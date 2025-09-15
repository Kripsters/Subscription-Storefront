<?php 
if (isset($subscription->billing_address)) {
$billing_address = json_decode($subscription->billing_address, true);
$shipping_address = json_decode($subscription->shipping_address, true);
}
?>
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 dark:text-gray-100">
            Subscription Overview
        </h1>
        @if (!$subscription)
        <p class="mt-4 text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
            You have no subscription yet.
        </p>
        @else
        <div class="mt-4 p-6 bg-zinc-100 rounded-lg border border-zinc-200 shadow hover:bg-zinc-50 dark:bg-zinc-800 dark:border-zinc-700 dark:hover:bg-zinc-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
                Subscription Status: 
                @if ($subscription->status === 'active')
                    <span class="text-green-600 dark:text-green-400">Active</span>
                @else
                    <span class="text-red-600 dark:text-red-400">Inactive</span>
                @endif
            </h5>
            <p class="font-normal text-zinc-700 dark:text-zinc-300">
                Billing name: {{ $subscription->billing_name }}
            </p>
            <p class="font-normal text-zinc-700 dark:text-zinc-300">
                Billing e-mail: {{ $subscription->billing_email }}
            </p>
            <p class="font-normal text-zinc-700 dark:text-zinc-300">
                Billing address: {{ $billing_address['line1'] }}, {{ $billing_address['city'] }}, {{ $billing_address['state'] }}, {{ $billing_address['postal_code'] }}, {{ $billing_address['country'] }}
            </p>
            <p class="font-normal text-zinc-700 dark:text-zinc-300">
                Shipping address: @if ($shipping_address) 
                {{ $shipping_address['line1'] }}, {{ $shipping_address['city'] }}, {{ $shipping_address['state'] }}, {{ $shipping_address['postal_code'] }}, {{ $shipping_address['country'] }} 
                @else 
                Shipping address is same as billing address
                @endif
            </p>
        </div>
        @endif
    </div>
</x-app-layout>