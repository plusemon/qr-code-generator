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
            'file' => ['required', 'file'],
            'per_page' => ['nullable', 'numeric'],
            'margin' => ['nullable', 'numeric'],
        ]);

        $config = [
            'size' => $request->input('size') ?? 230,
            'per_page' => $request->input('per_page') ?? 9,
            'margin' => $request->input('margin') ?? 10,
        ];


        $data['config'] = $config;
        $data['qrcodes'] = Excel::toCollection(new QrCodeImport, $request->file('file'))->first()->chunk($config['per_page']);
        return view('print', $data);
    }
}
