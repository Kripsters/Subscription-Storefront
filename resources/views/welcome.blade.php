<x-guest-layout>     
    <div class="py-12 ">
        <div class="flex items-center justify-center mt-4">
            @if (app()->getLocale() === 'en')
                <a href="{{ route('lang.switch', 'en') }}" class="inline-flex items-center px-4 py-2 bg-zinc-100 dark:bg-zinc-900 text-gray-600 dark:text-gray-300 rounded-md mx-2">
                    <span class="font-semibold text-zinc-800 dark:text-zinc-100">{{ __('English') }}</span>
                </a>
            @else
                <a href="{{ route('lang.switch', 'en') }}" class="inline-flex items-center px-4 py-2 bg-zinc-100 dark:bg-zinc-900 text-gray-600 dark:text-gray-300 rounded-md mx-2">
                    {{ __('English') }}
                </a>
            @endif

            @if (app()->getLocale() === 'lv')
                <a href="{{ route('lang.switch', 'lv') }}" class="inline-flex items-center px-4 py-2 bg-zinc-100 dark:bg-zinc-900 text-gray-600 dark:text-gray-300 rounded-md mx-2">
                    <span class="font-semibold text-zinc-800 dark:text-zinc-100">{{ __('Latviešu') }}</span>
                </a>
            @else
                <a href="{{ route('lang.switch', 'lv') }}" class="inline-flex items-center px-4 py-2 bg-zinc-100 dark:bg-zinc-900 text-gray-600 dark:text-gray-300 rounded-md mx-2">
                    {{ __('Latviešu') }}
                </a>
            @endif
        </div>
                            <!-- Dark mode toggle -->
                            <div class="flex justify-center mt-4">
                                <button id="theme-toggle" 
                                class="p-2 rounded-lg bg-zinc-200 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-600 transition sm:mx-12">
                                    <span id="theme-toggle-light" class="hidden">🌙</span>
                                    <span id="theme-toggle-dark" class="hidden">🌞</span>
                                </button>
                            </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
            <div class="bg-gray-100 dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <h1 class="p-6 text-3xl font-bold text-zinc-800 dark:text-zinc-100">
                    {{ __('welcome.welcome_message') }}
                </h1>
                <div class="p-6">
                    <x-primary-button class="w-full">
                        <a href="{{route('login')}}" class="w-full block text-center">{{ __('welcome.login') }}</a>
                    </x-primary-button>
                    <x-primary-button class="w-full mt-4">
                        <a href="{{route('register')}}" class="w-full block text-center">{{ __('welcome.register') }}</a>
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>

