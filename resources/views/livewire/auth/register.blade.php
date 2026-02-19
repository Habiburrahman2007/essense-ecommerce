<div x-data="{ loading: false }">
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-serif text-charcoal mb-3">Join Us</h1>
        <p class="text-charcoal/50 text-xs tracking-tight">Create your account to start your journey</p>
    </div>

    <form wire:submit="register" @submit="loading = true" class="space-y-5">
        <!-- Name -->
        <div>
            <label for="name" class="block text-[10px] font-bold uppercase tracking-widest text-charcoal/40 mb-2 ml-4">Full Name</label>
            <input wire:model="name" id="name" type="text" name="name" required autofocus 
                class="w-full bg-sand/50 border border-taupe px-6 py-4 rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-clay focus:border-clay transition-all placeholder:text-taupe"
                placeholder="John Doe">
            @error('name') <span class="text-red-400 text-[10px] mt-2 block ml-4">{{ $message }}</span> @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[10px] font-bold uppercase tracking-widest text-charcoal/40 mb-2 ml-4">Email Address</label>
            <input wire:model="email" id="email" type="email" name="email" required 
                class="w-full bg-sand/50 border border-taupe px-6 py-4 rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-clay focus:border-clay transition-all placeholder:text-taupe"
                placeholder="you@example.com">
            @error('email') <span class="text-red-400 text-[10px] mt-2 block ml-4">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block text-[10px] font-bold uppercase tracking-widest text-charcoal/40 mb-2 ml-4">Password</label>
            <div class="relative">
                <input wire:model="password" id="password" :type="show ? 'text' : 'password'" name="password" required 
                    class="w-full bg-sand/50 border border-taupe px-6 py-4 rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-clay focus:border-clay transition-all placeholder:text-taupe pr-12"
                    placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-charcoal/40 hover:text-charcoal transition-colors focus:outline-none">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            @error('password') <span class="text-red-400 text-[10px] mt-2 block ml-4">{{ $message }}</span> @enderror
        </div>

        <div class="pt-4">
            <button type="submit" 
                class="w-full bg-charcoal text-white py-5 rounded-full text-sm font-bold tracking-widest uppercase shadow-xl hover:bg-clay hover:shadow-2xl transition-all flex items-center justify-center space-x-2 disabled:opacity-50"
                :disabled="loading">
                <span x-show="!loading">Create Account</span>
                <span x-show="loading" x-cloak class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creating account...
                </span>
            </button>
        </div>
    </form>

    <div class="mt-8 text-center">
        <p class="text-[10px] tracking-widest uppercase text-charcoal/40">
            Already have an account? <a href="/login" class="text-clay font-bold hover:text-charcoal transition-colors ml-1">Sign in instead</a>
        </p>
    </div>
</div>
