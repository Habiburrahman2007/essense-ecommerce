<div class="space-y-4 md:space-y-8">
    <!-- Header Area: Breadcrumbs & Cart -->
    <div class="flex justify-between items-center text-sm font-medium text-charcoal/60">
        <!-- Breadcrumbs -->
        <nav class="flex">
            @foreach($breadcrumbs as $crumb)
                @if(!$loop->first)
                    <span class="mx-2 text-taupe/60">/</span>
                @endif
                
                @if($crumb['action'])
                    <button wire:click="{{ $crumb['action'] }}" class="hover:text-gold transition-colors">
                        {{ $crumb['name'] }}
                    </button>
                @else
                    <span class="text-charcoal cursor-default">{{ $crumb['name'] }}</span>
                @endif
            @endforeach
        </nav>

        <!-- Cart Button -->
        <a href="{{ route('cart') }}" class="group relative">
            <div class="bg-white p-3 rounded-full shadow-sm border border-taupe/10 group-hover:bg-sand transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-charcoal group-hover:text-gold transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            
            <!-- Badge -->
            @if(session('cart') && count(session('cart')) > 0)
                <span class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 bg-clay text-white text-[10px] font-bold rounded-full border-2 border-sand">
                    {{ count(session('cart')) }}
                </span>
            @endif
        </a>
    </div>

    <!-- Top Filter & Search Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 md:gap-8 bg-cream/50 backdrop-blur-md p-4 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] border border-taupe/20 shadow-sm">
        <!-- Search Input -->
        <div class="relative w-full md:w-96 group">
            <span class="absolute inset-y-0 left-4 md:left-6 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 text-charcoal/30 group-focus-within:text-clay transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input wire:model.live.debounce.300ms="search" type="text" 
                class="w-full bg-sand/50 border border-taupe px-10 py-3 md:px-14 md:py-4 rounded-full text-xs md:text-sm focus:outline-none focus:ring-1 focus:ring-clay focus:border-clay transition-all placeholder:text-taupe"
                placeholder="Search products...">
        </div>

        <!-- Category Chips -->
        <div class="flex items-center space-x-2 md:space-x-3 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto scrollbar-hide">
            @foreach($categories as $category)
                <button 
                    wire:click="setCategory('{{ $category }}')"
                    class="px-4 py-2 md:px-6 rounded-full text-[9px] md:text-[10px] font-bold uppercase tracking-widest transition-all whitespace-nowrap
                    {{ $activeCategory === $category 
                        ? 'bg-charcoal text-white shadow-lg' 
                        : 'bg-white text-charcoal/40 hover:text-charcoal border border-taupe/30' }}"
                >
                    {{ $category }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 pb-20"> <!-- grid-cols-2 by default on mobile for better density -->
        @forelse($products as $product)
            <div class="group bg-white/60 rounded-[1.5rem] md:rounded-[2rem] overflow-hidden border border-white/80 shadow-sm hover:shadow-xl transition-all duration-500 hover:-translate-y-2">
                <!-- Image Wrapper -->
                <div class="relative aspect-[4/5] overflow-hidden bg-sand/30">
                    @php
                        $image = $product->primaryImage ?? $product->images->first();
                        $imageUrl = $image && Storage::disk('public')->exists($image->image_url) 
                            ? asset('storage/' . $image->image_url) 
                            : asset('assets/images/prod_knit.png');
                    @endphp
                    <a href="{{ route('product.detail', $product->slug) }}" class="block w-full h-full">
                        <img src="{{ $imageUrl }}" 
                            alt="{{ $product->name }}" 
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    </a>
                    
                    <!-- Overlay with stock count -->
                    <div class="absolute bottom-3 left-3 md:bottom-4 md:left-4">
                        <span class="px-2 py-1 md:px-4 md:py-1.5 bg-white/90 backdrop-blur-md rounded-full text-[8px] md:text-[9px] font-bold uppercase tracking-widest text-charcoal shadow-sm">
                            Stock: {{ $product->stock }}
                        </span>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="p-4 md:p-6">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[8px] md:text-[10px] text-clay font-bold uppercase tracking-widest">{{ $product->category->name ?? 'Uncat' }}</span>
                    </div>
                    <a href="{{ route('product.detail', $product->slug) }}">
                        <h4 class="text-sm md:text-lg font-serif text-charcoal mb-2 md:mb-4 line-clamp-1 group-hover:text-clay transition-colors">{{ $product->name }}</h4>
                    </a>
                    <div class="flex justify-between items-center pt-3 md:pt-4 border-t border-taupe/10">
                        <span class="text-base md:text-xl font-serif text-charcoal">Rp {{ number_format($product->base_price, 2) }}</span>
                        
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-20 h-20 bg-taupe/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-charcoal/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-serif text-charcoal/60">No pieces found</h3>
                <p class="text-charcoal/40 text-sm mt-2">Adjust your filters or try another search term.</p>
            </div>
        @endforelse
    </div>
</div>
