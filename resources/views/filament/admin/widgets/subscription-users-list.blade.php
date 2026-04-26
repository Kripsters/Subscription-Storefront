<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Subscription Users</x-slot>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="pb-3 pr-6 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">User</th>
                        <th class="pb-3 pr-6 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Subscription Products</th>
                        <th class="pb-3 pr-6 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Shipping Address</th>
                        <th class="pb-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Replacements</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($users as $user)
                        <tr>
                            <td class="py-3 pr-6">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $user['name'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user['email'] }}</div>
                            </td>

                            <td class="py-3 pr-6">
                                @if($user['products']->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user['products'] as $product)
                                            <span class="inline-flex items-center rounded-full bg-primary-50 px-2 py-0.5 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-700/10 dark:bg-primary-400/10 dark:text-primary-400 dark:ring-primary-400/30">
                                                {{ $product }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-600">—</span>
                                @endif
                            </td>

                            <td class="py-3 pr-6 text-gray-700 dark:text-gray-300">
                                {{ $user['shipping'] ?? '—' }}
                            </td>

                            <td class="py-3">
                                @if($user['replacements']->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user['replacements'] as $replacement)
                                            <span class="inline-flex items-center rounded-full bg-warning-50 px-2 py-0.5 text-xs font-medium text-warning-700 ring-1 ring-inset ring-warning-700/10 dark:bg-warning-400/10 dark:text-warning-400 dark:ring-warning-400/30">
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
                            <td colspan="4" class="py-10 text-center text-sm text-gray-400 dark:text-gray-600">
                                No subscription users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
