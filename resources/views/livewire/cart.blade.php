<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12">
        <h1 class="text-4xl font-serif text-charcoal mb-4">Shopping Cart</h1>
        <p class="text-taupe font-medium">Review your selected items before checkout.</p>
    </div>

    @if(count($cartItems) > 0)
        <div class="bg-white/60 backdrop-blur-md rounded-[2.5rem] border border-taupe/20 p-8 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold uppercase tracking-widest text-charcoal/40 border-b border-taupe/10">
                            <th class="pb-6 pl-4 w-10">
                                <input type="checkbox" wire:click="toggleAll" @if($allSelected) checked @endif class="rounded border-taupe text-charcoal focus:ring-charcoal/30 bg-sand/50 h-4 w-4">
                            </th>
                            <th class="pb-6 pl-4">Product</th>
                            <th class="pb-6">Price</th>
                            <th class="pb-6">Quantity</th>
                            <th class="pb-6 text-right pr-4">Total</th>
                            <th class="pb-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-taupe/10">
                        @foreach($cartItems as $sku => $item)
                            <tr class="group">
                                <td class="py-6 pl-4">
                                     <input type="checkbox" wire:click="toggleSelection('{{ $sku }}')" @if(in_array($sku, $selectedItems)) checked @endif class="rounded border-taupe text-charcoal focus:ring-charcoal/30 bg-sand/50 h-4 w-4">
                                </td>
                                <td class="py-6 pl-4">
                                    <div class="flex items-center space-x-6">
                                        <div class="w-20 h-24 bg-sand/30 rounded-xl overflow-hidden shadow-sm">
                                            @php
                                                $imageUrl = $item['image_url'] ?? 'assets/images/prod_knit.png';
                                                $imageUrl = Str::startsWith($imageUrl, ['http', 'assets']) ? asset($imageUrl) : asset('storage/' . $imageUrl);
                                            @endphp
                                            <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="font-serif text-lg text-charcoal">{{ $item['name'] ?? 'Unknown Item' }}</h3>
                                            <p class="text-xs text-taupe font-bold uppercase tracking-widest mt-1">
                                                {{ $item['size_name'] ?? ($item['size'] ?? 'N/A') }} <span class="mx-1">/</span> {{ $item['color_name'] ?? ($item['color'] ?? 'N/A') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 font-medium text-charcoal">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </td>
                                <td class="py-6">
                                    <div class="inline-flex items-center bg-sand/50 rounded-full px-2 py-1 border border-taupe/20">
                                        <button wire:click="updateQuantity('{{ $sku }}', -1)" class="w-8 h-8 flex items-center justify-center text-charcoal/50 hover:text-charcoal transition-colors">
                                            -
                                        </button>
                                        <span class="w-8 text-center text-sm font-bold text-charcoal">{{ $item['quantity'] }}</span>
                                        <button wire:click="updateQuantity('{{ $sku }}', 1)" class="w-8 h-8 flex items-center justify-center text-charcoal/50 hover:text-charcoal transition-colors">
                                            +
                                        </button>
                                    </div>
                                </td>
                                <td class="py-6 text-right font-serif text-lg text-charcoal pr-4">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </td>
                                <td class="py-6 text-right">
                                    <button wire:click="removeItem('{{ $sku }}')" class="p-2 text-red-300 hover:text-red-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-12 flex flex-col md:flex-row justify-between items-center bg-sand/30 p-8 rounded-[2rem] border border-taupe/10">
                <div class="text-center md:text-left mb-6 md:mb-0">
                    <span class="block text-xs font-bold uppercase tracking-widest text-charcoal/50 mb-1">Total Amount ({{ count($selectedItems) }} items)</span>
                    <span class="font-serif text-4xl text-clay">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <button 
                    wire:click="checkout"
                    wire:loading.attr="disabled"
                    wire:target="checkout"
                    @if(count($selectedItems) == 0) disabled @endif
                    class="px-12 py-4 bg-charcoal text-white rounded-full font-bold uppercase tracking-widest hover:bg-black transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[160px]">
                    <span wire:loading.remove wire:target="checkout">Checkout</span>
                    <span wire:loading wire:target="checkout" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Wait...
                    </span>
                </button>
            </div>
        </div>
    @else
        <div class="text-center py-24 bg-white/40 rounded-[2.5rem] border border-taupe/10 border-dashed">
            <div class="w-20 h-20 bg-sand rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-charcoal/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h2 class="text-2xl font-serif text-charcoal mb-2">Your cart is empty</h2>
            <p class="text-taupe mb-8">It looks like you haven't added any items yet.</p>
            <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-sand text-charcoal font-bold uppercase tracking-widest text-xs rounded-full hover:bg-clay hover:text-white transition-all">
                Start Shopping
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-snap-modal', (event) => {
            window.snap.pay(event.token, {
                onSuccess: function(result){
                    // alert('Payment success!');
                    console.log(result);
                    // You can add a redirect or Livewire call here to save the order
                },
                onPending: function(result){
                    // alert('Waiting for your payment!');
                    console.log(result);
                },
                onError: function(result){
                    // alert('Payment failed!');
                    console.log(result);
                },
                onClose: function(){
                    // alert('You closed the popup without finishing the payment');
                }
            });
        });
    });
</script>
@endpush
