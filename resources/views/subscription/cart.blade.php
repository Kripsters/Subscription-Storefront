<x-app-layout>
    @php
        // Currency symbol (adjust as needed)
        $currency = '€';

        // Derive subtotal from the products your controller passes in
        $subtotal = collect($existingItems)->sum(function ($p) {
            return $p->price ?? 0;
        });
    @endphp

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        {{-- Content --}}
        @if(collect($existingItems)->count() > 0)
            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
                {{-- Items table --}}
                <div class="lg:col-span-2">
                    <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-zinc-50 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                            <thead class="bg-zinc-50 dark:bg-zinc-900">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-300">
                                        {{ __('cart.item') }}
                                    </th>
                                    {{-- If you track quantities in subscription orders, add a quantity column and bind it here --}}
                                    {{-- <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-300">
                                        {{ __('cart.quantity') }}
                                    </th> --}}
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-300">
                                        {{ __('cart.unit_price') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">

                                @foreach($existingItems as $item)
                                    <tr class="hover:bg-zinc-50/60 dark:hover:bg-zinc-800/40 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div>
                                                    <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                        {{ $item->title ?? __('cart.item') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                {{ $currency }}{{ number_format($item->price ?? 0, 2) }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Clear cart (optional) --}}
                </div>

                {{-- Summary card --}}
                <aside class="lg:col-span-1">
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ __('cart.summary') ?? 'Summary' }}
                        </h2>

                        <dl class="mt-4 space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-zinc-600 dark:text-zinc-400">{{ __('cart.total') }}</dt>
                                <dd class="font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $currency }}{{ number_format($subtotal, 2) }}
                                </dd>
                            </div>
                            {{-- Add additional rows (discounts, shipping, etc.) if needed --}}
                        </dl>
                    </div>
                </aside>
            </div>
        @else
            {{-- Empty state --}}
            <div class="mt-12 rounded-xl border border-dashed border-zinc-300 bg-white p-10 text-center dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 text-zinc-500 dark:bg-zinc-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 8h14l-2-8M10 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"/>
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-zinc-100">
                    {{ __('cart.empty') }}
                </h3>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('cart.empty_cta') ?? 'Browse products and add them to your subscription.' }}
                </p>
            </div>
        @endif
    </div>
</x-app-layout>