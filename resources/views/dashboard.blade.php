<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('StockedUp') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-200 dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-zinc-300 dark:bg-zinc-800 border-b border-zinc-500 dark:border-zinc-700">
                    <div class="flex items-center">
                        <div class="text-lg leading-7 font-semibold mr-8">
                            <x-application-logo class="block h-9 w-auto fill-current text-zinc-800 dark:text-zinc-200" />
                        </div>
                        <div class="text-lg leading-7 font-semibold">
                            <p class="text-zinc-900 dark:text-zinc-100">{{ __('Welcome to your grocery delivery service!') }}</p>
                            <p class="text-zinc-600 dark:text-zinc-400">{{ __('Here you can manage your orders, products, and more.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mt-8">
                    <div class="p-6 bg-zinc-300 dark:bg-zinc-800 border border-zinc-700 dark:border-zinc-700 rounded-lg m-8">
                        <a href="{{ route('subscription.index') }}">
                            <h2 class="text-lg leading-7 font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Orders') }}
                            </h2>
                            <p class="mt-2 text-zinc-600 dark:text-zinc-400">{{ __('View all your orders') }}</p>
                        </a>
                    </div>
                    <div class="p-6 bg-zinc-300 dark:bg-zinc-800 border border-zinc-700 dark:border-zinc-700 rounded-lg m-8">
                        <a href="{{ route('products.index') }}">
                            <h2 class="text-lg leading-7 font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Products') }}
                            </h2>
                            <p class="mt-2 text-zinc-600 dark:text-zinc-400">{{ __('View all available products') }}</p>
                        </a>
                    </div>
                    <div class="p-6 bg-zinc-300 dark:bg-zinc-800 border border-zinc-700 dark:border-zinc-700 rounded-lg m-8">
                        <a href="{{ route('about') }}">
                            <h2 class="text-lg leading-7 font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('About us') }}
                            </h2>
                            <p class="mt-2 text-zinc-600 dark:text-zinc-400">{{ __('Learn more about us') }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
