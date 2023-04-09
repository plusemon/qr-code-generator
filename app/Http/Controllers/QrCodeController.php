<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\QrCodeImport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class QrCodeController extends Controller
{

    public function __construct()
    {
        ini_set('max_execution_time', 36000);
    }

    public function print(Request $request)
    {

        $request->validate([
            'file' => ['required', 'file'],
        ]);

        // $start = time();


        $qrcodes = Excel::toCollection(new QrCodeImport, $request->file('file'))->first()
            ->flatten();

        $sheets = $qrcodes->count() / 7;

        if ($sheets > 1000) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => 'Maximum 7000 qr code allowed'
            ]);
        }

        // echo ('Excel pharsed in => ' . time() - $start . ' sec');

        $this->clearQrCodes();

        // echo ('<br/> existing data cleared in => ' . time() - $start . ' sec');

        $qrcodes
            ->each(function ($item) {
                if (!file_exists(public_path("qrcodes/$item.svg")))
                    QrCode::size(40)->generate($item, public_path("qrcodes/$item.svg"));
            });

        // echo ('<br/> ' . $qrcodes->count() . ' qr codes(svg) saved in => ' . time() - $start . ' sec');

        $pdf_name = 'bizli_labels_' . now('asia/dhaka')->format("Y_m_d_h_i_s") . '.pdf';

        $pdf = Pdf::loadView('print', compact('qrcodes', 'pdf_name'));
        $pdf->set_paper(array(0, 0, 638, 1096));

        $pdf->save(public_path('pdf/' . $pdf_name));
        return $pdf->stream();

        // echo ('<br/> ' . $qrcodes->count() . ' qr code pdf (' . ($qrcodes->count() / 7) . ' pages) saved in => ' . time() - $start . ' sec');
        // exit;
        // view('print');
    }

    private function clearQrCodes()
    {
        $folder_path = public_path('qrcodes');

        // Delete all the files inside the given folder
        array_map('unlink', array_filter(glob("$folder_path/*"), 'is_file'));
    }
}
