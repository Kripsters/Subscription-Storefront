<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 leading-tight">
            {{ __('welcome.welcome') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex items-center justify-center mt-4">
            @if (app()->getLocale() === 'en')
                <a href="{{ route('lang.switch', 'en') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-600 dark:text-gray-300 rounded-md mx-2">
                    <span class="font-semibold text-zinc-800 dark:text-zinc-100">{{ __('English') }}</span>
                </a>
            @else
                <a href="{{ route('lang.switch', 'en') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-600 dark:text-gray-300 rounded-md mx-2">
                    {{ __('English') }}
                </a>
            @endif

            @if (app()->getLocale() === 'lv')
                <a href="{{ route('lang.switch', 'lv') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-600 dark:text-gray-300 rounded-md mx-2">
                    <span class="font-semibold text-zinc-800 dark:text-zinc-100">{{ __('Latviešu') }}</span>
                </a>
            @else
                <a href="{{ route('lang.switch', 'lv') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-600 dark:text-gray-300 rounded-md mx-2">
                    {{ __('Latviešu') }}
                </a>
            @endif
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-100 dark:bg-zinc-900  overflow-hidden shadow-sm sm:rounded-lg">
                <h1 class="p-6 text-3xl font-bold text-zinc-800 dark:text-zinc-100">
                    {{ __('welcome.welcome_message') }}
                </h1>
                <div class="p-6 text-zinc-800 dark:text-zinc-400">
                    <a href="{{route('login')}}">{{ __('welcome.login') }}</a>
                </div>
                <div class="p-6 text-zinc-800 dark:text-zinc-400">
                    <a href="{{route('register')}}">{{ __('welcome.register') }}</a>
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>
