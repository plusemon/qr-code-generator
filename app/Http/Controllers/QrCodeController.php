<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\QrCodeImport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class QrCodeController extends Controller
{
    public function print(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $qrcodes = Excel::toCollection(new QrCodeImport, $request->file('file'))->first()->flatten();

        $data['items'] = $qrcodes->map(function ($item) {

            $file_name = $item . '.svg';
            $file_path = public_path("qrcodes/$file_name");

            if (!file_exists($file_path)) QrCode::size(40)->generate($item, $file_path);

            return [
                'img' => url("qrcodes/$file_name"),
                'code' => $item
            ];
        })->chunk(7);

        $pdf_name = 'bizli_mrp_label_qrcodes_'.now('asia/dhaka')->format("Y_m_d_h_i_s") . '.pdf';
        $data['pdf_name'] = $pdf_name;
        $pdf = Pdf::loadView('print', $data);

        $width = 638;
        $height = 1096;

        $pdf->set_paper(array(0, 0, $width, $height));
        return $pdf->stream($pdf_name);
    }
}
