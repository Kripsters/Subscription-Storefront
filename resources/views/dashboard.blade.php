<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-zinc-800 dark:text-zinc-100 tracking-tight">
            {{ __('stockedup.name') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-gradient-to-r from-zinc-200 to-zinc-100 dark:from-zinc-800 dark:to-zinc-900 overflow-hidden shadow-xl dark:shadow-zinc-800 sm:rounded-2xl hover:shadow-2xl transition">
                
                <!-- Header Welcome -->
                <div class="p-8 border-b border-zinc-300 dark:border-zinc-700 flex items-center space-x-6">
                    <x-application-logo class="h-12 w-12 fill-current text-zinc-800 dark:text-zinc-200" />
                    <div>
                        <p class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ __('dashboard.welcome') }}
                        </p>
                        <p class="text-zinc-600 dark:text-zinc-400">
                            {{ __('dashboard.welcome_subtext') }}
                        </p>
                    </div>
                </div>

                <!-- Cards Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-8">
                    
                    <!-- Orders -->
                    <a href="{{ route('subscription.index') }}" 
                       class="group p-6 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow hover:shadow-lg transition-all">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 group-hover:text-indigo-500">
                            {{ __('dashboard.orders') }}
                        </h2>
                        <p class="mt-2 text-zinc-600 dark:text-zinc-400">{{ __('dashboard.orders_subtext') }}</p>
                    </a>

                    <!-- Products -->
                    <a href="{{ route('products.index') }}" 
                       class="group p-6 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow hover:shadow-lg transition-all">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 group-hover:text-green-500">
                            {{ __('dashboard.products') }}
                        </h2>
                        <p class="mt-2 text-zinc-600 dark:text-zinc-400">{{ __('dashboard.products_subtext') }}</p>
                    </a>

                    <!-- About Us -->
                    <a href="{{ route('about') }}" 
                       class="group p-6 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow hover:shadow-lg transition-all">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 group-hover:text-pink-500">
                            {{ __('dashboard.about') }}
                        </h2>
                        <p class="mt-2 text-zinc-600 dark:text-zinc-400">{{ __('dashboard.about_subtext') }}</p>
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
