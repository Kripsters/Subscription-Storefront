<?php unset($cart->items[0]->product); ?>
<?=($cart->items[0]); ?>
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
        <div class="bg-white shadow-xl rounded-2xl p-8 w-96 text-center mx-auto">
            <h1 class="text-2xl font-bold mb-4">Basic Plan</h1>
            <p class="mb-6 text-gray-600">€40.00 / month</p>
            <button id="subscribe-button" 
                class="px-6 py-3 bg-purple-600 text-white rounded-xl shadow hover:bg-purple-700">
                Subscribe Now
            </button>
        </div>

        <script>
            const stripe = Stripe("{{ env('STRIPE_KEY') }}");
            const subscribeButton = document.getElementById("subscribe-button");
            const cartItems = <?php echo json_encode($cart->items); ?>;

            subscribeButton.addEventListener("click", () => {
                fetch("{{ route('subscription.session') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cart: cartItems })
                })
                .then(res => res.json())
                .then(data => {
                    stripe.redirectToCheckout({ sessionId: data.id });
                });
            });
        </script>

@else
  <p class="mt-4 text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
    Your cart is empty.
  </p>
  @endif
</x-app-layout>
