<?php

use App\Http\Middleware\CheckLicense;
use Illuminate\Support\Facades\Route;
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
