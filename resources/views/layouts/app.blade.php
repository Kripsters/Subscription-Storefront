<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'StockedUp') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script> 
        <script>
            if (
              localStorage.theme === 'dark' ||
              (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
            ) {
              document.documentElement.classList.add('dark');
            } else {
              document.documentElement.classList.remove('dark');
            }
          </script>
          <style>
            #animated-bg {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: -1; /* Ensure it's behind other content */
            }
            .bg-shape {
                position: absolute;
                border-radius: 50%;
                filter: blur(25px);
                animation: float 10s ease-in-out infinite;
            }
            @keyframes float {
                0%, 100% {
                    transform: translateY(0) translateX(0) scale(1);
                    opacity: 0.3;
                }
                50% {
                    transform: translateY(-20px) translateX(20px) scale(1.2);
                    opacity: 0.6;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-zinc-100 dark:bg-zinc-900">
            @include('layouts.navigation')



            @if (request()->routeIs('dashboard')) <!-- If the current route is dashboard, add a background slideshow -->
            <div class="absolute inset-0 overflow-hidden rounded-2xl">
                <!-- Slideshow container -->
                <div id="slideshow" class="absolute inset-0 h-full"></div>
            </div>
            @endif
            {{-- End of dashboard if --}}



            @if (request()->routeIs('about')) <!-- If the current route is about, add a background video -->
             <div class="absolute inset-0 overflow-hidden">
                <video 
                    autoplay 
                    loop 
                    muted 
                    playsinline 
                    class="w-full h-full object-cover filter blur-[10px]"
                >
                    <source src="storage/videos/background-video.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            @endif <!-- End of about if -->

            <div class="relative z-10">
                            @if (request()->routeIs('about') || request()->routeIs('dashboard'))
                            @else
                                <div id="animated-bg">
                                    @for ($i = 0; $i < 10; $i++)
                                    <div class="bg-shape bg-purple-300 dark:bg-purple-950"></div>
                                    @endfor
                                </div>
                            @endif
                                <!-- Page Heading -->
                                @isset($header)
                                    <header class="bg-zinc-50/70 dark:bg-zinc-900/60 shadow">
                                        
                                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                            {{ $header }}
                                        </div>
                                    </header>
                                @endisset   
                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
                <x-footer />
            </div>
            
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const shapes = document.querySelectorAll('.bg-shape');
                
                shapes.forEach((shape, index) => {
                    // Random initial position and size
                    shape.style.width = Math.random() * 200 + 100 + 'px';
                    shape.style.height = shape.style.width;
                    shape.style.left = Math.random() * 100 + '%';
                    shape.style.top = Math.random() * 100 + '%';
                    
                    // Animate each shape
                    anime({
                        targets: shape,
                        translateX: () => anime.random(-200, 200),
                        translateY: () => anime.random(-200, 200),
                        scale: [1, 1.5, 1],
                        opacity: [0.3, 0.6, 0.3],
                        duration: 8000 + (index * 2000),
                        easing: 'easeInOutQuad',
                        loop: true,
                        direction: 'alternate'
                    });
                });
            });
            </script>
    </body>
</html>
