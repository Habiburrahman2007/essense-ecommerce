<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class LandingPage extends Component
{
    public function render()
    {
        // 1. Get Parent Categories and determine their image
        $categories = Category::whereNull('parent_id')
            ->orWhere('parent_id', 0)
            ->with(['children.products.primaryImage', 'children.products.images', 'products.primaryImage', 'products.images'])
            ->get()
            ->map(function ($category) {
                // Try to find an image from direct products
                $product = $category->products->first();
                
                // If no direct products, check children
                if (!$product) {
                    foreach ($category->children as $child) {
                        if ($child->products->isNotEmpty()) {
                            $product = $child->products->first();
                            break;
                        }
                    }
                }

                $image = null;
                if ($product) {
                     $imageModel = $product->primaryImage ?? $product->images->first();
                     if ($imageModel) {
                         $image = $imageModel->image_url;
                     }
                }
                
                $category->display_image = $image;
                return $category;
            });

        // 2. Get Daily Essentials (Random products)
        $products = Product::with(['category', 'primaryImage', 'images'])
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('welcome', [
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
