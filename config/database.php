<?php

use Illuminate\Support\Str;

$rawHost = env('DB_HOST', (str_contains(env('DB_CONNECTION', ''), '.') ? env('DB_CONNECTION') : '127.0.0.1'));
$cleanHost = preg_replace('#^https?://#', '', (string)$rawHost);
if (str_contains($cleanHost, ':')) {
    $parts = explode(':', $cleanHost);
    $cleanHost = $parts[0];
}

$port = (int) env('DB_PORT', (str_contains($cleanHost, 'tidbcloud.com') ? 4000 : 3306));
if ($port === 3306 && str_contains($cleanHost, 'tidbcloud.com')) {
    $port = 4000;
}

return [

    'default' => in_array(env('DB_CONNECTION'), ['mysql', 'sqlite', 'pgsql', 'sqlsrv']) ? env('DB_CONNECTION') : (file_exists('/tmp/database.sqlite') || file_exists(database_path('database.sqlite')) ? 'sqlite' : 'mysql'),

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', file_exists('/tmp/database.sqlite') ? '/tmp/database.sqlite' : database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => $cleanHost,
            'port' => $port,
            'database' => env('DB_DATABASE', 'test'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => 'InnoDB',
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA', (str_contains($cleanHost, 'tidbcloud.com') && file_exists('/etc/ssl/certs/ca-certificates.crt')) ? '/etc/ssl/certs/ca-certificates.crt' : null),
                PDO::ATTR_TIMEOUT => 10,
            ]) : [],
        ],

    ],

    'migrations' => 'migrations',

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => (int) env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', '0'),
        ],

    ],

];
