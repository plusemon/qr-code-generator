<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\QrCodeImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class QrCodeController extends Controller
{
    public function print(Request $request)
    {
        $request->validate([
            'file' => ['required','file']
        ]);

        $qrcodes = Excel::toCollection(new QrCodeImport, $request->file('file'))
        ->first()->chunk(9);

        return view('print', compact('qrcodes'));
    }
}
