<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PaymentSuccess extends Component
{
    public $order;
    public $orderItems;
    
    public function mount($orderId)
    {
        // Load order with relationships
        $this->order = Order::with(['items', 'address', 'payment'])
            ->where('order_code', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $this->orderItems = $this->order->items;
        
        // Clear selected items from cart
        $cart = session('cart', []);
        $selectedSkus = session('selected_items_checkout', []);
        
        foreach ($selectedSkus as $sku) {
            unset($cart[$sku]);
        }
        
        session(['cart' => $cart]);
        session()->forget('selected_items_checkout');
    }
    
    public function downloadInvoice()
    {
        // TODO: Implement PDF invoice generation
        $this->dispatch('notify', message: 'Invoice download feature coming soon!');
    }
    
    public function continueShopping()
    {
        return redirect()->route('dashboard');
    }
    
    #[Layout('layouts.app')]
    #[Title('Payment Success')]
    public function render()
    {
        return view('livewire.payment-success');
    }
}
