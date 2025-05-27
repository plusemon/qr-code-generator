<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LicenseService;

class LicenseController extends Controller
{
    public function showForm()
    {
        return view('license');
    }

    public function activate(Request $request, LicenseService $license)
    {
        $request->validate([
            'activation_key' => ['required', 'string'],
        ]);

        if ($license->validate($request->activation_key)) {
            return redirect('/')->with('success', 'License activated until ' .
                $license->licenseData['expires_at']);
        }

        return back()->withErrors(['activation_key' => 'Invalid activation key.']);
    }
}