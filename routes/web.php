<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GajiSayaController;
use App\Http\Controllers\DokumenController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    Route::get('/gajiSaya', [GajiSayaController::class, 'index'])->name('gaji.saya');
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
