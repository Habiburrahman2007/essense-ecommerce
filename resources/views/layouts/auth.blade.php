<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Auth' }} - Essence</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Lora:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

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

    <div class="min-h-screen flex flex-col items-center justify-center p-6 sm:p-12 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-cream rounded-full blur-[120px] opacity-60"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-taupe/20 rounded-full blur-[120px] opacity-60"></div>

        <div class="w-full max-w-md relative z-10">
            <!-- Logo area -->
            <div class="text-center mb-12">
                <a href="/" class="text-4xl font-serif tracking-tight text-charcoal inline-block mb-2">Essence</a>
                <p class="text-[10px] tracking-[0.3em] uppercase text-clay font-bold">Quiet Luxury Redefined</p>
            </div>

            <!-- Content Slot -->
            <div class="bg-cream/70 backdrop-blur-xl border border-white/40 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.08)] rounded-[2.5rem] p-8 md:p-12 relative overflow-hidden">
                {{ $slot }}
            </div>

            <!-- Simple Back Link -->
            <div class="mt-12 text-center text-[10px] tracking-[0.1em] uppercase">
                <a href="/" class="text-charcoal/40 hover:text-charcoal transition-colors flex items-center justify-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </div>

</body>
</html>
