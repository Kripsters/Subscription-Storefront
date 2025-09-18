<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('stockedup.name') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-gradient-to-r from-zinc-200 to-zinc-300 dark:from-zinc-800 dark:to-zinc-900 overflow-hidden shadow-xl sm:rounded-2xl hover:shadow-2xl transition">
                <div class="p-8 border-b border-zinc-300 dark:border-zinc-700 flex items-center space-x-6">
                    <x-application-logo class="hidden sm:block h-24 w-24 fill-current text-zinc-800 dark:text-zinc-200" />
                    <div>
                        <p class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ __('about.about') }}
                        </p>
                        <p class="text-zinc-600 dark:text-zinc-400">
                            {{ __('about.about_subtext') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-gradient-to-r from-zinc-200 to-zinc-300 dark:from-zinc-800 dark:to-zinc-900 overflow-hidden shadow-xl sm:rounded-2xl hover:shadow-2xl transition">
                <div class="p-8 border-b border-zinc-300 dark:border-zinc-700 flex items-center space-x-6">
                    <x-application-logo class="hidden sm:block h-12 w-12 fill-current text-zinc-800 dark:text-zinc-200" />
                    <div>
                        <p class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ __('about.contact') }}
                        </p>
                        <p class="text-zinc-600 dark:text-zinc-400">
                            {{ __('about.contact_subtext') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
