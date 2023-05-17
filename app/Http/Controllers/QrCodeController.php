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

        $files = collect($items)->reverse()->values();

        $files = (new Collection($items))->paginate(20);

        return view('welcome', compact('files'));
    }

    public function print(Request $request)
    {

        $request->validate([
            'file' => ['required', 'file'],
        ]);

        // $start = time();

        $qrcodes = Excel::toCollection(new QrCodeImport, $request->file('file'))->first()
            ->flatten();

        if ($qrcodes->count() > 3500) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => 'Maximum 3500 items allowed. (This file has ' . $qrcodes->count() . ' items)'
            ]);
        }

        // echo ('Excel pharsed in => ' . time() - $start . ' sec');

        // $this->clearQrCodes();

        // echo ('<br/> existing data cleared in => ' . time() - $start . ' sec');

        // $qrcodes
        //     ->each(function ($item) {
        //         if (!file_exists(public_path("qrcodes/$item.svg")))
        //             QrCode::size(40)->generate($item, public_path("qrcodes/$item.svg"));
        //     });

        // echo ('<br/> ' . $qrcodes->count() . ' qr codes(svg) saved in => ' . time() - $start . ' sec');

        $pdf_name = 'bizli_labels_' . now('asia/dhaka')->format("Y_m_d_h_i_s") . '.pdf';

        // return view('print', compact('qrcodes', 'pdf_name'));

        $pdf = Pdf::loadView('print', compact('qrcodes', 'pdf_name'));
        $pdf->set_paper(array(0, 0, 792, 504));
        return $pdf->stream($pdf_name);

        // $pdf->save(public_path('pdf/' . $pdf_name));
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
