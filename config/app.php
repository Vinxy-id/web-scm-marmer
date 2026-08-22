<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'name' => env('APP_NAME', 'E-SCM IKM Marmer Tulungagung'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost:8000'),

    'asset_url' => env('ASSET_URL'),

    'timezone' => 'Asia/Jakarta',

    'locale' => 'id',

    'fallback_locale' => 'en',

    'faker_locale' => 'id_ID',

    'key' => env('APP_KEY', 'base64:IhDKGEhZ4YlerAkEn6Q3nAbOh/11KjtnL7/7TDCbw04='),

    'cipher' => 'AES-256-CBC',

    'maintenance' => [
        'driver' => 'file',
    ],

    'providers' => ServiceProvider::defaultProviders()->merge([
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        // Custom Facades
    ])->toArray(),

];
