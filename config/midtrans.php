<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Merchant Credentials
    |--------------------------------------------------------------------------
    | Kredensial diperoleh dari portal dashboard Midtrans (Sandbox / Production).
    */
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),
    'client_key'  => env('MIDTRANS_CLIENT_KEY', ''),
    'server_key'  => env('MIDTRANS_SERVER_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Environment Settings
    |--------------------------------------------------------------------------
    | Set false untuk Sandbox (testing simulator), true untuk Production (live).
    */
    'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized'  => (bool) env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds'        => (bool) env('MIDTRANS_IS_3DS', true),

    /*
    |--------------------------------------------------------------------------
    | Snap JS Script URL
    |--------------------------------------------------------------------------
    | Endpoint URL file JS library Snap sesuai mode environment.
    */
    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',
];
