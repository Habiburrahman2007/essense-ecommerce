<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Dashboard extends Component
{
    public $search = '';
    public $activeCategory = 'All';
    public $breadcrumbs = [];

    protected $queryString = ['search', 'activeCategory'];

    #[Layout('layouts.app')]
    #[Title('Storefront')]
    public function render()
    {
        $this->generateBreadcrumbs();

        // category logic
        if ($this->activeCategory === 'All') {
             $categories = \App\Models\Category::whereNull('parent_id')->orWhere('parent_id', 0)->pluck('name');
        } else {
             $currentCategory = \App\Models\Category::where('name', $this->activeCategory)->first();
             if ($currentCategory) {
                 if ($currentCategory->children()->exists()) {
                     $categories = $currentCategory->children()->pluck('name');
                 } else {
                     $categories = $currentCategory->parent ? $currentCategory->parent->children()->pluck('name') : collect([]);
                 }
             } else {
                 $categories = collect([]);
             }
        }
        
        // Product Logic
        $products = \App\Models\Product::with(['category', 'primaryImage', 'images', 'variants'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function ($cq) {
                          $cq->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->activeCategory !== 'All', function ($query) {
                $category = \App\Models\Category::where('name', $this->activeCategory)->first();
                if ($category) {
                     // Include products from children as well if it's a parent category
                     $categoryIds = $category->children()->pluck('id')->push($category->id);
                     $query->whereIn('category_id', $categoryIds);
                }
            })
            ->get()
            ->map(function ($product) {
               $product->stock = $product->variants->sum('stock');
               return $product;
            });

        return view('livewire.dashboard', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function setCategory($category)
    {
        $this->activeCategory = $category;
        $this->search = ''; // Clear search when category is selected
    }

    public function updatedSearch()
    {
        $this->activeCategory = 'All'; // Reset category when searching
    }
    
    public function generateBreadcrumbs()
    {
        $this->breadcrumbs = [['name' => 'Home', 'action' => 'setCategory(\'All\')']];
        
        if ($this->activeCategory !== 'All') {
            $category = \App\Models\Category::where('name', $this->activeCategory)->first();
            
            if ($category) {
                // If it has a parent, add parent first
                if ($category->parent) {
                     $this->breadcrumbs[] = [
                         'name' => $category->parent->name,
                         'action' => "setCategory('{$category->parent->name}')"
                     ];
                }
                
                // Add current
                $this->breadcrumbs[] = [
                    'name' => $category->name,
                    'action' => null // Current page, no action or maybe refresh
                ];
            }
        }
    }
}
