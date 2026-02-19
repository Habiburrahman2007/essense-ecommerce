<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-12 text-center md:text-left">
        <h1 class="text-4xl font-serif text-charcoal mb-4">My Account</h1>
        <p class="text-taupe font-medium">Manage your personal information and view your order history.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="md:col-span-2">
            <div class="bg-cream/50 backdrop-blur-md rounded-[2.5rem] border border-taupe/20 p-8 shadow-sm">
                <div class="flex flex-col md:flex-row items-center gap-6 mb-8">
                    <!-- Avatar Placeholder -->
                    <div class="w-24 h-24 rounded-full bg-sand flex items-center justify-center text-3xl font-serif text-charcoal border-2 border-white shadow-inner">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="text-center md:text-left">
                        <h2 class="text-2xl font-serif text-charcoal">{{ $user->name }}</h2>
                        <p class="text-charcoal/50 text-sm">Member since {{ $user->created_at->format('F Y') }}</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-charcoal/50 mb-1">Email Address</label>
                        <div class="text-lg text-charcoal font-medium bg-white/50 px-4 py-3 rounded-xl border border-taupe/10">
                            {{ $user->email }}
                        </div>
                    </div>
                    
                     <!-- Password Change Placeholder (Can be expanded later) -->
                    <div class="pt-4 border-t border-taupe/10 opacity-60">
                         <label class="block text-xs font-bold uppercase tracking-widest text-charcoal/50 mb-1">Password</label>
                         <p class="text-sm text-charcoal/70">••••••••</p>
                    </div>
                </div>
            </div>

            <!-- Address Management Section -->
            <div class="mt-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-serif text-charcoal">Shipping Addresses</h2>
                    <button wire:click="toggleAddAddress" class="px-6 py-2 bg-charcoal text-white rounded-full text-sm font-bold uppercase tracking-widest hover:bg-black transition-all">
                        {{ $isAddingAddress ? 'Cancel' : 'Add New Address' }}
                    </button>
                </div>

                <!-- Add Address Form -->
                @if($isAddingAddress)
                <div class="bg-white rounded-[2rem] p-8 border border-taupe/20 shadow-sm mb-8 animate-fade-in-down">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-charcoal/50">Receiver Name</label>
                            <input type="text" wire:model="receiver_name" class="w-full px-4 py-3 bg-sand/30 border border-taupe/20 rounded-xl focus:outline-none focus:border-charcoal transition-colors">
                            @error('receiver_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-charcoal/50">Phone Number</label>
                            <input type="text" wire:model="phone" class="w-full px-4 py-3 bg-sand/30 border border-taupe/20 rounded-xl focus:outline-none focus:border-charcoal transition-colors">
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-charcoal/50">Full Address</label>
                            <textarea wire:model="address_detail" rows="3" class="w-full px-4 py-3 bg-sand/30 border border-taupe/20 rounded-xl focus:outline-none focus:border-charcoal transition-colors"></textarea>
                            @error('address_detail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-charcoal/50">City</label>
                            <input type="text" wire:model="city" class="w-full px-4 py-3 bg-sand/30 border border-taupe/20 rounded-xl focus:outline-none focus:border-charcoal transition-colors">
                            @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-charcoal/50">Province</label>
                            <input type="text" wire:model="province" class="w-full px-4 py-3 bg-sand/30 border border-taupe/20 rounded-xl focus:outline-none focus:border-charcoal transition-colors">
                            @error('province') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-charcoal/50">Postal Code</label>
                            <input type="text" wire:model="postal_code" class="w-full px-4 py-3 bg-sand/30 border border-taupe/20 rounded-xl focus:outline-none focus:border-charcoal transition-colors">
                            @error('postal_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button wire:click="saveAddress" class="px-8 py-3 bg-clay text-white rounded-xl font-bold uppercase tracking-widest hover:bg-charcoal transition-all shadow-lg hover:shadow-xl text-xs">
                            Save Address
                        </button>
                    </div>
                </div>
                @endif

                <!-- Address List -->
                <div class="grid grid-cols-1 gap-6">
                    @forelse($addresses as $address)
                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-taupe/10 hover:border-taupe/30 transition-all group relative">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-serif text-lg text-charcoal mb-1">{{ $address->receiver_name }}</h3>
                                <p class="text-charcoal/60 text-sm mb-4">{{ $address->phone }}</p>
                                <p class="text-charcoal/80 leading-relaxed max-w-md">
                                    {{ $address->address }}<br>
                                    {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                                </p>
                            </div>
                            <button wire:click="deleteAddress({{ $address->id }})" class="p-2 text-red-300 hover:text-red-500 hover:bg-red-50 rounded-full transition-all" title="Delete Address">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 bg-white/40 rounded-2xl border border-dashed border-taupe/20">
                        <p class="text-taupe mb-4">No addresses saved yet.</p>
                        <button wire:click="toggleAddAddress" class="text-clay font-bold uppercase tracking-widest text-xs hover:text-charcoal transition-colors">
                            + Add your first address
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Stats / Actions -->
        <div class="space-y-6">
             <div class="bg-white/60 rounded-[2rem] p-6 border border-white/60 shadow-sm text-center">
                <span class="block text-4xl font-serif text-clay mb-1">0</span>
                <span class="text-[10px] text-charcoal/50 font-bold uppercase tracking-widest">Active Orders</span>
             </div>
             
             <div class="bg-white/60 rounded-[2rem] p-6 border border-white/60 shadow-sm text-center">
                 <span class="block text-4xl font-serif text-clay mb-1">0</span>
                 <span class="text-[10px] text-charcoal/50 font-bold uppercase tracking-widest">Wishlist Items</span>
             </div>
             
             <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-sand hover:bg-red-50 text-charcoal/60 hover:text-red-400 py-4 rounded-[1.5rem] font-bold uppercase tracking-widest transition-all text-xs">
                    Sign Out
                </button>
             </form>
        </div>
    </div>
</div>
