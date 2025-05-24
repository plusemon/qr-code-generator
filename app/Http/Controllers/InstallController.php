<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\License;

class InstallController extends Controller
{
    public function showLicenseForm()
    {
        return view('install');
    }

    public function validateLicense(Request $request)
    {
        $valid = $this->validateLicenseKey($request->license_key);

        if ($valid) {
            // Create installed file
            File::put(storage_path('installed'), $request->license_key);

            // Store license in database
            License::create([
                'license_key' => $request->license_key,
                'expires_at' => now()->addYear(),
                'is_active' => true
            ]);

            return redirect('/');
        }

        return back()->with('error', 'Invalid license key');
    }

    protected function validateLicenseKey($key)
    {
        // Implement your validation logic here
        // This could be checking against your license server
        // Or validating the key format

        return preg_match('/^LIC-[A-Z0-9]{16}$/', $key);
    }
}