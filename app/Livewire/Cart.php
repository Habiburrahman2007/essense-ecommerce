<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public $cartItems = [];
    public $selectedItems = [];

    public function mount()
    {
        $this->cartItems = session('cart', []);
        // By default, select all items
        $this->selectedItems = array_keys($this->cartItems);
    }

    public function removeItem($sku)
    {
        $cart = session('cart', []);
        
        if (isset($cart[$sku])) {
            unset($cart[$sku]);
            session(['cart' => $cart]);
            $this->cartItems = $cart;
            
            // Remove from selection if exists
            $key = array_search($sku, $this->selectedItems);
            if ($key !== false) {
                unset($this->selectedItems[$key]);
            }
        }
    }

    public function updateQuantity($sku, $change)
    {
        $cart = session('cart', []);

        if (isset($cart[$sku])) {
            $newQuantity = $cart[$sku]['quantity'] + $change;

            if ($newQuantity > 0) {
                $cart[$sku]['quantity'] = $newQuantity;
                session(['cart' => $cart]);
                $this->cartItems = $cart;
            }
        }
    }

    public function toggleSelection($sku)
    {
        if (in_array($sku, $this->selectedItems)) {
            $this->selectedItems = array_diff($this->selectedItems, [$sku]);
        } else {
            $this->selectedItems[] = $sku;
        }
    }

    public function toggleAll()
    {
        if (count($this->selectedItems) === count($this->cartItems)) {
            $this->selectedItems = [];
        } else {
            $this->selectedItems = array_keys($this->cartItems);
        }
    }

    #[Layout('layouts.app')]
    #[Title('Shopping Cart')]
    public function render()
    {
        $total = collect($this->cartItems)
            ->whereIn('sku', $this->selectedItems) // Filter by selection
            ->sum(function ($item) {
                // We need to match by array key (SKU), but collection makes keys available or we iterate.
                // Actually collect($assocArray) preserves keys.
                return $item['price'] * $item['quantity'];
            });
            
        // Fix: logic above might fail because whereIn checks values. 
        // Better:
        $total = 0;
        foreach ($this->selectedItems as $sku) {
            if (isset($this->cartItems[$sku])) {
                $total += $this->cartItems[$sku]['price'] * $this->cartItems[$sku]['quantity'];
            }
        }

        return view('livewire.cart', [
            'total' => $total,
            'allSelected' => count($this->cartItems) > 0 && count($this->selectedItems) === count($this->cartItems)
        ]);
    }

    public function checkout()
    {
        // 1. Validate selection
        if (empty($this->selectedItems)) {
            $this->dispatch('notify', message: 'Please select items to checkout.');
            return;
        }

        // 2. Calculate Total and prepare items
        $total = 0;
        $itemsDetails = [];
        $orderItemsData = [];
        
        foreach ($this->selectedItems as $sku) {
            if (isset($this->cartItems[$sku])) {
                $item = $this->cartItems[$sku];
                $lineTotal = $item['price'] * $item['quantity'];
                $total += $lineTotal;

                $itemsDetails[] = [
                    'id' => $sku,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'name' => substr($item['name'], 0, 50), // Midtrans limit
                ];
                
                // Get variant ID from session (stored as 'id')
                $variantId = $item['id'] ?? ($item['variant_id'] ?? 0);
                
                // Validate variant existence
                if ($variantId == 0) {
                     $this->dispatch('notify', message: 'Item ' . $item['name'] . ' has invalid data. Please remove and add again.');
                     return;
                }

                // Prepare order items data
                $orderItemsData[] = [
                    'product_variant_id' => $variantId,
                    'product_name' => $item['name'],
                    'size_name' => $item['size_name'] ?? ($item['size'] ?? 'N/A'),
                    'color_name' => $item['color_name'] ?? ($item['color'] ?? 'N/A'),
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $lineTotal,
                ];
            }
        }

        if ($total <= 0) {
            $this->dispatch('notify', message: 'Invalid cart total.');
            return;
        }

        // 3. Validate Address
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Get the latest address used/created
        $address = $user->addresses()->latest()->first();

        if (!$address) {
            $this->dispatch('notify', message: 'Please add a shipping address in My Account before checkout.');
            return;
        }

        // 4. Generate Order ID
        $orderId = 'ORDER-' . time() . '-' . rand(100, 999);
        
        // 5. Create Order in Database
        try {
            $order = \App\Models\Order::create([
                'user_id' => Auth::id(),
                'address_id' => $address->id,
                'order_code' => $orderId,
                'total_price' => $total,
                'invoice_number' => 'INV-' . date('Ymd') . '-' . rand(1000, 9999),
                'invoice_date' => now(),
                'status' => 'pending',
            ]);
            
            // 5. Create Order Items
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }
            
            // 6. Save selected items to session for clearing later
            session(['selected_items_checkout' => $this->selectedItems]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to create order: ' . $e->getMessage());
            return;
        }

        // 7. Configure Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        // 8. Create Transaction Params
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'item_details' => $itemsDetails,
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'callbacks' => [
                'finish' => route('midtrans.finish') . '?order_id=' . $orderId,
            ]
        ];

        try {
            // 9. Get Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // 10. Dispatch event to frontend
            $this->dispatch('show-snap-modal', token: $snapToken);

        } catch (\Exception $e) {
            // Delete the order if payment setup fails
            $order->delete();
            $this->dispatch('notify', message: 'Payment error: ' . $e->getMessage());
        }
    }
}
