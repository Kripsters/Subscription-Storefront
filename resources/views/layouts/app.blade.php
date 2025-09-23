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
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-zinc-100 dark:bg-zinc-900">
            @include('layouts.navigation')

            @if (request()->routeIs('dashboard'))
                <div class="absolute inset-0 overflow-hidden">
                    <!-- Slideshow container -->
                    <div id="slideshow" class="absolute inset-0">
                        <div class="slide absolute inset-0 bg-cover bg-center filter blur-[10px] opacity-100 transition-opacity duration-1000"></div>
                        <div class="slide absolute inset-0 bg-cover bg-center filter blur-[10px] opacity-0 transition-opacity duration-1000"></div>
                    </div>
                </div>

                <script>
                        document.addEventListener("DOMContentLoaded", () => {
                        const slides = document.querySelectorAll("#slideshow .slide");

                        const images = [
                            "storage/images/background-dash-1.jpg",
                            "storage/images/background-dash-2.jpg"
                        ];

                        let current = 0;
                        let next = 1;

                        // Set initial images
                        slides[current].style.backgroundImage = `url(${images[current]})`;
                        slides[next].style.backgroundImage = `url(${images[next]})`;

                        function changeSlide() {
                            // Fade out current
                            slides[current].classList.remove("opacity-100");
                            slides[current].classList.add("opacity-0");

                            // Fade in next
                            slides[next].classList.remove("opacity-0");
                            slides[next].classList.add("opacity-100");

                            // Cycle indices
                            current = next;
                            next = (next + 1) % images.length;

                            // Preload upcoming image
                            slides[next].style.backgroundImage = `url(${images[next]})`;
                        }

                        setInterval(changeSlide, 5000); // switch every 5s
                        });

                    </script>
            @endif



            {{-- <div class="absolute inset-0 overflow-hidden">
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
            </div> --}}

            <div class="relative z-10">
                                <!-- Page Heading -->
                                @isset($header)
                                    <header class="bg-zinc-50/70 dark:bg-zinc-800 shadow">
                                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                            {{ $header }}
                                        </div>
                                    </header>
                                @endisset   
                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
