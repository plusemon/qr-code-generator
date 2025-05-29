<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LicenseService;

class LicenseController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    public function showActivationForm()
    {
        // Check if already active, redirect if it is
        if ($this->licenseService->isActive()) {
            return redirect('/')->with('success', 'Application is already active.');
        }
        return view('license');
    }

    public function activate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
        ]);

        if ($this->licenseService->activate($request->input('license_key'))) {
            return redirect('/')->with('success', 'Application activated successfully!');
        } else {
            return back()->withInput()->withErrors(['license_key' => 'Invalid or expired license key.']);
        }
    }
}