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
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-zinc-100 dark:bg-zinc-900">
            @include('layouts.navigation')



            @if (request()->routeIs('dashboard')) <!-- If the current route is dashboard -->
            <div class="absolute inset-0 overflow-hidden rounded-2xl">
                <!-- Slideshow container -->
                <div id="slideshow" class="absolute inset-0 h-full"></div>
            </div>
            
            {{-- JS for slideshow --}}
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const numImages = 4; // configurable number of images
                    const images = []; // set image array
            
                    for (let i = 1; i <= numImages; i++) {
                        images.push(`storage/images/background-dash-${i}.jpg`); // add image URLs to array from 1 to numImages
                    }
            
                    const slideshow = document.getElementById("slideshow");
                    const slides = [];
                    let current = 0;
                    let isLoaded = {}; // track which images have been loaded
            
                    // Create slide elements (without background images yet)
                    images.forEach((src, i) => {
                        const slide = document.createElement("div");
                        slide.className =
                            "slide absolute inset-0 bg-cover bg-center filter blur-[10px] transition-opacity duration-1000 opacity-0";
                        slide.dataset.src = src; // store URL for lazy loading
                        slideshow.appendChild(slide);
                        slides.push(slide);
                    });
            
                    // Function to load an image
                    function loadImage(index) {
                        if (isLoaded[index]) return Promise.resolve();
                        
                        return new Promise((resolve) => {
                            const img = new Image();
                            img.onload = () => {
                                slides[index].style.backgroundImage = `url(${images[index]})`;
                                isLoaded[index] = true;
                                resolve();
                            };
                            img.onerror = () => resolve(); // continue even if image fails
                            img.src = images[index];
                        });
                    }
            
                    // Load first image and show it
                    loadImage(0).then(() => {
                        slides[0].classList.add("opacity-100");
                    });
            
                    // Preload next image
                    if (images.length > 1) {
                        loadImage(1);
                    }
            
                    function changeSlide() {
                        const next = (current + 1) % slides.length;
                        
                        // Load next image if not already loaded
                        loadImage(next).then(() => {
                            // Fade out current
                            slides[current].classList.remove("opacity-100");
                            slides[current].classList.add("opacity-0");
            
                            // Fade in next
                            slides[next].classList.remove("opacity-0");
                            slides[next].classList.add("opacity-100");
            
                            current = next;
                            
                            // Preload the image after next
                            const afterNext = (next + 1) % slides.length;
                            loadImage(afterNext);
                        });
                    }
            
                    setInterval(changeSlide, 5000); // switch every 5s
                });
            </script>
                    {{-- JS end --}}
            @endif
            {{-- End of dashboard if --}}



            @if (request()->routeIs('about')) <!-- If the current route is about -->
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
            @endif

            
            <div class="relative z-10">
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
            const themeToggle = document.getElementById('theme-toggle');
            const lightIcon = document.getElementById('theme-toggle-light');
            const darkIcon = document.getElementById('theme-toggle-dark');
        
            // On page load -> set icon
            if (localStorage.theme === 'dark' || 
                (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                lightIcon.classList.add('hidden');
                darkIcon.classList.remove('hidden');
            }
        
            // On click -> toggle
            themeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                    darkIcon.classList.add('hidden');
                    lightIcon.classList.remove('hidden');
                } else {
                    localStorage.setItem('theme', 'light');
                    lightIcon.classList.add('hidden');
                    darkIcon.classList.remove('hidden');
                }
            });
        </script>
    </body>
</html>
