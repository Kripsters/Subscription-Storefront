<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('StockedUp') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-zinc-800 shadow sm:rounded-lg">
                <h2 class="text-2xl font-semibold text-zinc-800 dark:text-zinc-200">
                    {{ __('About us') }}
                </h2>
                <p class="mt-2 text-zinc-600 dark:text-zinc-400">
                    {{ __('StockedUp is a grocery delivery service, designed to make it easier for you to order your groceries online. We believe that everyone should have access to fresh and healthy food, regardless of their location and availability. Our mission is to make grocery shopping as convenient as possible, by providing a one-stop-shop for all your grocery needs.') }}
                </p>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-zinc-800 shadow sm:rounded-lg">
                <h2 class="text-2xl font-semibold text-zinc-800 dark:text-zinc-200">
                    {{ __('Contact') }}
                </h2>
                <p class="mt-2 text-zinc-600 dark:text-zinc-400">
                    {{ __('If you have any questions or concerns, please do not hesitate to contact us. Our team is available 24/7 to assist you with any issues you may have.') }}
            </div>
        </div>
    </div>
</x-app-layout>
