<?php

use Illuminate\Support\Facades\Route;
Route::get('/', ['\App\Http\Controllers\QrCodeController', 'home'])->name('home');
Route::post('qr', ['\App\Http\Controllers\QrCodeController', 'print'])->name('print');