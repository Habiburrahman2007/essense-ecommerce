<div x-data="{ loading: false }">
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-serif text-charcoal mb-3">Welcome Back</h1>
        <p class="text-charcoal/50 text-xs tracking-tight">Enter your details to access your account</p>
    </div>

    <form wire:submit="login" @submit="loading = true" class="space-y-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[10px] font-bold uppercase tracking-widest text-charcoal/40 mb-2 ml-4">Email Address</label>
            <input wire:model="email" id="email" type="email" name="email" required autofocus 
                class="w-full bg-sand/50 border border-taupe px-6 py-4 rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-clay focus:border-clay transition-all placeholder:text-taupe"
                placeholder="you@example.com">
            @error('email') <span class="text-red-400 text-[10px] mt-2 block ml-4">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <div class="flex justify-between items-center mb-2 ml-4">
                <label for="password" class="block text-[10px] font-bold uppercase tracking-widest text-charcoal/40">Password</label>
                <a href="#" class="text-[10px] tracking-widest uppercase text-clay hover:text-charcoal transition-colors">Forgot?</a>
            </div>
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

        <!-- Remember Me -->
        <div class="flex items-center ml-4">
            <label for="remember" class="inline-flex items-center cursor-pointer group">
                <input wire:model="remember" id="remember" type="checkbox" class="hidden peer">
                <div class="w-4 h-4 rounded border border-taupe bg-sand/50 flex items-center justify-center peer-checked:bg-clay peer-checked:border-clay transition-all group-hover:border-clay">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="ml-2 text-[10px] font-medium uppercase tracking-widest text-charcoal/50 group-hover:text-charcoal transition-colors">Remember me</span>
            </label>
        </div>

        <div>
            <button type="submit" 
                class="w-full bg-charcoal text-white py-5 rounded-full text-sm font-bold tracking-widest uppercase shadow-xl hover:bg-clay hover:shadow-2xl transition-all flex items-center justify-center space-x-2 disabled:opacity-50"
                :disabled="loading">
                <span x-show="!loading">Sign In</span>
                <span x-show="loading" x-cloak class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Signing in...
                </span>
            </button>
        </div>
    </form>

    <div class="relative flex py-6 items-center">
        <div class="flex-grow border-t border-taupe/20"></div>
        <span class="flex-shrink-0 mx-4 text-[10px] text-charcoal/40 uppercase tracking-widest">Or continue with</span>
        <div class="flex-grow border-t border-taupe/20"></div>
    </div>

    <div>
        <a href="{{ route('google.redirect') }}" 
            class="w-full bg-white text-charcoal border border-taupe/30 py-4 rounded-full text-sm font-bold tracking-widest uppercase hover:bg-sand/30 hover:border-clay hover:text-clay transition-all flex items-center justify-center space-x-3 shadow-sm hover:shadow-md">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
            </svg>
            <span>Google</span>
        </a>
    </div>

    <div class="mt-8 text-center">
        <p class="text-[10px] tracking-widest uppercase text-charcoal/40">
            New to Essence? <a href="/register" class="text-clay font-bold hover:text-charcoal transition-colors ml-1">Create an account</a>
        </p>
    </div>
</div>
