<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     * Di lingkungan preview serverless (Vercel), kecualikan semua rute agar tidak terjadi 419 Page Expired
     * akibat arsitektur multi-instance stateless.
     *
     * @var array<int, string>
     */
    protected $except = [
        '*',
    ];
}
