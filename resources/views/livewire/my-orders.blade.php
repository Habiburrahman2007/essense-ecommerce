<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-serif text-charcoal mb-8">My Orders</h1>

    <div class="space-y-6">
        @forelse($orders as $order)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-taupe/20 transition hover:shadow-md">
                <div class="flex flex-col md:flex-row justify-between md:items-center mb-4 gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="font-bold text-lg text-charcoal">#{{ $order->order_code }}</span>
                            <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full 
                                @if($order->status == 'paid' || $order->status == 'completed') bg-green-100 text-green-700
                                @elseif($order->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($order->status == 'cancelled' || $order->status == 'failed') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-charcoal/60">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-charcoal/60 mb-1">Total Amount</p>
                        <p class="text-xl font-bold text-clay">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="border-t border-taupe/10 pt-4 mt-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex-1">
                             <p class="text-sm text-charcoal/80 mb-2 font-medium">Items:</p>
                             <ul class="text-sm text-charcoal/60 space-y-1">
                                @foreach($order->items->take(2) as $item)
                                    <li>{{ $item->quantity }}x {{ $item->product_name }} ({{ $item->size_name }}, {{ $item->color_name }})</li>
                                @endforeach
                                @if($order->items->count() > 2)
                                    <li class="italic text-xs">+{{ $order->items->count() - 2 }} more items...</li>
                                @endif
                             </ul>
                        </div>
                        
                        <div class="flex gap-3 w-full sm:w-auto">
                            @if($order->status == 'pending')
                                <a href="{{ route('payment.success', ['orderId' => $order->order_code]) }}" class="flex-1 sm:flex-none text-center px-6 py-2 bg-charcoal text-white rounded-xl text-sm font-bold uppercase tracking-widest hover:bg-black transition-colors">
                                    Pay Now
                                </a>
                            @else
                                <a href="{{ route('payment.success', ['orderId' => $order->order_code]) }}" class="flex-1 sm:flex-none text-center px-6 py-2 bg-sand text-charcoal rounded-xl text-sm font-bold uppercase tracking-widest hover:bg-taupe/50 transition-colors">
                                    View Details
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white/50 rounded-3xl border border-dashed border-taupe/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-taupe mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <h3 class="text-xl font-serif text-charcoal mb-2">No orders found</h3>
                <p class="text-charcoal/60 mb-6">Looks like you haven't placed any orders yet.</p>
                <a href="{{ route('dashboard') }}" class="inline-block px-8 py-3 bg-clay text-white rounded-xl font-bold uppercase tracking-widest hover:bg-charcoal transition-all shadow-lg hover:shadow-xl text-xs">
                    Start Shopping
                </a>
            </div>
        @endforelse
    </div>
</div>
