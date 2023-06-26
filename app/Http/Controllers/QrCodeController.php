<?php

namespace App\Http\Controllers;

use App\Support\Collection;
use Illuminate\Http\Request;
use App\Imports\QrCodeImport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Validation\ValidationException;


class QrCodeController extends Controller
{

    public function __construct()
    {
        ini_set('max_execution_time', 36000);
    }

    public function home()
    {
        $directory = public_path('pdf');
        $items = array_diff(scandir($directory), array('..', '.'));

        $files = $items;

        $files = (new Collection($items))->paginate(20);

        return view('welcome', compact('files'));
    }

    public function print(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $border = $request->get('border');

        $qrcodes = Excel::toCollection(new QrCodeImport, $request->file('file'))->first()
            ->flatten();

        $pdf_name = 'kg_stiker_' . now('asia/dhaka')->format("Y_m_d_h_i_s") . ($border ? '_(with_border)' : '') . '.pdf';

        // artboard size in points (pt)

        $height = 720;
        $width = 540;


        $pdf = Pdf::loadView('print', compact('qrcodes', 'pdf_name', 'height', 'width', 'border'));
        $pdf->set_paper(array(0, 0, $width, $height));

        $pdf->save(public_path('pdf/' . $pdf_name));
        return $pdf->stream($pdf_name);
    }

    public function destroy($item)
    {
        return $item;
    }

    private function clearQrCodes()
    {
        $folder_path = public_path('qrcodes');

        // Delete all the files inside the given folder
        array_map('unlink', array_filter(glob("$folder_path/*"), 'is_file'));
    }
}
