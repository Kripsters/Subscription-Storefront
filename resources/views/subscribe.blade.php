<x-app-layout>
<script src="https://js.stripe.com/v3/"></script>
    <body class="bg-gray-100 flex items-center justify-center h-screen">
        <div class="bg-white shadow-xl rounded-2xl p-8 w-96 text-center">
            <h1 class="text-2xl font-bold mb-4">Pro Plan</h1>
            <p class="mb-6 text-gray-600">$9.99 / month</p>
            <button id="subscribe-button" 
                class="px-6 py-3 bg-purple-600 text-white rounded-xl shadow hover:bg-purple-700">
                Subscribe Now
            </button>
        </div>

        <script>
            const stripe = Stripe("{{ env('STRIPE_KEY') }}");
            const subscribeButton = document.getElementById("subscribe-button");

            subscribeButton.addEventListener("click", () => {
                fetch("{{ route('subscription.session') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                .then(res => res.json())
                .then(data => {
                    stripe.redirectToCheckout({ sessionId: data.id });
                });
            });
        </script>
    </body>
</x-app-layout>