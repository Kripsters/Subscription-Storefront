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
                        <h2 class="text-lg leading-7 font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ __('Orders') }}
                        </h2>
                        <p class="mt-2 text-zinc-600 dark:text-zinc-400">{{ __('View all your orders') }}</p>
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

    <!-- <script async src="https://js.stripe.com/v3/pricing-table.js"></script>
    <stripe-pricing-table pricing-table-id="{{config('services.stripe.pricing_table.id')}}" publishable-key="{{config('services.stripe.key')}}" client-reference-id="{{auth()->user()->id}}" mode="subscription" ></stripe-pricing-table> -->
    <!-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-200 dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-zinc-300 dark:bg-zinc-800 border-b border-zinc-700 dark:border-zinc-700">
                    <div class="flex items-center">
                        <div class="text-lg leading-7 font-semibold mr-8">
                            <x-application-logo class="block h-9 w-auto fill-current text-zinc-800 dark:text-zinc-200" />
                        </div>
                        <div class="text-lg leading-7 font-semibold">
                            <p class="text-zinc-900 dark:text-zinc-100">{{ __('Check out our pricing plans!') }}</p>
                            <p class="text-zinc-600 dark:text-zinc-400">{{ __('Choose the best plan for yourself.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mt-8">
                    <div class="p-6 bg-zinc-300 dark:bg-zinc-800 border border-zinc-700 dark:border-zinc-700 rounded-lg m-8">
                        <h2 class="text-lg leading-7 font-semibold text-lime-800 dark:text-lime-700">
                            {{ __('40€/month') }}
                        </h2>
                        <p class="mt-2 text-zinc-600 dark:text-zinc-400">{{ __('Just enough to get those basics every month') }}</p>
                    </div>
                    <div class="p-6 bg-zinc-300 dark:bg-zinc-800 border border-zinc-700 dark:border-zinc-700 rounded-lg m-8">
                        <h2 class="text-lg leading-7 font-semibold text-lime-700 dark:text-lime-600">
                            {{ __('80€/month') }}
                        </h2>
                        <p class="mt-2 text-zinc-600 dark:text-zinc-400">{{ __('Keep your fridge stocked without worry') }}</p>
                    </div>
                    <div class="p-6 bg-zinc-300 dark:bg-zinc-800 border border-zinc-700 dark:border-zinc-700 rounded-lg m-8">
                        <h2 class="text-lg leading-7 font-semibold text-lime-500 dark:text-lime-400">
                            {{ __('120€/month') }}
                        </h2>
                        <p class="mt-2 text-zinc-600 dark:text-zinc-400">{{ __('Stop worrying about driving to the store') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</x-app-layout>
