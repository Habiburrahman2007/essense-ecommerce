<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class ProductDetail extends Component
{
    public $slug;
    public $product;
    public $selectedSize = null;
    public $selectedColor = null;
    public $selectedQuantity = 1;
    public $feedbackMessage = ''; // To show "Added to Cart" feedback

    // Computed properties for UI availability
    public $availableSizes = [];
    public $availableColors = [];
    public $currentPrice;
    public $currentStock;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->product = Product::with(['category', 'images', 'primaryImage', 'variants.size', 'variants.color'])
            ->where('slug', $slug)
            ->firstOrFail();

        $this->currentPrice = $this->product->base_price;
        $this->currentStock = $this->product->variants->sum('stock');
        
        // Initialize available options
        $this->computeAvailableOptions();
    }
    
    public function computeAvailableOptions()
    {
        // Get all variants
        $variants = $this->product->variants;

        // Populate available sizes and colors
        // Note: Ideally you want to filter based on selection (e.g. if size L selected, only show colors available for L)
        // For now, we show all sizes and colors that exist for this product.
        $this->availableSizes = $variants->pluck('size.name', 'size.id')->unique()->toArray();
        $this->availableColors = $variants->pluck('color.name', 'color.id')->unique()->toArray();
    }
    
    public function updatedSelectedSize($value)
    {
        $this->updateVariantState();
    }

    public function updatedSelectedColor($value)
    {
        $this->updateVariantState();
    }

    public function toggleSize($id)
    {
        $this->selectedSize = ($this->selectedSize == $id) ? null : $id;
        $this->updateVariantState();
    }

    public function toggleColor($id)
    {
        $this->selectedColor = ($this->selectedColor == $id) ? null : $id;
        $this->updateVariantState();
    }
    
    public function updateVariantState()
    {
        if ($this->selectedSize && $this->selectedColor) {
            $variant = $this->product->variants
                ->where('size_id', $this->selectedSize)
                ->where('color_id', $this->selectedColor)
                ->first();
                
            if ($variant) {
                $this->currentPrice = $variant->price;
                $this->currentStock = $variant->stock;
            } else {
                 // Option selected but no variant exists (e.g. Red XL doesn't exist)
                $this->currentStock = 0; 
            }
        }
    }

    public function addToCart()
    {
        $this->addVariantToCart(false);
    }

    public function buyNow()
    {
        $this->addVariantToCart(true);
    }

    protected function addVariantToCart($redirect = false)
    {
        // Validation
        $this->validate([
            'selectedSize' => 'required',
            'selectedColor' => 'required',
        ], [
            'selectedSize.required' => 'Please select a size.',
            'selectedColor.required' => 'Please select a color.',
        ]);

        $variant = $this->product->variants
            ->where('size_id', $this->selectedSize)
            ->where('color_id', $this->selectedColor)
            ->first();

        if (!$variant) {
            $this->addError('selectedSize', 'This combination is unavailable.');
            return;
        }

        if ($variant->stock < 1) {
            $this->addError('selectedSize', 'This item is out of stock.');
            return;
        }

        // Cart Logic
        $cart = session('cart', []);
        $sku = $variant->sku; // Assuming variant has SKU or unique ID. Ideally Variant ID.

        // Fallback SKU if null
        if (!$sku) {
            $sku = $this->product->slug . '-' . $variant->id; 
        }

        if (isset($cart[$sku])) {
            $cart[$sku]['quantity'] += 1;
        } else {
            $image = $this->product->primaryImage ?? $this->product->images->first();
            $imageUrl = $image ? $image->image_url : 'assets/images/prod_knit.png';

            $cart[$sku] = [
                'id' => $variant->id,
                'name' => $this->product->name,
                'price' => $variant->price ?? $this->product->base_price,
                'quantity' => 1,
                'size_name' => $variant->size->name,
                'color_name' => $variant->color->name,
                'image_url' => $imageUrl,
            ];
        }

        session(['cart' => $cart]);

        if ($redirect) {
            return redirect()->route('cart');
        }

        // Feedback
        $this->feedbackMessage = 'Added to Cart!';
        
        // Reset message after 2s
        $this->dispatch('cart-updated'); 
    }

    #[Layout('layouts.app')]
    #[Title('Product Detail')]
    public function render()
    {
        $relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('livewire.product-detail', [
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
