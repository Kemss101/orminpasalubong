<?php

// Force display of fatal PHP errors before framework boot
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Create required writable directories in Vercel's /tmp environment
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

// Override storage & bootstrap paths to /tmp
putenv('APP_STORAGE_PATH=/tmp/storage');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');

// Load bootstrap file
require __DIR__ . '/../public/index.php';