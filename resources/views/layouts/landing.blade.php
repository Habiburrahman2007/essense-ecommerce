<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Essence - Minimalist Basic Fashion</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Lora:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
        .hero-gradient { background: linear-gradient(to right, rgba(253, 252, 240, 0.9), rgba(253, 252, 240, 0)); }
        html { scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }

        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(calc(-50% - 1rem)); } 
        }
        .animate-scroll {
            animation: scroll 40s linear infinite;
            display: flex;
            width: max-content;
        }
        .pause-scroll:hover {
            animation-play-state: paused;
        }
    </style>
    @livewireStyles
</head>
<body class="antialiased selection:bg-taupe selection:text-charcoal" x-data="{ mobileMenuOpen: false }">

    <!-- Header / Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-cream/80 backdrop-blur-md border-b border-taupe px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="/" class="text-2xl font-serif tracking-tight text-charcoal flex-shrink-0">Essence</a>
            
            <!-- Desktop Menu (Right Aligned) -->
            <div class="hidden md:flex flex-grow justify-end items-center">
                <div class="flex space-x-12 text-sm font-medium tracking-wide uppercase mr-12">
                    <a href="#about" class="hover:text-clay transition-colors">Philosophy</a>
                    <a href="#categories" class="hover:text-clay transition-colors">Collections</a>
                    <a href="#products" class="hover:text-clay transition-colors">Essentials</a>
                </div>
                <div class="flex items-center space-x-6 text-sm font-medium border-l border-taupe pl-12">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-5 py-2 border border-charcoal rounded-full hover:bg-charcoal hover:text-white transition-all">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-clay">Sign In</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2 bg-charcoal text-white rounded-full hover:bg-clay transition-all">Join Us</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>

            <!-- Mobile Menu Button (Hamburger) -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-charcoal focus:outline-none p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden fixed inset-x-0 top-16 bg-cream/80 backdrop-blur-xl border-b border-taupe shadow-2xl z-40 overflow-hidden">
            <div class="px-6 py-12 flex flex-col space-y-8 text-center">
                <a href="#about" @click="mobileMenuOpen = false" class="text-xl font-serif text-charcoal hover:text-clay transition-colors">Philosophy</a>
                <a href="#categories" @click="mobileMenuOpen = false" class="text-xl font-serif text-charcoal hover:text-clay transition-colors">Collections</a>
                <a href="#products" @click="mobileMenuOpen = false" class="text-xl font-serif text-charcoal hover:text-clay transition-colors">Essentials</a>
                <div class="w-12 h-[1px] bg-taupe mx-auto my-4"></div>
                <div class="flex flex-col space-y-4 pt-4">
                    <a href="{{ route('login') }}" class="text-charcoal font-medium">Sign In</a>
                    <a href="{{ route('register') }}" class="mx-auto px-8 py-3 bg-charcoal text-white rounded-full text-sm font-medium w-fit">Join Us</a>
                </div>
            </div>
        </div>
    </nav>

    {{ $slot }}

    @livewireScripts
</body>
</html>
