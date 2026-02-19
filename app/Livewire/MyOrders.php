<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class MyOrders extends Component
{
    #[Layout('layouts.app')]
    #[Title('My Orders')]
    public function render()
    {
        $orders = Auth::user()->orders()->latest()->get();
        return view('livewire.my-orders', compact('orders'));
    }
}
