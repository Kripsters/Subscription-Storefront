<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Subscription Users</x-slot>

        <div class="space-y-3">
            @forelse($users as $user)
                @php
                    $initials = collect(explode(' ', $user['name']))
                        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                        ->take(2)
                        ->implode('');
                @endphp

                <div class="group overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-200 hover:shadow-md dark:border-gray-700 dark:bg-gray-900">

                    {{-- Card header --}}
                    <div class="flex items-center justify-between gap-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white px-5 py-3.5 dark:border-gray-700/60 dark:from-gray-800 dark:to-gray-900">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-primary-400 to-primary-600 text-xs font-bold text-white shadow-sm">
                                {{ $initials }}
                            </div>
                            <div>
                                <div class="font-semibold leading-tight text-gray-900 dark:text-gray-100">{{ $user['name'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user['email'] }}</div>
                            </div>
                        </div>

                        {{-- Replacement indicator pill in header --}}
                        @if($user['replacements']->isNotEmpty())
                            <span class="shrink-0 rounded-full bg-warning-100 px-2.5 py-1 text-xs font-semibold text-warning-700 dark:bg-warning-400/15 dark:text-warning-400">
                                {{ $user['replacements']->count() }} replacement{{ $user['replacements']->count() > 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>

                    {{-- Card body: 3 columns --}}
                    <div class="grid grid-cols-1 divide-y divide-gray-100 dark:divide-gray-800 sm:grid-cols-3 sm:divide-x sm:divide-y-0">

                        {{-- Products --}}
                        <div class="p-5">
                            <div class="mb-2.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                                Products
                            </div>
                            @if($user['products']->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($user['products'] as $product)
                                        <span class="inline-flex items-center rounded-lg bg-primary-50 px-2.5 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-600/20 dark:bg-primary-500/10 dark:text-primary-400 dark:ring-primary-500/20">
                                            {{ $product }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-sm text-gray-400 dark:text-gray-600">No products</span>
                            @endif
                        </div>

                        {{-- Shipping Address --}}
                        <div class="p-5">
                            <div class="mb-2.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Shipping Address
                            </div>
                            @if($user['shipping'])
                                <p class="text-sm leading-relaxed text-gray-700 dark:text-gray-300">{{ $user['shipping'] }}</p>
                                @if($user['shipping_is_billing'])
                                    <div class="mt-2 inline-flex items-center gap-1 rounded-md bg-gray-100 px-2 py-0.5 text-xs text-gray-500 dark:bg-gray-700/60 dark:text-gray-400">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Using billing address
                                    </div>
                                @endif
                            @else
                                <span class="text-sm text-gray-400 dark:text-gray-600">No address on file</span>
                            @endif
                        </div>

                        {{-- Replacements --}}
                        <div class="p-5">
                            <div class="mb-2.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Replacements
                            </div>
                            @if($user['replacements']->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($user['replacements'] as $replacement)
                                        <span class="inline-flex items-center rounded-lg bg-warning-50 px-2.5 py-1 text-xs font-medium text-warning-700 ring-1 ring-inset ring-warning-600/20 dark:bg-warning-500/10 dark:text-warning-400 dark:ring-warning-500/20">
                                            {{ $replacement }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-sm text-gray-400 dark:text-gray-600">None chosen</span>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 py-14 dark:border-gray-700">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mb-3 text-gray-300 dark:text-gray-600"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="text-sm font-medium text-gray-400 dark:text-gray-600">No subscription users found</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
