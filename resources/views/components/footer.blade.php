<footer class="bg-white dark:bg-zinc-900 border-t border-zinc-200/50 dark:border-zinc-800 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            
            {{-- Logo / About --}}
            <div class="md:col-span-1">
                <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">{{ __('about.about') }}</h2>
                <p class="mt-3 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ __('about.about_shortsubtext') }}
                </p>
            </div>

            {{-- Links --}}
            <div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 tracking-wide uppercase">{{ __('footer.links') }}</h3>
                <ul class="mt-4 space-y-3">
                    <li><a href="{{ route('about') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">{{ __('navigation.about') }}</a></li>
                    <li><a href="{{ route('dashboard') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">{{ __('navigation.dashboard') }}</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">{{ __('navigation.products') }}</a></li>
                </ul>
            </div>

            {{-- Socials --}}
            <div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 tracking-wide uppercase">{{ __('footer.socials') }}</h3>
                <div class="mt-4">
                    <a href="https://github.com/Kripsters/Subscription-Storefront" class="inline-flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                        <x-icons.chat class="w-5 h-5"/>
                        <span>{{ __('navigation.github') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-zinc-200/50 dark:border-zinc-800">
        <p class="text-center py-6 text-xs text-zinc-500 dark:text-zinc-500">
            &copy; {{ date('Y') }} {{ __('footer.rights') }}
        </p>
    </div>
</footer>