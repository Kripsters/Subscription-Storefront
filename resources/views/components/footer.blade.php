<footer class="bg-white dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-700 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 md:grid-cols-3 gap-8">
        
        {{-- Logo / About --}}
        <div>
            <h2 class="text-xl font-bold text-zinc-800 dark:text-zinc-200">{{ __('about.about') }}</h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('about.about_shortsubtext') }}
            </p>
        </div>

        {{-- Links --}}
        <div>
            <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">{{ __('footer.links') }}</h3>
            <ul class="mt-3 space-y-2">
                <li><a href="{{ route('about') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-purple-600">{{ __('navigation.about') }}</a></li>
                <li><a href="{{ route('dashboard') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-purple-600">{{ __('navigation.dashboard') }}</a></li>
                <li><a href="{{ route('dashboard') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-purple-600">{{ __('navigation.products') }}</a></li>
            </ul>
        </div>

        {{-- Socials --}}
        <div>
            <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">{{ __('footer.socials') }}</h3>
            <div class="mt-3 flex space-x-4">
                <a href="https://github.com/Kripsters/Subscription-Storefront" class="text-zinc-500 hover:text-purple-600">
                    <x-icons.chat class="w-5 h-5"/>  <p>{{ __('navigation.github') }}</p>
                </a>
            </div>
        </div>
    </div>

    <div class="border-t border-zinc-200 dark:border-zinc-700 mt-8">
        <p class="text-center py-4 text-xs text-zinc-500">
            &copy; {{ date('Y') }} {{ __('footer.rights') }}
        </p>
    </div>
</footer>