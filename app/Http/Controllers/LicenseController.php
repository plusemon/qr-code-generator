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
            $message = 'License activated until ' . $license->getLicenseData('expires_at');
            return redirect('/')->with('success', $message);
        }

        return back()->withErrors(['activation_key' => 'Invalid activation key.']);
    }
}