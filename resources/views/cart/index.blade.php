<?php 
foreach($cart->items as $item) {
    unset($item->product); 
}
?>
<x-app-layout>
<script src="https://js.stripe.com/v3/"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <h1 class="text-3xl font-bold leading-tight text-gray-900 dark:text-gray-100">
    Your Cart
  </h1>
  @if($cart->items->count() > 0)
  <table class="mt-4 w-full table-auto">
    <thead>
      <tr>
        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
          Item
        </th>
        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
          Quantity
        </th>
        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
          Unit Price
        </th>
        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
          Subtotal
        </th>
        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
          Remove
        </th>
      </tr>
    </thead>
    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
      @foreach($cart->items as $item)
        <tr>
          <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">
            <span class="text-sm leading-5 font-medium text-gray-900 dark:text-gray-100">
              {{ $item->name }}
            </span>
          </td>
          <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('cart.update',$item->product_id) }}">
              @csrf @method('PATCH')
              <input type="number" name="quantity" min="0" value="{{ $item->quantity }}" class="px-1 py-1 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-300">
              <button type="submit" class="inline-flex items-center px-2 py-1 bg-gray-800 dark:bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Update
              </button>
            </form>
          </td>
          <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">
            <span class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
              {{ $item->unit_price }}
            </span>
          </td>
          <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">
            <span class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
              {{ $item->quantity * $item->unit_price }}
            </span>
          </td>
          <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('cart.remove',$item->product_id) }}">
              @csrf @method('DELETE')
              <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-600 dark:bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Remove
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <p class="mt-4 text-sm leading-5 font-medium text-lime-500 dark:text-lime-400">
    Total: {{ $cart->subtotal }}
  </p>
</div>
      @if ($cart->subtotal <= $prices[0]->price-10)
        <div class="flex justify-center">
            <div class="w-1/4 bg-white shadow-xl rounded-2xl p-8 mx-4">
                <h1 class="text-2xl font-bold mb-4">Basic Plan</h1>
                <p class="mb-6 text-gray-600">€{{$prices[0]->price}} / month</p>
                <p class="mb-6 text-gray-600">€10 is allocated for shipping</p>
                <button id="subscribe-button-basic" 
                    class="px-6 py-3 bg-purple-600 text-white rounded-xl shadow hover:bg-purple-700">
                    Subscribe Now
                </button>
            </div>
        </div>
        @elseif ($cart->subtotal > $prices[0]->price-10 && $cart->subtotal <= $prices[1]->price-10)
        <div class="flex justify-center">
            <div class="w-1/4 bg-white shadow-xl rounded-2xl p-8 mx-4">
                <h1 class="text-2xl font-bold mb-4">Medium Plan</h1>
                <p class="mb-6 text-gray-600">€{{$prices[1]->price}} / month</p>
                <p class="mb-6 text-gray-600">€10 is allocated for shipping</p>
                <button id="subscribe-button-medium" 
                    class="px-6 py-3 bg-purple-600 text-white rounded-xl shadow hover:bg-purple-700">
                    Subscribe Now
                </button>
            </div>
        </div>
        @elseif ($cart->subtotal > $prices[1]->price-10 && $cart->subtotal <= $prices[2]->price-10)
        <div class="flex justify-center">
            <div class="w-1/4 bg-white shadow-xl rounded-2xl p-8 mx-4">
                <h1 class="text-2xl font-bold mb-4">Advanced Plan</h1>
                <p class="mb-6 text-gray-600">€{{$prices[2]->price}} / month</p>
                <p class="mb-6 text-gray-600">€10 is allocated for shipping</p>
                <button id="subscribe-button-advanced" 
                    class="px-6 py-3 bg-purple-600 text-white rounded-xl shadow hover:bg-purple-700">
                    Subscribe Now
                </button>
            </div>
        </div>
        @elseif ($cart->subtotal > $prices[2]->price-10)
        <div class="flex justify-center">
          <div class="w-1/4 bg-white shadow-xl rounded-2xl p-8 mx-4">
            <h1 class="text-2xl font-bold mb-4">Too much!</h1>
            <p class="mb-6 text-gray-600">Your cart total exceeds 110€, please remove some items.</p>
          </div>
        </div>
        @endif
        <script>
            const stripe = Stripe("{{ config('services.stripe.key') }}"); 
            const subscribeButtonBasic = document.getElementById("subscribe-button-basic");
            const subscribeButtonMedium = document.getElementById("subscribe-button-medium");
            const subscribeButtonAdvanced = document.getElementById("subscribe-button-advanced");
            const cartId = <?php echo json_encode($cart->id); ?>;
            console.log(JSON.stringify({ cart: cartId }));

            if (!subscribeButtonBasic) {
                console.log("Basic button not found");
            } else {
            subscribeButtonBasic.addEventListener("click", () => {
                fetch("{{ route('subscription.session') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cart: cartId, plan: 'basic' })
                })
                .then(res => res.json())
                .then(data => {
                    stripe.redirectToCheckout({ sessionId: data.id });
                });
            });
          }  if (!subscribeButtonMedium) {
                console.log("Medium button not found");
            } else {
            subscribeButtonMedium.addEventListener("click", () => {
                fetch("{{ route('subscription.session') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cart: cartId, plan: 'medium' })
                })
                .then(res => res.json())
                .then(data => {
                    stripe.redirectToCheckout({ sessionId: data.id });
                });
            });
          }  if (!subscribeButtonAdvanced) {
                console.log("Advanced button not found");
            } else {
            subscribeButtonAdvanced.addEventListener("click", () => {
                fetch("{{ route('subscription.session') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cart: cartId, plan: 'advanced' })
                })
                .then(res => res.json())
                .then(data => {
                    stripe.redirectToCheckout({ sessionId: data.id });
                });
            });
          }
        </script>

@else
  <p class="mt-4 text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
    Your cart is empty.
  </p>
  @endif
</x-app-layout>

