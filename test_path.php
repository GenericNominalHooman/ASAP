<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Raw path: " . storage_path('app/temp') . "\n";
echo "JSON encoded: " . json_encode(['path' => storage_path('app/temp')]) . "\n";
