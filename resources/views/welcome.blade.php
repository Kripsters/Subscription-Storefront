<x-guest-layout>
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 flex flex-col">

        {{-- Top bar: language switcher + dark mode --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
            <div class="flex items-center gap-2">
                @foreach (['en' => 'EN', 'lv' => 'LV'] as $locale => $label)
                    <a href="{{ route('lang.switch', $locale) }}"
                       class="px-3 py-1.5 text-sm rounded-md transition
                              {{ app()->getLocale() === $locale
                                  ? 'bg-zinc-900 dark:bg-zinc-100 text-zinc-50 dark:text-zinc-900 font-semibold'
                                  : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-800' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <button id="theme-toggle"
                    class="p-2 rounded-md bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300
                           hover:bg-zinc-300 dark:hover:bg-zinc-700 transition">
                <span id="theme-toggle-light" class="hidden text-sm">🌙</span>
                <span id="theme-toggle-dark" class="hidden text-sm">🌞</span>
            </button>
        </div>

        {{-- Hero --}}
        <main class="flex flex-1 flex-col items-center justify-center px-6 py-20 text-center">

            {{-- Logo / wordmark placeholder --}}
            <div class="mb-6 inline-flex items-center justify-center w-16 h-16 rounded-2xl
                        bg-zinc-900 dark:bg-zinc-100 shadow-lg">
                <svg class="w-8 h-8 text-zinc-50 dark:text-zinc-900" fill="none" stroke="currentColor"
                     stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z"/>
                </svg>
            </div>

            <h1 class="text-4xl sm:text-5xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50 max-w-xl">
                {{ __('welcome.welcome_message') }}
            </h1>

            <p class="mt-4 text-zinc-500 dark:text-zinc-400 text-lg max-w-md">
                {{ __('welcome.subtitle', ['default' => 'Sign in to your account or create one to get started.']) }}
            </p>

            {{-- CTA buttons --}}
            <div class="mt-10 flex flex-col sm:flex-row items-center gap-3 w-full max-w-xs">
                <x-primary-button class="w-full justify-center">
                    <a href="{{ route('login') }}" class="w-full block text-center">
                        {{ __('welcome.login') }}
                    </a>
                </x-primary-button>

                <x-primary-button class="w-full justify-center">
                    <a href="{{ route('register') }}" class="w-full block text-center">
                        {{ __('welcome.register') }}
                    </a>
                </x-primary-button>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="py-6 text-center text-sm text-zinc-400 dark:text-zinc-600 border-t border-zinc-200 dark:border-zinc-800">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </footer>

    </div>
</x-guest-layout>