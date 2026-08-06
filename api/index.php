<?php

// 1. Force raw PHP errors to surface if execution halts
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// 2. Build required writable folders in Vercel's /tmp execution space
$dirs = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 3. Touch SQLite file for temporary fallback database handling
if (!file_exists('/tmp/database.sqlite')) {
    touch('/tmp/database.sqlite');
}

// 4. Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 5. Bootstrap Laravel Application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 6. Explicitly override Laravel storage and cache paths to /tmp
$app->useStoragePath('/tmp/storage');

// 7. Process Incoming Request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);