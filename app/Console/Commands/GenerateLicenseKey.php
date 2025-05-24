<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateLicenseKey extends Command
{
    protected $signature = 'license:generate';
    protected $description = 'Generate a license key';

    public function handle()
    {
        $key = 'LIC-' . strtoupper(bin2hex(random_bytes(8)));
        $this->info("Generated License Key: " . $key);
    }
}
