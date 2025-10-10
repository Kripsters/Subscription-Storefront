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
            @endif <!-- End of about if -->

            
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
    </body>
</html>
