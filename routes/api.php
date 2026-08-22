<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'system' => 'E-SCM Marmer Tulungagung API',
        'status' => 'operational',
        'timestamp' => now()->toIso8601String()
    ]);
});
