<?php 
if (isset($subscription->billing_address)) {
$billing_address = json_decode($subscription->billing_address, true);
$shipping_address = json_decode($subscription->shipping_address, true);

}
?>
<x-app-layout>
    <!-- Subscription info div -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 dark:text-gray-100">
            {{ __('subscription.title') }}
        </h1>
        @if (!$subscription) <!-- If no subscription, display some simple text -->

            <p class="mt-4 text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                {{ __('subscription.no_subscription') }}
            </p>

        @else <!-- Else, display the subscription details -->

            <div class="mt-4 p-6 bg-zinc-100 hover:bg-zinc-50 rounded-lg border border-zinc-200 shadow  dark:bg-zinc-900 dark:border-zinc-700 dark:hover:bg-zinc-800">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
                    {{ __('subscription.status_title') }} 

                    <!-- Show subscription status -->
                    @if ($subscription->status === 'active')
                        <span class="text-green-600 dark:text-green-400">{{ __('subscription.status_active') }}</span>
                    @else
                        <span class="text-red-600 dark:text-red-400">{{ __('subscription.status_inactive') }}</span>
                    @endif

                </h5>
                <p class="font-normal text-zinc-700 dark:text-zinc-300">
                    {{ __('subscription.billing_name') }} {{ $subscription->billing_name }}
                </p>
                <p class="font-normal text-zinc-700 dark:text-zinc-300">
                    {{ __('subscription.billing_email') }} {{ $subscription->billing_email }}
                </p>
                <p class="font-normal text-zinc-700 dark:text-zinc-300">
                    {{ __('subscription.billing_address') }} {{ $billing_address['line1'] }}, {{ $billing_address['city'] }}, {{ $billing_address['state'] }}, {{ $billing_address['postal_code'] }}, {{ $billing_address['country'] }}
                </p>
                <p class="font-normal text-zinc-700 dark:text-zinc-300">
                    {{ __('subscription.shipping_address') }} 

                    @if ($shipping_address)  
                    <!-- If shipping address is set, display it, 
                        else display "Shipping address is same as billing address" -->
                    {{ $shipping_address['line1'] }}, {{ $shipping_address['city'] }}, {{ $shipping_address['state'] }}, {{ $shipping_address['postal_code'] }}, {{ $shipping_address['country'] }} 
                    @else 
                    {{ __('subscription.shipping_address_none') }}
                    @endif

                </p>
            </div>

        @endif <!-- End of subscription if -->
    </div> <!-- End of subscription info div -->
        


                <!-- Confirmation Modal -->
        <div id="confirmation-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden backdrop-blur-md">
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4" id="modal-title">{{ __('subscription.confirm_action') }}</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-6" id="modal-message">{{ __('subscription.confirm_message') }}</p>

                <div class="flex justify-end gap-3">
                    <button id="cancel-modal"
                        class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-zinc-600">
                        {{ __('subscription.cancel') }}
                    </button>
                    <button id="confirm-modal"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        {{ __('subscription.confirm') }}
                    </button>
                </div>
            </div>
        </div> <!-- End of confirmation modal -->



        <!-- Subscription actions -->
        <div class="mt-6 flex items-center justify-center gap-4">
            @if($subscription) <!-- Only display these if a subscription exists -->
                @if($subscription->status === 'paused')
                    <form method="POST" action="{{ route('subscription.resume') }}">
                        @csrf
                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                        data-action={{ __('subscription.resume') }}
                        data-message='{{ __('subscription.resume_message') }}'>
                            {{ __('subscription.resume') }}
                        </button>
                    </form>
                @elseif ($subscription->status === 'active')
                    <form method="POST" action="{{ route('subscription.pause') }}">
                        @csrf
                        <button class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700"
                        data-action={{ __('subscription.pause') }}
                        data-message='{{ __('subscription.pause_message') }}'>
                        {{ __('subscription.pause') }}
                        </button>
                    </form>
                @endif
                @if ($subscription->status === 'canceled') <!-- If subscription is canceled, show no actions -->
                @else <!-- If subscription is active or paused, show cancel action -->
                <form method="POST" action="{{ route('subscription.cancel') }}">
                    @csrf
                    <button type="button"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                        data-action={{ __('subscription.cancel') }}
                        data-message='{{ __('subscription.cancel_message') }}'>
                        {{ __('subscription.cancel') }}
                    </button>
                </form>
                @endif
            @endif
        </div> <!-- End of subscription actions -->


        
        <!-- Payment history -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 dark:bg-zinc-900">
            <h2 class="text-2xl font-bold mb-6 dark:text-white">{{ __('subscription.payments_title') }}</h2>

            <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"> <!-- Start of table -->
                    <thead class="bg-gray-50 dark:bg-zinc-800">
                        <tr>

                            <!-- Table headers:  Date/Amount/Status/Plan -->
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-white">{{ __('subscription.date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-white">{{ __('subscription.amount') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-white">{{ __('subscription.status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-white">{{ __('subscription.plan') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-zinc-900">
                        @if (!$payments) <!-- If no payments exist, show a message. -->
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center dark:text-white">
                                    {{ __('subscription.payments_empty') }}
                                </td>
                            </tr>
                        @elseif ($payments) <!-- If payments exist, display them -->
                            @foreach ($payments as $payment) <!-- Loop through payments -->
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800 border-b border-gray-200 dark:border-zinc-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-white">
                                    {{ $payment->paid_at? \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-white">
                                    {{ number_format($payment->amount, 2) .'  '. $payment->currency}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $payment->status === 'paid' ? __('subscription.paid') : __('subscription.unpaid') }} 
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-white">
                                    {{ $payment->plan_name ?? '—' }}
                                </td>
                            </tr>
                            @endforeach <!-- End of loop -->
                        @endif <!-- End of payment if -->
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Confirmation Modal javascript -->
        <script>
            // Get DOM elements
            const modal = document.getElementById('confirmation-modal');
            const confirmBtn = document.getElementById('confirm-modal');
            const cancelBtn = document.getElementById('cancel-modal');
            const modalMessage = document.getElementById('modal-message');
        
            // Remember which form is open
            let currentForm = null;
        
            // Attach event listeners to all action buttons
            document.querySelectorAll('form button[data-action]').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault(); // stop form from submitting immediately
                    currentForm = button.closest('form'); // remember which form
                    modalMessage.textContent = button.dataset.message; // set message
                    modal.classList.remove('hidden'); // show modal
                });
            });
        
            // Confirm -> submit form
            confirmBtn.addEventListener('click', () => {
                if (currentForm) {
                    currentForm.submit();
                }
            });
        
            // Cancel -> hide modal
            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                currentForm = null;
            });
        
            // Close modal on outside click (awesome!)
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    currentForm = null;
                }
            });
        </script>
        

</x-app-layout>