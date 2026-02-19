<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        Auth::login($user);

        return redirect()->intended('/admin');
    }
}; ?>

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
        <div>
            <label for="password" class="block text-[10px] font-bold uppercase tracking-widest text-charcoal/40 mb-2 ml-4">Password</label>
            <input wire:model="password" id="password" type="password" name="password" required 
                class="w-full bg-sand/50 border border-taupe px-6 py-4 rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-clay focus:border-clay transition-all placeholder:text-taupe"
                placeholder="••••••••">
            @error('password') <span class="text-red-400 text-[10px] mt-2 block ml-4">{{ $message }}</span> @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-widest text-charcoal/40 mb-2 ml-4">Confirm Password</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required 
                class="w-full bg-sand/50 border border-taupe px-6 py-4 rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-clay focus:border-clay transition-all placeholder:text-taupe"
                placeholder="••••••••">
            @error('password_confirmation') <span class="text-red-400 text-[10px] mt-2 block ml-4">{{ $message }}</span> @enderror
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