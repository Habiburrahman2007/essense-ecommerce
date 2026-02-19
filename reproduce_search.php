<?php

use App\Models\Product;
use App\Models\Category;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate Logic from Dashboard.php
$search = 'Sweater'; 
$activeCategory = 'All'; // Simulating the reset state

echo "Searching for: '$search' with Category: '$activeCategory'\n";

$products = Product::query()
    ->with(['category'])
    ->when($search, function ($query) use ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhereHas('category', function ($cq) use ($search) {
                  $cq->where('name', 'like', '%' . $search . '%');
              });
        });
    })
    ->when($activeCategory !== 'All', function ($query) use ($activeCategory) {
        $category = Category::where('name', $activeCategory)->first();
        if ($category) {
             $categoryIds = $category->children()->pluck('id')->push($category->id);
             $query->whereIn('category_id', $categoryIds);
        }
    })
    ->get();

echo "Found " . $products->count() . " products.\n";
foreach ($products as $p) {
    echo "- [{$p->id}] {$p->name} (Cat: {$p->category->name})\n";
}
