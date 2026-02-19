<?php

use App\Models\ProductImage;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$latest = ProductImage::latest()->first();
echo "Latest Image Record:\n";
if ($latest) {
    print_r($latest->toArray());
} else {
    echo "No images found.\n";
}

echo "\nPublic Disk Config:\n";
print_r(config('filesystems.disks.public'));
