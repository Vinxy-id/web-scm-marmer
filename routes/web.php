<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - E-SCM Marmer Tulungagung
|--------------------------------------------------------------------------
| Sesuai Dokumen Pembagian Tugas: Docs/PEMBAGIAN_TUGAS_MIGRASI.md
*/

// Redirect root to dashboard (or login if not authenticated)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Load routes per modul
require __DIR__ . '/modules/auth.php';

Route::middleware(['web'])->group(function () {
    require __DIR__ . '/modules/materials.php';    // Dikelola Alvin
    require __DIR__ . '/modules/production.php';   // Dikelola Alvin
    require __DIR__ . '/modules/qc.php';           // Dikelola Dapin
    require __DIR__ . '/modules/distribution.php'; // Dikelola Dapin
    require __DIR__ . '/modules/analytics.php';    // Dikelola Dapin
});
