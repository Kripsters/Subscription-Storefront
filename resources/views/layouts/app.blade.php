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



            @if (request()->routeIs('dashboard')) <!-- If the current route is dashboard -->
                <div class="absolute inset-0 overflow-hidden border-2 sm:border-4 rounded-2xl border-zinc-800 dark:border-zinc-200">
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

                        // Create slides dynamically
                        images.forEach((src, i) => {
                            const slide = document.createElement("div");
                            slide.className =
                            "slide absolute inset-0 bg-cover bg-center filter blur-[10px] transition-opacity duration-1000 " +
                            (i === 0 ? "opacity-100" : "opacity-0");
                            slide.style.backgroundImage = `url(${src})`;
                            slideshow.appendChild(slide);
                        });
                        

                        
                        const slides = document.querySelectorAll("#slideshow .slide");
                        let current = 0;
                        
                        function changeSlide() {
                            const next = (current + 1) % slides.length;
                        
                            // Fade out current
                            slides[current].classList.remove("opacity-100");
                            slides[current].classList.add("opacity-0");
                        
                            // Fade in next
                            slides[next].classList.remove("opacity-0");
                            slides[next].classList.add("opacity-100");
                        
                            current = next;
                        }
                        
                        setInterval(changeSlide, 5000); // switch every 5s
                        });
                    </script>
                    {{-- JS end --}}
            @endif
            {{-- End of dashboard if --}}



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
            </div>
            <x-footer />
        </div>
    </body>
</html>
