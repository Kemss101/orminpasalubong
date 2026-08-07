<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Load application
$app = require_once __DIR__.'/../bootstrap/app.php';

// Configure read-only filesystem workaround for Vercel
$storagePath = '/tmp/storage';
if (!file_exists($storagePath)) {
    mkdir($storagePath . '/framework/views', 0755, true);
    mkdir($storagePath . '/framework/sessions', 0755, true);
    mkdir($storagePath . '/framework/cache', 0755, true);
    mkdir($storagePath . '/logs', 0755, true);
}
$app->useStoragePath($storagePath);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

try {
    $response = $kernel->handle(
        $request = Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    // 1. Output short concise error to Vercel STDERR (prevents log truncation)
    file_put_contents('php://stderr', "\n\n=== LARAVEL EXCEPTION ===\n" . $e->getMessage() . "\nin " . $e->getFile() . ":" . $e->getLine() . "\n=========================\n\n");

    // 2. Output directly to response body
    http_response_code(500);
    echo "<h1>Server Error</h1>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</p>";
    exit;
}