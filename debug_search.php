<?php

use App\Models\Product;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$searchTerm = 'Outer'; // Testing category name search
$activeCategory = 'All';

echo "Searching for: '$searchTerm' with Active Category: '$activeCategory'\n";

DB::enableQueryLog();

$products = Product::with(['category', 'primaryImage', 'images', 'variants'])
    ->when($searchTerm, function ($query) use ($searchTerm) {
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('category', function ($cq) use ($searchTerm) {
                  $cq->where('name', 'like', '%' . $searchTerm . '%');
              });
        });
    })
    ->when($activeCategory !== 'All', function ($query) use ($activeCategory) {
        // ... (copy logic if needed, but for 'All' it's skipped)
    })
    ->get();

$log = DB::getQueryLog();
echo "SQL Query:\n";
// Print last query
$lastQuery = end($log);
// Hydrate bindings for easier reading
$sql = $lastQuery['query'];
foreach ($lastQuery['bindings'] as $binding) {
    // simple binding replacement for debug
    $binding = is_numeric($binding) ? $binding : "'$binding'";
    $sql = preg_replace('/\?/', $binding, $sql, 1);
}
echo $sql . "\n\n";

echo "Found " . $products->count() . " products:\n";
foreach ($products as $p) {
    echo "- [{$p->id}] {$p->name} (Cat: {$p->category->name})\n";
}
