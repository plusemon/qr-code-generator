<?php

namespace App\Providers;

use App\Models\License;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Check license on every request if installed
        // if (File::exists(storage_path('installed'))) {
        //     $licenseKey = File::get(storage_path('installed'));
        //     $license = License::where('license_key', $licenseKey)->first();

        //     if (!$license || !$license->is_active || $license->expires_at < now()) {
        //         // License is invalid - show warning or disable features
        //         config(['app.license_valid' => false]);
        //     } else {
        //         config(['app.license_valid' => true]);
        //     }
        // }
        Paginator::useBootstrap();
    }
}
