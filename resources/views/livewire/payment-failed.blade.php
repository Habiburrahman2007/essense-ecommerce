<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-orange-50 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Failed Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-red-400 to-red-600 rounded-full shadow-2xl mb-6">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Payment Failed</h1>
            <p class="text-gray-600 text-lg">{{ $errorMessage }}</p>
        </div>

        <!-- Order Details Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-500 to-orange-600 px-8 py-6">
                <div class="flex justify-between items-start text-white">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Order Number</p>
                        <p class="text-2xl font-bold">{{ $order->order_code }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-90 mb-1">Status</p>
                        <span class="inline-block px-4 py-1 bg-white/20 rounded-full text-sm font-semibold">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Failed Reason -->
            @if($order->payment)
            <div class="px-8 py-4 bg-red-50 border-b border-red-100">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-red-900 mb-1">Payment Issue</h4>
                        <p class="text-red-700 text-sm">{{ $errorMessage }}</p>
                        <p class="text-xs text-red-600 mt-1">Status: {{ ucfirst($order->payment->status) }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Items -->
            <div class="px-8 py-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Order Items (Not Processed)
                </h3>
                
                <div class="space-y-4">
                    @foreach($orderItems as $item)
                    <div class="flex justify-between items-start py-3 border-b border-gray-100 last:border-0 opacity-75">
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
                        <span class="text-3xl font-bold text-gray-600">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
            <button wire:click="retryPayment" 
                    class="group relative px-8 py-4 bg-gradient-to-r from-red-500 to-orange-600 text-white rounded-xl font-semibold hover:from-red-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-2xl hover:scale-105 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Retry Payment
            </button>
            
            <button wire:click="backToCart" 
                    class="group relative px-8 py-4 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Back to Cart
            </button>
        </div>

        <!-- Help Section -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
            <h4 class="font-semibold text-yellow-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                </svg>
                Common Reasons for Payment Failure
            </h4>
            <ul class="space-y-2 text-yellow-800 text-sm">
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    Insufficient balance in your account
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    Card expired or incorrect card details
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    Payment cancelled by user
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    Bank or payment provider declined the transaction
                </li>
            </ul>
            <div class="mt-4 pt-4 border-t border-yellow-300">
                <p class="text-sm text-yellow-900">
                    <strong>Need help?</strong> Contact our support team at 
                    <a href="mailto:support@example.com" class="underline hover:text-yellow-700">support@example.com</a>
                </p>
            </div>
        </div>
    </div>
</div>
