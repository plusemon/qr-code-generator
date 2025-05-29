<?php

namespace App\Http\Controllers;

use App\Services\LicenseService;
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

    public function home(LicenseService $licenseService)
    {


        $license = $licenseService->getLicenseInfo();
        $remainingDays = $licenseService->getRemainingDays();

        $directory = public_path('pdf');
        $items = array_diff(scandir($directory), array('..', '.'));

        $files = $items;

        $files = (new Collection($items))->paginate(20);

        return view('welcome', compact('files', 'license', 'remainingDays'));
    }

    public function print(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        try {
            // Determine if QR codes should have a border
            $border = $request->get('border');

            // Parse the uploaded Excel file and flatten the data
            $qrcodes = Excel::toCollection(new QrCodeImport, $request->file('file'))->first()->flatten();

            // Check if the number of QR codes exceeds the limit
            if ($qrcodes->count() > 5000) {
                throw ValidationException::withMessages([
                    'file' => 'Maximum 5000 items allowed. (This file has ' . $qrcodes->count() . ' items)'
                ]);
            }

            // Clear existing QR codes
            $this->clearQrCodes();

            // Generate QR codes and save them as SVG files
            $qrcodes->each(function ($item) {
                if (!file_exists(public_path("qrcodes/$item.svg"))) {
                    QrCode::size(30)->generate($item, public_path("qrcodes/$item.svg"));
                }
            });

            // Create a unique PDF name based on the current time and border option
            $pdf_name = now('asia/dhaka')->format("Y_m_d_h_i_s") . ($border ? '_(with_border)' : '') . '.pdf';

            // Define the PDF page size in (pt)
            $width = 595.2756;
            $height = 841.8898;

            // Load the view for printing, set the paper size, and save the PDF
            $pdf = Pdf::loadView('print', compact('qrcodes', 'pdf_name', 'height', 'width', 'border'));
            $pdf->set_paper([0, 0, $width, $height]);

            // Save the PDF to the public directory and stream it as a response
            $pdf->save(public_path('pdf/' . $pdf_name));
            return $pdf->stream($pdf_name);
        } catch (\Throwable $th) {
            // throw $th;
            return back()->withErrors('file', $th->getMessage());
        }
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
