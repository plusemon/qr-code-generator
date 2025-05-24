<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallController;

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

Route::get('/', ['\App\Http\Controllers\QrCodeController', 'home'])->name('home');

Route::post('qr', ['\App\Http\Controllers\QrCodeController', 'print'])->name('print');

// Route::delete('qr/{path}', ['\App\Http\Controllers\QrCodeController', 'destroy'])->name('qr.destroy');


Route::prefix('install')->group(function () {
    Route::get('license', [InstallController::class, 'showLicenseForm'])->name('install.license');
    Route::post('validate', [InstallController::class, 'validateLicense'])->name('install.validate');
});