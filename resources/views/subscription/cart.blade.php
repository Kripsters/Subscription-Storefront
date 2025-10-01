<x-app-layout>
    @php
        // Currency symbol (adjust as needed)
        $currency = '€';

        // Derive subtotal from the products your controller passes in
        $subtotal = collect($existingItems)->sum(function ($p) {
            return $p->price ?? 0;
        });

        $subtotal2 = collect($subcart)->sum(function ($p) {
            return $p->price ?? 0;
        })
    @endphp

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

        <h2 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">{{ __('cart.subcart') }}</h2>
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

        

        <h2 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 mt-24">{{ __('cart.subcart-new') }}</h2>

        @if(collect($subcart)->count() > 0)


        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <table class="w-full text-left">
          
              <!-- Header: hidden on mobile, shown on md+ -->
              <thead class="hidden md:table-header-group bg-zinc-50 dark:bg-zinc-700/50">
                <tr>
                  <th class="px-6 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-300 uppercase">{{ __('cart.item') }}</th>
                  <th class="px-6 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-300 uppercase">{{ __('cart.quantity') }}</th>
                  <th class="px-6 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-300 uppercase">{{ __('cart.unit_price') }}</th>
                  <th class="px-6 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-300 uppercase">{{ __('cart.subtotal') }}</th>
                  <th class="px-6 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-300 uppercase">{{ __('cart.remove') }}</th>
                </tr>
              </thead>
          
              <!-- Body -->
              <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 md:divide-y">
                @foreach($subcart as $item)
                <!-- Each row becomes a “card” on mobile -->
                <tr class="block md:table-row">
                  <!-- Card wrapper (mobile only) -->
                  <td colspan="5" class="md:hidden px-4 pt-4">
                    <div class="rounded-lg ring-1 ring-zinc-200 dark:ring-zinc-700 bg-white dark:bg-zinc-800">
                      <!-- We'll fill this same row’s cells below; this <td> simply wraps the stacked layout. -->
                    </div>
                  </td>
          
                  <!-- ITEM (mobile: label + value) -->
                  <td class="block md:table-cell px-4 py-3 md:px-6 md:py-4 align-top">
                    <span class="md:hidden block text-xs font-semibold uppercase text-zinc-500 dark:text-zinc-300">{{ __('cart.item') }}</span>
                    <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                      {{ $item->title }}
                    </div>
                  </td>
          
                  <!-- QUANTITY -->
                  <td class="block md:table-cell px-4 py-3 md:px-6 md:py-4 align-top">
                    <span class="md:hidden block text-xs font-semibold uppercase text-zinc-500 dark:text-zinc-300 mb-1">{{ __('cart.quantity') }}</span>
                    <form method="POST" action="{{ route('subcart.update', ['id' => $item->id]) }}" class="flex items-center gap-2">
                      @csrf @method('PATCH')
                      <input
                        type="number" name="quantity" min="0" step="1" value="{{ $item->quantity }}"
                        class="w-20 rounded-md border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 shadow-sm
                               focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm text-zinc-900 dark:text-zinc-100"
                        inputmode="numeric" />
                      <button type="submit"
                        class="px-3 py-1 bg-indigo-600 text-white text-xs font-semibold rounded-md
                               hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                               {{ __('cart.updates') }}
                      </button>
                    </form>
                  </td>
          
                  <!-- UNIT PRICE -->
                  <td class="block md:table-cell px-4 py-3 md:px-6 md:py-4 align-top">
                    <span class="md:hidden block text-xs font-semibold uppercase text-zinc-500 dark:text-zinc-300">{{ __('cart.unit_price') }}</span>
                    <div class="text-sm text-zinc-600 dark:text-zinc-400">
                      €{{ number_format($item->price, 2) }}
                    </div>
                  </td>
          
                  <!-- SUBTOTAL -->
                  <td class="block md:table-cell px-4 py-3 md:px-6 md:py-4 align-top">
                    <span class="md:hidden block text-xs font-semibold uppercase text-zinc-500 dark:text-zinc-300">{{ __('cart.subtotal') }}</span>
                    <div class="text-sm text-zinc-600 dark:text-zinc-400">
                      €{{ number_format($item->quantity * $item->price, 2) }}
                    </div>
                  </td>
          
                  <!-- REMOVE -->
                  <td class="block md:table-cell px-4 pb-4 md:px-6 md:py-4 align-top">
                    <span class="md:hidden block text-xs font-semibold uppercase text-zinc-500 dark:text-zinc-300 mb-1">{{ __('cart.remove') }}</span>
                    <form method="POST" action="{{ route('subcart.remove', $item->id) }}">
                      @csrf @method('DELETE')
                      <button type="submit"
                        class="px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded-md
                               hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400">
                               {{ __('cart.remove') }}
                      </button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        
          <div class="mt-20"> </div>
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
                    {{ __('cart.empty') ?? 'Browse products and add them to your subscription.' }}
                </p>
            </div>
        @endif







    </div>
</x-app-layout>