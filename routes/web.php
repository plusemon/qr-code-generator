<?php

use App\Services\LicenseService;
use App\Http\Middleware\CheckLicense;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\LicenseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(CheckLicense::class)->group(function () {
    Route::get('/', ['\App\Http\Controllers\QrCodeController', 'home'])->name('home');
    Route::post('qr', ['\App\Http\Controllers\QrCodeController', 'print'])->name('print');
});

Route::get('/activate', [LicenseController::class, 'showActivationForm'])->name('license.form');
Route::post('/activate', [LicenseController::class, 'activate'])->name('license.activate');


Route::get('/generate-test-key', function () {
    $licenseService = new LicenseService();
    try {
        $key = $licenseService->generateLicenseKey(3);
        return "Generated License Key: <textarea rows='5' cols='80'>{$key}</textarea><br>Copy this key and use it in the activation form.";
    } catch (Exception $e) {
        return "Error generating key: " . $e->getMessage();
    }
});