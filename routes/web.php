<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicCatalogController;

/*
|--------------------------------------------------------------------------
| Web Routes - E-SCM Marmer Tulungagung
|--------------------------------------------------------------------------
| Sesuai Dokumen Pembagian Tugas: Docs/PEMBAGIAN_TUGAS_MIGRASI.md
*/

// Public Front-End Landing Page & Product Catalog Showcase
Route::get('/', [PublicCatalogController::class, 'index'])->name('home');
Route::get('/katalog', [PublicCatalogController::class, 'catalog'])->name('catalog');
Route::get('/katalog/{id}', [PublicCatalogController::class, 'show'])->name('catalog.show');

// Load routes per modul
require __DIR__ . '/modules/auth.php';

Route::middleware(['web'])->group(function () {
    require __DIR__ . '/modules/materials.php';    // Dikelola Alvin
    require __DIR__ . '/modules/production.php';   // Dikelola Alvin
    require __DIR__ . '/modules/qc.php';           // Dikelola Dapin
    require __DIR__ . '/modules/distribution.php'; // Dikelola Dapin
    require __DIR__ . '/modules/analytics.php';    // Dikelola Dapin
});
