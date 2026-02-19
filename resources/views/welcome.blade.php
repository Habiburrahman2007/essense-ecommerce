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

    <!-- 1. Jumbotron (Hero) -->
    <section class="min-h-screen flex items-center relative pt-20">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/images/hero.jpeg') }}" alt="Minimalist Fashion Model" class="w-full h-full object-cover">
            <div class="absolute inset-0 hero-gradient"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-6 w-full">
            <div class="max-w-2xl">
                <span class="inline-block text-clay tracking-[0.3em] uppercase text-xs font-semibold mb-6">Sustainable Basics</span>
                <h1 class="text-6xl md:text-8xl font-serif leading-tight text-charcoal mb-8">Quiet Luxury <br> <span class="italic text-clay">Redefined.</span></h1>
                <p class="text-lg text-charcoal/70 mb-10 leading-relaxed font-light">Timeless silhouettes tailored for the modern minimalist. Discover the beauty of fundamental pieces crafted with organic materials and ethical care.</p>
                <div class="flex space-x-4">
                    <a href="#products" class="px-8 py-4 bg-charcoal text-white rounded-full text-sm font-medium hover:bg-clay transition-all duration-300">Shop Essentials</a>
                    <a href="#about" class="px-8 py-4 border border-charcoal rounded-full text-sm font-medium hover:bg-charcoal hover:text-white transition-all duration-300">Our Story</a>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. About -->
    <section id="about" class="py-32 md:py-48 px-6 bg-cream">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-5xl font-serif text-charcoal mb-12 leading-relaxed">"Style is a way to say who you are without having to speak. Less is always more."</h2>
            <div class="w-24 h-[1px] bg-taupe mx-auto mb-12"></div>
            <p class="text-charcoal/60 leading-loose mx-auto max-w-2xl">
                Essence was built on the belief that a wardrobe should be a source of calm, not clutter. We curate only the most fundamental staples—designed to last a lifetime, not just a season.
            </p>
        </div>
    </section>

    <!-- 3. Categories -->
    <section id="categories" class="py-24 px-6 bg-sand">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16">
                <div>
                    <span class="text-clay tracking-widest uppercase text-xs font-semibold mb-4 block">The Collections</span>
                    <h2 class="text-4xl font-serif text-charcoal">Core Categories</h2>
                </div>
                <p class="text-charcoal/50 max-w-md text-right text-sm leading-relaxed hidden md:block">Fundamental layers carefully designed to integrate seamlessly into your daily life.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Shirts -->
                <div class="group cursor-pointer overflow-hidden relative aspect-[4/5] bg-cream rounded-2xl shadow-sm">
                    <img src="{{ asset('assets/images/shirt.jpeg') }}" alt="Organic Shirts" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-8 z-10">
                        <h3 class="text-2xl font-serif mb-2 text-white">Shirts</h3>
                        <p class="text-sm tracking-widest uppercase text-white/80 opacity-0 group-hover:opacity-100 transition-all duration-500">Discover</p>
                    </div>
                </div>
                <!-- Outers -->
                <div class="group cursor-pointer overflow-hidden relative aspect-[4/5] bg-cream rounded-2xl shadow-sm">
                    <img src="{{ asset('assets/images/outer.jpeg') }}" alt="Structured Outers" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-8 z-10">
                        <h3 class="text-2xl font-serif mb-2 text-white">Outers</h3>
                        <p class="text-sm tracking-widest uppercase text-white/80 opacity-0 group-hover:opacity-100 transition-all duration-500">Discover</p>
                    </div>
                </div>
                <!-- Pants -->
                <div class="group cursor-pointer overflow-hidden relative aspect-[4/5] bg-cream rounded-2xl shadow-sm">
                    <img src="{{ asset('assets/images/pants.jpeg') }}" alt="Tailored Pants" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-8 z-10">
                        <h3 class="text-2xl font-serif mb-2 text-white">Pants</h3>
                        <p class="text-sm tracking-widest uppercase text-white/80 opacity-0 group-hover:opacity-100 transition-all duration-500">Discover</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Popular Products (Scrolling Carousel) -->
    <section id="products" class="py-32 bg-cream overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 mb-20 text-center">
            <h2 class="text-4xl font-serif text-charcoal mb-4">Daily Essentials</h2>
            <p class="text-charcoal/50 text-sm">The foundation of every minimalist wardrobe.</p>
        </div>

        <!-- Scrolling Container -->
        <div class="relative overflow-hidden w-full h-full">
            <div class="flex gap-8 animate-scroll hover:pause-scroll w-max px-4">
                <!-- Original Items -->
                <div class="flex gap-8">
                    <!-- Product 1 -->
                    <div class="w-72 flex-shrink-0">
                        <div class="aspect-square bg-sand rounded-xl overflow-hidden mb-6 relative group/card">
                            <img src="{{ asset('assets/images/prod_tshirt.png') }}" alt="Core T-Shirt" class="w-full h-full object-contain p-8 group-hover/card:scale-105 transition-transform duration-500">
                            <button class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white text-charcoal px-6 py-2 rounded-full text-xs font-medium opacity-0 group-hover/card:opacity-100 transition-all shadow-sm whitespace-nowrap">Add to Bag</button>
                        </div>
                        <div class="flex justify-between items-start">
                            <div class="text-left">
                                <h4 class="text-charcoal font-medium text-sm">Heavy Cotton Tee</h4>
                                <p class="text-charcoal/40 text-[10px] tracking-widest uppercase">Off-White</p>
                            </div>
                            <span class="text-sm font-medium">$45.00</span>
                        </div>
                    </div>
                    <!-- Product 2 -->
                    <div class="w-72 flex-shrink-0">
                        <div class="aspect-square bg-sand rounded-xl overflow-hidden mb-6 relative group/card">
                            <img src="{{ asset('assets/images/prod_linen.png') }}" alt="Linen Shirt" class="w-full h-full object-contain p-8 group-hover/card:scale-105 transition-transform duration-500">
                            <button class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white text-charcoal px-6 py-2 rounded-full text-xs font-medium opacity-0 group-hover/card:opacity-100 transition-all shadow-sm whitespace-nowrap">Add to Bag</button>
                        </div>
                        <div class="flex justify-between items-start">
                            <div class="text-left">
                                <h4 class="text-charcoal font-medium text-sm">Relaxed Linen Shirt</h4>
                                <p class="text-charcoal/40 text-[10px] tracking-widest uppercase">Mist Grey</p>
                            </div>
                            <span class="text-sm font-medium">$85.00</span>
                        </div>
                    </div>
                    <!-- Product 3 -->
                    <div class="w-72 flex-shrink-0">
                        <div class="aspect-square bg-sand rounded-xl overflow-hidden mb-6 relative group/card">
                            <img src="{{ asset('assets/images/prod_knit.png') }}" alt="Knit Sweater" class="w-full h-full object-contain p-8 group-hover/card:scale-105 transition-transform duration-500">
                            <button class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white text-charcoal px-6 py-2 rounded-full text-xs font-medium opacity-0 group-hover/card:opacity-100 transition-all shadow-sm whitespace-nowrap">Add to Bag</button>
                        </div>
                        <div class="flex justify-between items-start">
                            <div class="text-left">
                                <h4 class="text-charcoal font-medium text-sm">Cashmere Blend Knit</h4>
                                <p class="text-charcoal/40 text-[10px] tracking-widest uppercase">Beige</p>
                            </div>
                            <span class="text-sm font-medium">$120.00</span>
                        </div>
                    </div>
                    <!-- Product 4 -->
                    <div class="w-72 flex-shrink-0">
                        <div class="aspect-square bg-sand rounded-xl overflow-hidden mb-6 relative group/card">
                            <img src="{{ asset('assets/images/prod_tote.png') }}" alt="Canvas Tote" class="w-full h-full object-contain p-8 group-hover/card:scale-105 transition-transform duration-500">
                            <button class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white text-charcoal px-6 py-2 rounded-full text-xs font-medium opacity-0 group-hover/card:opacity-100 transition-all shadow-sm whitespace-nowrap">Add to Bag</button>
                        </div>
                        <div class="flex justify-between items-start">
                            <div class="text-left">
                                <h4 class="text-charcoal font-medium text-sm">Minimal Canvas Tote</h4>
                                <p class="text-charcoal/40 text-[10px] tracking-widest uppercase">Natural</p>
                            </div>
                            <span class="text-sm font-medium">$35.00</span>
                        </div>
                    </div>
                </div>

                <!-- Cloned Items for Infinite effect -->
                <div class="flex gap-8" aria-hidden="true">
                    <!-- Product 1 (Clone) -->
                    <div class="w-72 flex-shrink-0">
                        <div class="aspect-square bg-sand rounded-xl overflow-hidden mb-6 relative group/card">
                            <img src="{{ asset('assets/images/prod_tshirt.png') }}" alt="Core T-Shirt" class="w-full h-full object-contain p-8 group-hover/card:scale-105 transition-transform duration-500">
                            <button class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white text-charcoal px-6 py-2 rounded-full text-xs font-medium opacity-0 group-hover/card:opacity-100 transition-all shadow-sm whitespace-nowrap">Add to Bag</button>
                        </div>
                        <div class="flex justify-between items-start">
                            <div class="text-left">
                                <h4 class="text-charcoal font-medium text-sm">Heavy Cotton Tee</h4>
                                <p class="text-charcoal/40 text-[10px] tracking-widest uppercase">Off-White</p>
                            </div>
                            <span class="text-sm font-medium">$45.00</span>
                        </div>
                    </div>
                    <!-- Product 2 (Clone) -->
                    <div class="w-72 flex-shrink-0">
                        <div class="aspect-square bg-sand rounded-xl overflow-hidden mb-6 relative group/card">
                            <img src="{{ asset('assets/images/prod_linen.png') }}" alt="Linen Shirt" class="w-full h-full object-contain p-8 group-hover/card:scale-105 transition-transform duration-500">
                            <button class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white text-charcoal px-6 py-2 rounded-full text-xs font-medium opacity-0 group-hover/card:opacity-100 transition-all shadow-sm whitespace-nowrap">Add to Bag</button>
                        </div>
                        <div class="flex justify-between items-start">
                            <div class="text-left">
                                <h4 class="text-charcoal font-medium text-sm">Relaxed Linen Shirt</h4>
                                <p class="text-charcoal/40 text-[10px] tracking-widest uppercase">Mist Grey</p>
                            </div>
                            <span class="text-sm font-medium">$85.00</span>
                        </div>
                    </div>
                    <!-- Product 3 (Clone) -->
                    <div class="w-72 flex-shrink-0">
                        <div class="aspect-square bg-sand rounded-xl overflow-hidden mb-6 relative group/card">
                            <img src="{{ asset('assets/images/prod_knit.png') }}" alt="Knit Sweater" class="w-full h-full object-contain p-8 group-hover/card:scale-105 transition-transform duration-500">
                            <button class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white text-charcoal px-6 py-2 rounded-full text-xs font-medium opacity-0 group-hover/card:opacity-100 transition-all shadow-sm whitespace-nowrap">Add to Bag</button>
                        </div>
                        <div class="flex justify-between items-start">
                            <div class="text-left">
                                <h4 class="text-charcoal font-medium text-sm">Cashmere Blend Knit</h4>
                                <p class="text-charcoal/40 text-[10px] tracking-widest uppercase">Beige</p>
                            </div>
                            <span class="text-sm font-medium">$120.00</span>
                        </div>
                    </div>
                    <!-- Product 4 (Clone) -->
                    <div class="w-72 flex-shrink-0">
                        <div class="aspect-square bg-sand rounded-xl overflow-hidden mb-6 relative group/card">
                            <img src="{{ asset('assets/images/prod_tote.png') }}" alt="Canvas Tote" class="w-full h-full object-contain p-8 group-hover/card:scale-105 transition-transform duration-500">
                            <button class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white text-charcoal px-6 py-2 rounded-full text-xs font-medium opacity-0 group-hover/card:opacity-100 transition-all shadow-sm whitespace-nowrap">Add to Bag</button>
                        </div>
                        <div class="flex justify-between items-start">
                            <div class="text-left">
                                <h4 class="text-charcoal font-medium text-sm">Minimal Canvas Tote</h4>
                                <p class="text-charcoal/40 text-[10px] tracking-widest uppercase">Natural</p>
                            </div>
                            <span class="text-sm font-medium">$35.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-20">
            <a href="#" class="inline-block border-b border-charcoal text-xs font-semibold tracking-widest uppercase pb-1 hover:text-clay hover:border-clay transition-all">View All Essentials</a>
        </div>
    </section>

    <!-- 5. CTA & Footer (Unified Section) -->
    <section class="relative py-24 px-6 md:px-12 bg-sand min-h-[800px] flex flex-col justify-center overflow-hidden">
        <!-- Full Section Background with footer.jpeg -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/images/footer.jpeg') }}" alt="Universal Footer Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-[1px]"></div>
        </div>

        <div class="max-w-7xl mx-auto w-full relative z-10 h-full flex flex-col justify-between">
            <!-- Top Part: Large Heading & Centered CTA -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-16 py-20">
                <div class="max-w-2xl">
                    <span class="inline-block text-clay tracking-[0.4em] uppercase text-xs font-bold mb-8">Join the Movement</span>
                    <h2 class="text-6xl md:text-8xl font-serif leading-[1.05] text-white">Style That <br> Works Around <br> <span class="italic text-clay">You.</span></h2>
                </div>

                <div class="flex flex-col text-right uppercase tracking-[0.2em] pt-10">
                    <!-- Main CTA Button -->
                    <a href="{{ route('register') }}" class="w-full md:w-auto bg-clay/90 text-white px-12 py-6 rounded-[2rem] text-sm font-bold flex items-center justify-center hover:bg-clay transition-all shadow-xl group">
                        Register Now
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-3 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Bottom "Accent" Bar: Socials Left, Copyright Right -->
            <div class="bg-clay/90 backdrop-blur-xl rounded-[2.5rem] p-6 md:p-3 flex flex-col md:flex-row justify-between items-center shadow-2xl mt-auto">
                <!-- Social Links -->
                <div class="flex space-x-8 px-8 py-4">
                    <a href="#" class="text-[10px] tracking-[0.2em] uppercase font-bold text-charcoal hover:text-white transition-colors">Instagram</a>
                    <a href="#" class="text-[10px] tracking-[0.2em] uppercase font-bold text-charcoal hover:text-white transition-colors">Journal</a>
                    <a href="#" class="text-[10px] tracking-[0.1em] uppercase font-bold text-charcoal hover:text-white transition-colors">Support</a>
                </div>
                
                <!-- Copyright (Now Right Aligned) -->
                <p class="text-[10px] tracking-wide font-medium text-charcoal/80 uppercase px-8 py-4">
                    © 2026 Essence. Built for Eternity.
                </p>
            </div>
        </div>
    </section>

</body>
</html>
