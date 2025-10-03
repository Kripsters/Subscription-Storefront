<?php 
foreach($cart->items as $item) {
    unset($item->product); 
}
?>
<x-app-layout>
<script src="https://js.stripe.com/v3/"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  
  <!-- Title -->
  <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100 mb-6">
    {{ __('cart.title') }}
  </h1>

  @if($cart->items->count() > 0)
  <!-- Table -->
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
        @foreach($cart->items as $item)
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
              {{ $item->name }}
            </div>
          </td>
  
          <!-- QUANTITY -->
          <td class="block md:table-cell px-4 py-3 md:px-6 md:py-4 align-top">
            <span class="md:hidden block text-xs font-semibold uppercase text-zinc-500 dark:text-zinc-300 mb-1">{{ __('cart.quantity') }}</span>
            <form method="POST" action="{{ route('cart.update', $item->product_id) }}" class="flex items-center gap-2">
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
              €{{ number_format($item->unit_price, 2) }}
            </div>
          </td>
  
          <!-- SUBTOTAL -->
          <td class="block md:table-cell px-4 py-3 md:px-6 md:py-4 align-top">
            <span class="md:hidden block text-xs font-semibold uppercase text-zinc-500 dark:text-zinc-300">{{ __('cart.subtotal') }}</span>
            <div class="text-sm text-zinc-600 dark:text-zinc-400">
              €{{ number_format($item->quantity * $item->unit_price, 2) }}
            </div>
          </td>
  
          <!-- REMOVE -->
          <td class="block md:table-cell px-4 pb-4 md:px-6 md:py-4 align-top">
            <span class="md:hidden block text-xs font-semibold uppercase text-zinc-500 dark:text-zinc-300 mb-1">{{ __('cart.remove') }}</span>
            <form method="POST" action="{{ route('cart.remove', $item->product_id) }}">
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
  
  
  <!-- Total -->
  <p class="mt-6 text-lg font-semibold text-lime-600">
    {{ __('cart.total') }}: €{{ $cart->subtotal }}
  </p>


  <div class="mt-10 flex justify-center">
  @if ($allowed) <!-- If user has no active subscription -->
          <!-- Subscription Plans -->

            @if ($cart->subtotal <= $prices[0]->price - 10)
                <x-subscription-card 
                    :title="__('cart.basic_title')" 
                    :price="$prices[0]->price" 
                    :subtext="__('cart.basic_subtext')" 
                    id="basic" 
                    :error="false" />
            @elseif ($cart->subtotal > $prices[0]->price - 10 && $cart->subtotal <= $prices[1]->price - 10)
                <x-subscription-card 
                    :title="__('cart.medium_title')" 
                    :price="$prices[1]->price" 
                    :subtext="__('cart.medium_subtext')" 
                    id="medium" 
                    :error="false" />
            @elseif ($cart->subtotal > $prices[1]->price - 10 && $cart->subtotal <= $prices[2]->price - 10)
                <x-subscription-card 
                    :title="__('cart.advanced_title')" 
                    :price="$prices[2]->price" 
                    :subtext="__('cart.advanced_subtext')" 
                    id="advanced" 
                    :error="false" />
            @elseif ($cart->subtotal > $prices[2]->price - 10)
                <x-subscription-card 
                    :title="__('cart.total_exceeded')" 
                    :price="null" 
                    :subtext="__('cart.total_exceeded_subtext')" 
                    id="exceeded"
                    :error="true" />
            @endif

            @else
            <x-subscription-card 
            :title="__('cart.existing_subscription')" 
            :price="null" 
            :subtext="__('cart.existing_subscription_subtext')" 
            id="exceeded" 
            :error="true" />
    @endif
          </div>
  <!-- Stripe Checkout -->
  
<script>
  const stripe = Stripe("{{ config('services.stripe.key') }}");
  const cartId = @json($cart->id);

  async function createCheckoutSession(plan) {
    const res = await fetch("{{ route('subscription.session') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify({ cart: cartId, plan })
    });

    const data = await res.json();
    if (!res.ok || !data?.id) {
      throw new Error(data?.error || "Unable to create Stripe session.");
    }
    return data.id;
  }

  function wireSubscribeButtons() {
    // Use data attributes to avoid hard-coding multiple IDs
    document.querySelectorAll("[data-subscribe][data-plan]").forEach(btn => {
      btn.addEventListener("click", async () => {
        btn.disabled = true;
        btn.classList.add("opacity-70", "cursor-not-allowed");

        try {
          const sessionId = await createCheckoutSession(btn.dataset.plan);
          const { error } = await stripe.redirectToCheckout({ sessionId });
          if (error) throw error;
        } catch (err) {
          console.error(err);
          alert(err.message || "Checkout failed. Please try again.");
        } finally {
          btn.disabled = false;
          btn.classList.remove("opacity-70", "cursor-not-allowed");
        }
      });
    });
  }

  document.addEventListener("DOMContentLoaded", wireSubscribeButtons);
</script>


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
