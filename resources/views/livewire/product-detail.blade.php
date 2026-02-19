<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6 md:mb-8 text-sm font-medium text-charcoal/60">
        <a href="{{ route('dashboard') }}" class="hover:text-gold transition-colors">Home</a>
        <span class="mx-2 text-taupe/60">/</span>
        <a href="{{ route('dashboard', ['activeCategory' => $product->category->name]) }}" class="hover:text-gold transition-colors">{{ $product->category->name }}</a>
        <span class="mx-2 text-taupe/60">/</span>
        <span class="text-charcoal cursor-default">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">
        <!-- Gallery Section -->
        <div class="space-y-4 md:space-y-6" x-data="{ activeImage: '{{ $product->primaryImage && Storage::disk('public')->exists($product->primaryImage->image_url) ? asset('storage/' . $product->primaryImage->image_url) : ($product->images->first() && Storage::disk('public')->exists($product->images->first()->image_url) ? asset('storage/' . $product->images->first()->image_url) : asset('assets/images/prod_knit.png')) }}' }">
            <!-- Main Image -->
            <div class="aspect-[4/5] bg-sand/30 rounded-[1.5rem] md:rounded-[2.5rem] overflow-hidden shadow-sm border border-taupe/20 relative group">
                 <img :src="activeImage" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            </div>

            <!-- Thumbnail Strip -->
            @if($product->images->count() > 1)
            <div class="flex space-x-3 md:space-x-4 overflow-x-auto pb-2 scrollbar-hide lg:hidden">
                @foreach($product->images as $image)
                    @if(Storage::disk('public')->exists($image->image_url))
                    <button 
                        @click="activeImage = '{{ asset('storage/' . $image->image_url) }}'"
                        class="flex-shrink-0 w-16 h-16 md:w-20 md:h-20 rounded-xl md:rounded-2xl overflow-hidden border-2 transition-all"
                        :class="activeImage === '{{ asset('storage/' . $image->image_url) }}' ? 'border-charcoal' : 'border-transparent opacity-70 hover:opacity-100'"
                    >
                        <img src="{{ asset('storage/' . $image->image_url) }}" class="w-full h-full object-cover">
                    </button>
                    @endif
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info Section -->
        <div class="flex flex-col justify-center space-y-6 md:space-y-8">
            <!-- Desktop Thumbnails (Moved here) -->
            @if($product->images->count() > 1)
            <div class="hidden lg:grid grid-cols-5 gap-3 mb-2">
                 @foreach($product->images as $image)
                    @if(Storage::disk('public')->exists($image->image_url))
                    <button 
                        @click="activeImage = '{{ asset('storage/' . $image->image_url) }}'"
                        class="aspect-square rounded-xl overflow-hidden border-2 transition-all cursor-pointer"
                        :class="activeImage === '{{ asset('storage/' . $image->image_url) }}' ? 'border-charcoal' : 'border-transparent opacity-70 hover:opacity-100'"
                    >
                        <img src="{{ asset('storage/' . $image->image_url) }}" class="w-full h-full object-cover">
                    </button>
                    @endif
                @endforeach
            </div>
            @endif
            <div>
                <h1 class="text-2xl md:text-5xl font-serif text-charcoal mb-2 md:mb-4">{{ $product->name }}</h1>
                <p class="text-lg md:text-xl text-taupe font-medium">{{ $product->category->name }}</p>
            </div>

            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-clay">
                    Rp {{ number_format($currentPrice, 0, ',', '.') }}
                </h2>
                <div class="text-sm text-charcoal/60 mt-2 flex items-center gap-2">
                    Availability: 
                    @if($currentStock > 0)
                        <span class="text-green-600 font-bold">In Stock ({{ $currentStock }})</span>
                    @else
                        <span class="text-red-500 font-bold">Out of Stock</span>
                    @endif
                </div>
            </div>

            <div class="prose prose-stone text-charcoal/80">
                <p>{!! $product->description !!}</p>
            </div>

            <!-- Selectors -->
            <div class="space-y-6 pt-6 border-t border-taupe/20">
                <!-- Size Selector -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-charcoal/50 mb-3">Size</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($availableSizes as $id => $name)
                            <button 
                                wire:click="toggleSize({{ $id }})"
                                class="min-w-[3rem] px-4 py-2 rounded-xl text-sm font-bold border transition-all
                                {{ $selectedSize == $id 
                                    ? 'bg-charcoal text-white border-charcoal shadow-md' 
                                    : 'bg-white text-charcoal border-taupe/30 hover:border-charcoal/50' }}"
                            >
                                {{ $name }}
                            </button>
                        @endforeach
                    </div>
                    @error('selectedSize') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Color Selector -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-charcoal/50 mb-3">Color</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($availableColors as $id => $name)
                             <button 
                                wire:click="toggleColor({{ $id }})"
                                class="px-4 py-2 rounded-xl text-sm font-bold border transition-all
                                {{ $selectedColor == $id 
                                    ? 'bg-charcoal text-white border-charcoal shadow-md' 
                                    : 'bg-white text-charcoal border-taupe/30 hover:border-charcoal/50' }}"
                            >
                                {{ $name }}
                            </button>
                        @endforeach
                    </div>
                    @error('selectedColor') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <!-- Actions -->
            <div class="pt-8">
                <div class="flex gap-4">
                    <button 
                        wire:click="addToCart"
                        wire:loading.attr="disabled"
                        @if($currentStock == 0) disabled @endif
                        class="flex-1 bg-charcoal text-white py-4 rounded-full font-bold uppercase tracking-widest hover:bg-black transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="addToCart">
                            {{ $currentStock > 0 ? ($feedbackMessage ?: 'Add to Cart') : 'Sold Out' }}
                        </span>
                        <span wire:loading wire:target="addToCart">Adding...</span>
                    </button>
                    <button 
                        wire:click="buyNow"
                        @if($currentStock == 0) disabled @endif
                        class="flex-1 bg-clay text-white py-4 rounded-full font-bold uppercase tracking-widest hover:bg-taupe transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                        Buy Now
                    </button>
                </div>

                @if($feedbackMessage)
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="absolute top-0 right-0 p-4 bg-green-500 text-white rounded-lg shadow-lg">
                        Item added to cart!
                    </div>
                @endif
                
                @if($errors->has('selectedSize') || $errors->has('selectedColor'))
                    <p class="text-xs text-red-500 text-center mt-3 font-bold animate-pulse">
                        Please select both a size and a color to proceed.
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-20 border-t border-taupe/10 pt-12">
            <h3 class="text-2xl md:text-3xl font-serif text-charcoal mb-8 text-center">You May Also Like</h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
                @foreach($relatedProducts as $related)
                    <div class="group bg-white/60 rounded-[1.5rem] md:rounded-[2rem] overflow-hidden border border-white/80 shadow-sm hover:shadow-xl transition-all duration-500 hover:-translate-y-2">
                        <!-- Image Wrapper -->
                        <div class="relative aspect-[4/5] overflow-hidden bg-sand/30">
                            @php
                                $rImage = $related->primaryImage ?? $related->images->first();
                                $rImageUrl = $rImage && Storage::disk('public')->exists($rImage->image_url) 
                                    ? asset('storage/' . $rImage->image_url) 
                                    : asset('assets/images/prod_knit.png');
                            @endphp
                            <a href="{{ route('product.detail', $related->slug) }}" class="block w-full h-full">
                                <img src="{{ $rImageUrl }}" 
                                    alt="{{ $related->name }}" 
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            </a>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4 md:p-6">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[8px] md:text-[10px] text-clay font-bold uppercase tracking-widest">{{ $related->category->name }}</span>
                            </div>
                            <a href="{{ route('product.detail', $related->slug) }}">
                                <h4 class="text-sm md:text-lg font-serif text-charcoal mb-2 line-clamp-1 group-hover:text-clay transition-colors">{{ $related->name }}</h4>
                            </a>
                            <div class="flex justify-between items-center pt-3 border-t border-taupe/10">
                                <span class="text-sm md:text-base font-serif text-charcoal">Rp {{ number_format($related->base_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
