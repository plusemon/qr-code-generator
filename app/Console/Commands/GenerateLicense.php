<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LicenseService;

class GenerateLicense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new license key for a year';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $license = new LicenseService();
        $key = $license->generate(1); // 1 year license
        $this->info('License key: ' . $key);
        return 0;
    }
}
