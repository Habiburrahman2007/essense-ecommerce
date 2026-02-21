<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} - Essence</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Lora:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN -->
    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js (Removed: Livewire injects this automatically) -->
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: '#FDFCF0',
                        sand: '#F5F2EA',
                        taupe: '#D9D2C5',
                        charcoal: '#4A4A4A',
                        clay: '#B89F8F',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Lora', 'serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FDFCF0; color: #4A4A4A; }
        .font-serif { font-family: 'Lora', serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased selection:bg-taupe selection:text-charcoal bg-sand">

    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'w-72' : 'w-20'"
            class="bg-cream border-r border-taupe/30 flex flex-col transition-all duration-500 ease-in-out relative z-30">
            
            <!-- Sidebar Header / Logo -->
            <div class="h-24 flex items-center justify-center border-b border-taupe/20">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden transition-all duration-300">
                     <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                    <span x-show="sidebarOpen" x-transition class="text-2xl font-serif tracking-tight text-charcoal whitespace-nowrap">Essence</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-grow py-8 px-4 space-y-2 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 px-4 py-3 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-sand text-charcoal' : 'text-charcoal/50 hover:bg-sand hover:text-charcoal' }} transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-clay' : 'text-charcoal/50 group-hover:text-clay' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Homepage</span>
                </a>
                <a href="{{ route('my-orders') }}" class="flex items-center space-x-4 px-4 py-3 rounded-2xl {{ request()->routeIs('my-orders') ? 'bg-sand text-charcoal' : 'text-charcoal/50 hover:bg-sand hover:text-charcoal' }} transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs('my-orders') ? 'text-clay' : 'text-charcoal/50 group-hover:text-clay' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Orders</span>
                </a>
                <a href="{{ route('my-account') }}" class="flex items-center space-x-4 px-4 py-3 rounded-2xl {{ request()->routeIs('my-account') ? 'bg-sand text-charcoal' : 'text-charcoal/50 hover:bg-sand hover:text-charcoal' }} transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:text-clay {{ request()->routeIs('my-account') ? 'text-clay' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Account</span>
                </a>
            </nav>

            <!-- Sidebar Footer / Logout -->
            <div class="p-4 border-t border-taupe/20">
                <!-- Logout trigger button - dispatch event ke modal -->
                <button
                    type="button"
                    @click="$dispatch('open-logout-modal')"
                    class="w-full flex items-center space-x-4 px-4 py-3 rounded-2xl text-red-400 hover:bg-red-50 transition-all overflow-hidden"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Sign Out</span>
                </button>

                <!-- Hidden form untuk POST logout -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
            
            <!-- Toggle Button -->
            <button 
                @click="sidebarOpen = !sidebarOpen"
                class="absolute -right-3 top-24 w-6 h-6 bg-cream border border-taupe shadow-sm rounded-full flex items-center justify-center text-charcoal hover:bg-sand transition-all z-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform duration-300" :class="sidebarOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </aside>

        <!-- Main Content Carrier -->
        <main class="flex-grow flex flex-col min-w-0 bg-sand/50 overflow-hidden">

            <!-- Scrollable Area -->
            <div class="flex-grow overflow-y-auto p-4 md:p-8 relative">
                <!-- Mobile Blur Overlay -->
                <div 
                    x-show="sidebarOpen"
                    x-transition.opacity
                    class="absolute inset-0 bg-sand/30 backdrop-blur-sm z-20 md:hidden pointer-events-none"
                ></div>

                <!-- Decorative background elements -->
                <div class="absolute top-20 right-20 w-64 h-64 bg-cream rounded-full blur-[100px] opacity-60 z-0 pointer-events-none"></div>
                <div class="absolute bottom-20 left-20 w-64 h-64 bg-taupe/10 rounded-full blur-[100px] opacity-60 z-0 pointer-events-none"></div>

                <div class="relative z-10 max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    <!-- Toast Notification -->
    <div x-data="{ show: false, message: '' }" 
         x-on:notify.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-4 right-4 bg-charcoal text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center border border-taupe/20"
         style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-clay" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span x-text="message" class="font-medium"></span>
    </div>

    @stack('scripts')

    <!-- Logout Confirmation Modal -->
    <!-- x-data sendiri agar bisa berada di luar div scope utama -->
    <div
        x-data="{ open: false }"
        x-on:open-logout-modal.window="open = true"
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
        @keydown.escape.window="open = false"
    >
        <!-- Backdrop -->
        <div
            class="absolute inset-0 bg-charcoal/40 backdrop-blur-sm"
            @click="open = false"
        ></div>

        <!-- Modal Card -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="relative bg-cream rounded-3xl shadow-2xl border border-taupe/30 w-full max-w-sm p-8 flex flex-col items-center text-center"
        >
            <!-- Icon -->
            <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>

            <!-- Title -->
            <h2 class="text-xl font-serif text-charcoal mb-2">Sign Out?</h2>

            <!-- Message -->
            <p class="text-sm text-charcoal/60 leading-relaxed mb-8">
                Apakah kamu yakin ingin keluar dari akun ini?
            </p>

            <!-- Buttons -->
            <div class="flex gap-3 w-full">
                <button
                    type="button"
                    @click="open = false"
                    class="flex-1 px-5 py-2.5 rounded-full border border-taupe text-charcoal text-sm font-medium hover:bg-sand transition-all"
                >
                    Batal
                </button>
                <button
                    type="button"
                    @click="document.getElementById('logout-form').submit()"
                    class="flex-1 px-5 py-2.5 rounded-full bg-red-400 text-white text-sm font-medium hover:bg-red-500 transition-all"
                >
                    Ya, Sign Out
                </button>
            </div>
        </div>
    </div>
</body>
</html>
