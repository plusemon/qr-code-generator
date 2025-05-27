<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class LicenseService
{
    private $privateKey = 'plusemon!@#123';
    private $licenseFile;
    private $licenseData;

    public function __construct()
    {
        $this->licenseFile = storage_path('app/license.dat');
        $this->loadLicense();
    }

    private function loadLicense()
    {
        if (file_exists($this->licenseFile)) {
            $encrypted = file_get_contents($this->licenseFile);
            $this->licenseData = json_decode(Crypt::decrypt($encrypted), true);
        }
    }

    public function generate($years = 1)
    {
        $expiry = Carbon::now()->addYears($years);
        $data = [
            'key' => 'LIC-' . Str::upper(Str::random(16)),
            'hardware_id' => $this->getHardwareId(),
            'expires_at' => $expiry->toDateTimeString(),
            'activated_at' => now()->toDateTimeString()
        ];

        $signature = hash_hmac('sha256', json_encode($data), $this->privateKey);
        $data['signature'] = $signature;

        file_put_contents(
            $this->licenseFile,
            Crypt::encrypt(json_encode($data))
        );

        return $data['key'];
    }

    public function validate($licenseKey)
    {
        if (!file_exists($this->licenseFile)) {
            return false;
        }

        $data = $this->licenseData;

        // Verify key matches
        if ($data['key'] !== $licenseKey) {
            return false;
        }

        // Verify signature
        $signature = $data['signature'];
        unset($data['signature']);
        $expected = hash_hmac('sha256', json_encode($data), $this->privateKey);

        if (!hash_equals($expected, $signature)) {
            return false;
        }

        // Check expiry
        return Carbon::parse($data['expires_at'])->isFuture();
    }

    public function getHardwareId()
    {
        $components = [
            php_uname(),
            $_SERVER['SERVER_NAME'] ?? '',
            $_SERVER['COMPUTERNAME'] ?? '',
            file_exists('/etc/machine-id') ? file_get_contents('/etc/machine-id') : ''
        ];

        return hash('sha256', implode('|', $components));
    }

    public function isValid()
    {
        return $this->licenseData && $this->validate($this->licenseData['key']);
    }

    public function getLicenseData($key = null)
    {
        return $key ? data_get($this->licenseData, $key) : $this->licenseData;
    }
}