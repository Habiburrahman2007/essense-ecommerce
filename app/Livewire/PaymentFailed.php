<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PaymentFailed extends Component
{
    public $order;
    public $orderItems;
    public $errorMessage;
    
    public function mount($orderId)
    {
        // Load order with relationships
        $this->order = Order::with(['items', 'address', 'payment'])
            ->where('order_code', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $this->orderItems = $this->order->items;
        
        // Get error message from payment
        if ($this->order->payment) {
            $payload = $this->order->payment->payload;
            $this->errorMessage = $payload['status_message'] ?? 'Payment was declined or cancelled.';
        } else {
            $this->errorMessage = 'Payment was declined or cancelled.';
        }
    }
    
    public function retryPayment()
    {
        return redirect()->route('cart');
    }
    
    public function backToCart()
    {
        return redirect()->route('cart');
    }
    
    #[Layout('layouts.app')]
    #[Title('Payment Failed')]
    public function render()
    {
        return view('livewire.payment-failed');
    }
}
