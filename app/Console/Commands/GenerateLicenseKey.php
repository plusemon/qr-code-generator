<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Services\LicenseService;

class GenerateLicenseKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:generate {--days=365}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a new encrypted license key for a client.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $licenseService = new LicenseService();
        try {
            $key = $licenseService->generateLicenseKey($this->option('days'), Carbon::now()->addDays($this->option('days'))->format('d-m-Y'));
            $this->info('Generated license key: ' . $key);
        } catch (Exception $e) {
            $this->error('Error generating license key: ' . $e->getMessage());
        }
    }
}