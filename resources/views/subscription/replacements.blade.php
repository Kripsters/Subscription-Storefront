<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8">

        {{-- Back link --}}
        <a href="{{ route('subscription.cart') }}"
           class="inline-flex items-center gap-1 text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-100 mb-6">
            ← {{ __('subscription.back_to_cart') }}
        </a>

        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-1">
            {{ __('subscription.replacements_title') }}
        </h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-8">
            {{ __('subscription.replacements_subtitle') }}
        </p>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 px-4 py-3 text-sm text-green-700 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Original product card --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 mb-8 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 mb-2">
                {{ __('subscription.original_product') }}
            </p>
            <div class="flex items-center justify-between gap-4">
                <span class="text-base font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ $originalProduct->title }}
                </span>
                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300">
                    €{{ number_format($originalProduct->price, 2) }}
                </span>
            </div>
            <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-500">
                {{ __('subscription.replacements_price_rule') }}
            </p>
        </div>

        {{-- Current replacements --}}
        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
            {{ __('subscription.current_replacements') }}
            <span class="ml-2 inline-flex items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 text-xs font-bold w-6 h-6">
                {{ $replacements->count() }}
            </span>
        </h2>

        @if($replacements->isEmpty())
            <p class="text-sm text-zinc-400 dark:text-zinc-500 italic mb-8">
                {{ __('subscription.no_replacements') }}
            </p>
        @else
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm mb-8">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                {{ __('cart.item') }}
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                {{ __('cart.unit_price') }}
                            </th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($replacements as $r)
                            <tr class="hover:bg-zinc-50/60 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="px-5 py-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $r->product->title }}
                                </td>
                                <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                    €{{ number_format($r->product->price, 2) }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <form method="POST"
                                          action="{{ route('subscription.replacements.destroy', [$order, $r]) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded-md hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400">
                                            {{ __('cart.remove') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Add replacement --}}
        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
            {{ __('subscription.add_replacement') }}
        </h2>

        @if($eligible->isEmpty())
            <p class="text-sm text-zinc-400 dark:text-zinc-500 italic">
                {{ __('subscription.no_eligible_replacements') }}
            </p>
        @else
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                {{ __('cart.item') }}
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                {{ __('cart.unit_price') }}
                            </th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($eligible as $product)
                            <tr class="hover:bg-zinc-50/60 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="px-5 py-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $product->title }}
                                </td>
                                <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                    €{{ number_format($product->price, 2) }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <form method="POST"
                                          action="{{ route('subscription.replacements.store', $order) }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit"
                                                class="px-3 py-1 text-xs font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                            {{ __('subscription.select_replacement') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</x-app-layout>
