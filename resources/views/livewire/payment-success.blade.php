<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-50 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Success Icon Animation -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full shadow-2xl mb-6 animate-bounce-slow">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Payment Successful!</h1>
            <p class="text-gray-600 text-lg">Thank you for your purchase</p>
        </div>

        <!-- Order Details Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-8 py-6">
                <div class="flex justify-between items-start text-white">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Order Number</p>
                        <p class="text-2xl font-bold">{{ $order->order_code }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-90 mb-1">Invoice</p>
                        <p class="text-lg font-semibold">{{ $order->invoice_number }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="px-8 py-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Order Items
                </h3>
                
                <div class="space-y-4">
                    @foreach($orderItems as $item)
                    <div class="flex justify-between items-start py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors rounded-lg px-4">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $item->size_name }} • {{ $item->color_name }} • Qty: {{ $item->quantity }}
                            </p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="font-semibold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-1">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Total -->
                <div class="mt-6 pt-6 border-t-2 border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-800">Total Amount</span>
                        <span class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            @if($order->payment)
            <div class="px-8 py-4 bg-green-50 border-t border-green-100">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center text-green-700">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">Paid via {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}</span>
                    </div>
                    <span class="text-green-600 font-medium">{{ $order->payment->paid_at ? $order->payment->paid_at->format('d M Y, H:i') : 'Processing' }}</span>
                </div>
            </div>
            @endif

            <!-- Shipping Address -->
            @if($order->address)
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Shipping Address
                </h4>
                <p class="text-gray-700 leading-relaxed">
                    {{ $order->address->full_address ?? 'Address not available' }}
                </p>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button wire:click="downloadInvoice" 
                    class="group relative px-8 py-4 bg-white border-2 border-green-500 text-green-600 rounded-xl font-semibold hover:bg-green-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Invoice
            </button>
            
            <button wire:click="continueShopping" 
                    class="group relative px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-2xl hover:scale-105 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
                Continue Shopping
            </button>
        </div>

        <!-- Next Steps Info -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                What's Next?
            </h4>
            <ul class="space-y-2 text-blue-800 text-sm">
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    You will receive an order confirmation email shortly
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Your order will be processed and shipped within 1-2 business days
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Track your order status in your account dashboard
                </li>
            </ul>
        </div>
    </div>
    <style>
        @keyframes bounce-slow {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        .animate-bounce-slow {
            animation: bounce-slow 2s infinite;
        }
    </style>
</div>
