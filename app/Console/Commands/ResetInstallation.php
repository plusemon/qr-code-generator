<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ResetInstallation extends Command
{
    protected $signature = 'install:reset';
    protected $description = 'Reset the installation';

    public function handle()
    {
        if (File::exists(storage_path('installed'))) {
            File::delete(storage_path('installed'));
            $this->info("Installation reset. License key removed.");
        } else {
            $this->info("Application is not currently installed.");
        }
    }
}
