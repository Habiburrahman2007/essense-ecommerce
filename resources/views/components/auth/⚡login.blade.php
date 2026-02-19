<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        session()->regenerate();

        return redirect()->intended('/admin');
    }
}; ?>

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
        <div>
            <div class="flex justify-between items-center mb-2 ml-4">
                <label for="password" class="block text-[10px] font-bold uppercase tracking-widest text-charcoal/40">Password</label>
                <a href="#" class="text-[10px] tracking-widest uppercase text-clay hover:text-charcoal transition-colors">Forgot?</a>
            </div>
            <input wire:model="password" id="password" type="password" name="password" required 
                class="w-full bg-sand/50 border border-taupe px-6 py-4 rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-clay focus:border-clay transition-all placeholder:text-taupe"
                placeholder="••••••••">
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

    <div class="mt-8 text-center">
        <p class="text-[10px] tracking-widest uppercase text-charcoal/40">
            New to Essence? <a href="/register" class="text-clay font-bold hover:text-charcoal transition-colors ml-1">Create an account</a>
        </p>
    </div>
</div>