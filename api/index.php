<?php

// 1. Inisialisasi struktur folder /tmp untuk environment serverless Vercel
$tmpDirs = [
    '/tmp/storage',
    '/tmp/storage/app',
    '/tmp/storage/app/public',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap',
    '/tmp/bootstrap/cache',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Inisialisasi SQLite database di /tmp/database.sqlite
$sqliteSource = __DIR__ . '/../database/database.sqlite';
$sqliteDest = '/tmp/database.sqlite';
if (!file_exists($sqliteDest) && file_exists($sqliteSource)) {
    @copy($sqliteSource, $sqliteDest);
}

// 2. Set environment overrides untuk serverless cache, storage & database
$_ENV['CACHE_STORE'] = 'array';
$_ENV['CACHE_DRIVER'] = 'array';
$_ENV['SESSION_DRIVER'] = 'cookie';
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/bootstrap/cache/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/bootstrap/cache/packages.php';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/bootstrap/cache/config.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/bootstrap/cache/routes.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/bootstrap/cache/events.php';

putenv('CACHE_STORE=array');
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=cookie');
putenv('APP_STORAGE=/tmp/storage');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');

// 3. Register autoloader & load Laravel app
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Set storage path Laravel ke /tmp/storage
$app->useStoragePath('/tmp/storage');

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
