<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Subscription Users</x-slot>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-200 dark:border-gray-700">
                        <th class="w-1/5 pb-3 pr-8 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">User</th>
                        <th class="w-1/4 pb-3 pr-8 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Subscription Products</th>
                        <th class="w-1/4 pb-3 pr-8 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Shipping Address</th>
                        <th class="w-1/4 pb-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Replacements</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($users as $user)
                        <tr class="group transition-colors hover:bg-gray-50 dark:hover:bg-white/5">

                            {{-- User --}}
                            <td class="py-4 pr-8 align-top">
                                <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $user['name'] }}</div>
                                <div class="mt-0.5 text-xs text-gray-500 dark:text-gray-400 break-all">{{ $user['email'] }}</div>
                            </td>

                            {{-- Products --}}
                            <td class="py-4 pr-8 align-top">
                                @if($user['products']->isNotEmpty())
                                    <div class="flex flex-col gap-1.5">
                                        @foreach($user['products'] as $product)
                                            <span class="inline-flex w-fit items-center rounded-md bg-primary-50 px-2.5 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-700/10 dark:bg-primary-400/10 dark:text-primary-400 dark:ring-primary-400/30">
                                                {{ $product }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-600">—</span>
                                @endif
                            </td>

                            {{-- Shipping Address --}}
                            <td class="py-4 pr-8 align-top">
                                @if($user['shipping'])
                                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                        {{ $user['shipping'] }}
                                    </div>
                                    @if($user['shipping_is_billing'])
                                        <div class="mt-1.5 inline-flex items-center gap-1 rounded-md bg-gray-100 px-2 py-0.5 text-xs text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                            <x-filament::icon icon="heroicon-m-information-circle" class="h-3 w-3" />
                                            Billing address
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400 dark:text-gray-600">—</span>
                                @endif
                            </td>

                            {{-- Replacements --}}
                            <td class="py-4 align-top">
                                @if($user['replacements']->isNotEmpty())
                                    <div class="flex flex-col gap-1.5">
                                        @foreach($user['replacements'] as $replacement)
                                            <span class="inline-flex w-fit items-center rounded-md bg-warning-50 px-2.5 py-1 text-xs font-medium text-warning-700 ring-1 ring-inset ring-warning-700/10 dark:bg-warning-400/10 dark:text-warning-400 dark:ring-warning-400/30">
                                                {{ $replacement }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-600">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-sm text-gray-400 dark:text-gray-600">
                                No subscription users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
