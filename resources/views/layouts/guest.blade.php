<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-zinc-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative">
            <!-- Background layer -->
            <div class="absolute inset-0 bg-no-repeat bg-center bg-cover" 
                 style="background-image: url('storage/images/background-guest.jpg'); filter: blur(10px);">
            </div>
    
            <!-- Content layer -->
            <div class="relative z-10">
                <div class="bg-zinc-100/70 dark:bg-zinc-800/70 sm:rounded-lg flex items-center justify-center"> 
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-zinc-800 dark:text-zinc-200 animate-pulse" />
                    </a>
                </div>
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-zinc-100/70 dark:bg-zinc-800/70 shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
