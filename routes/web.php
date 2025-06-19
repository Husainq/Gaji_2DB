<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GajiSayaController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/gajiSaya', [GajiSayaController::class, 'index'])->name('gaji.saya');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
