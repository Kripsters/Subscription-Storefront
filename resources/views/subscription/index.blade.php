{{-- resources/views/subscription/index.blade.php --}}
@php
    use Carbon\Carbon;

    // Helpers
    $fmtDate = function ($v, $format = 'M j, Y') {
        if (empty($v)) return null;
        try {
            if ($v instanceof \DateTimeInterface) return Carbon::instance($v)->format($format);
            if (is_numeric($v)) return Carbon::createFromTimestamp((int)$v)->format($format);
            return Carbon::parse($v)->format($format);
        } catch (\Throwable $e) { return (string) $v; }
    };

    $fmtMoney = fn($amount, $currency = 'USD') =>
        $amount === null || $amount === '' ? null : number_format((float) $amount, 2) . ' ' . strtoupper($currency ?? 'USD');

    $statusBadge = function ($status) {
        $status = strtolower((string) $status);
        $colors = [
            'active'             => 'bg-green-600',
            'trialing'           => 'bg-blue-700',
            'incomplete'         => 'bg-amber-600',
            'incomplete_expired' => 'bg-red-700',
            'past_due'           => 'bg-red-600',
            'unpaid'             => 'bg-red-700',
            'paused'             => 'bg-zinc-500',
            'canceled'           => 'bg-zinc-500',
        ];
        $color = $colors[$status] ?? 'bg-zinc-500';
        return "<span class=\"px-2 py-0.5 rounded-full text-xs font-semibold text-white uppercase tracking-wide {$color}\">{$status}</span>";
    };

    $linesFromAddress = function ($addr, $name = null, $email = null) {
        if (!is_array($addr) || empty($addr)) return null;
        $lines = array_filter([
            $name,
            $addr['line1'] ?? null,
            $addr['line2'] ?? null,
            trim(
                trim(($addr['city'] ?? ''))
                . (isset($addr['state']) && $addr['state'] ? ', ' . $addr['state'] : '')
                . ' ' . ($addr['postal_code'] ?? '')
            ),
            $addr['country'] ?? null,
            $email,
        ], fn($x) => (string) $x !== '');
        return !empty($lines) ? implode("\n", $lines) : null;
    };

    // Subscription fields
    $planName   = $subscription->plan_name ?? 'Subscription';
    $amountDisp = $fmtMoney($subscription->amount ?? null, $subscription->currency ?? 'USD');
    $interval   = $subscription->interval ?? null;
    $status     = $subscription->status ?? 'unknown';

    $periodStart = $fmtDate($subscription->current_period_start ?? null);
    $periodEnd   = $fmtDate($subscription->current_period_end ?? null);

    $billingBlock = isset($billing_address)
        ? $linesFromAddress($billing_address, $subscription->billing_name ?? null, $subscription->billing_email ?? null)
        : null;

    $shippingBlock = isset($shipping_address)
        ? $linesFromAddress($shipping_address, $subscription->billing_name ?? null, null)
        : null;
@endphp

<x-app-layout>

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

    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-zinc-900 dark:text-zinc-100">
            {{ __('subscription.title') ?? 'Subscription' }}
        </h1>

        {{-- No subscription --}}
        @if(!$subscription)
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                <p class="text-zinc-500 dark:text-zinc-400">
                    {{ __('subscription.no_subscription') ?? 'No active subscription found.' }}
                </p>
                <div class="mt-4 flex gap-3 flex-wrap">
                    @if(Route::has('plans'))
                        <a href="{{ route('plans') }}"
                           class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                            {{ __('subscription.choose_plan') ?? 'Choose a Plan' }}
                        </a>
                    @endif
                    @if(Route::has('home'))
                        <a href="{{ route('home') }}"
                           class="px-4 py-2 bg-zinc-100 dark:bg-zinc-700 dark:text-zinc-200 text-zinc-800 text-sm font-medium rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-600">
                            {{ __('common.back_home') ?? 'Back to Home' }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="py-8 sm:py-44"></div>
        @else
            {{-- Summary + Addresses --}}
            <div class="grid gap-6 md:grid-cols-3">
                <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm md:col-span-2">
                    <h2 class="text-lg font-semibold mb-4 text-zinc-900 dark:text-zinc-100">
                        {{ __('subscription.title') ?? 'Subscription Summary' }}
                    </h2>

                    <div class="flex items-center gap-3 mb-6">
                        {!! $statusBadge($status) !!}
                        @if($interval)
                            <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-2 py-0.5 rounded-full text-xs font-medium uppercase">
                                {{ $interval }}
                            </span>
                        @endif
                    </div>

                    <dl class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        <div class="flex justify-between py-3">
                            <dt class="text-zinc-500 dark:text-zinc-400">{{ __('subscription.plan') ?? 'Plan' }}</dt>
                            <dd class="font-medium text-zinc-900 dark:text-zinc-100">{{ $planName }}</dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-zinc-500 dark:text-zinc-400">{{ __('subscription.price') ?? 'Price' }}</dt>
                            <dd class="font-medium text-zinc-900 dark:text-zinc-100">
                                @if($amountDisp)
                                    {{ $amountDisp }} @if($interval)/ {{ $interval }}@endif
                                @else
                                    <span class="text-zinc-400">—</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-zinc-500 dark:text-zinc-400">{{ __('subscription.current_period') ?? 'Current period' }}</dt>
                            <dd class="font-medium text-zinc-900 dark:text-zinc-100">
                                @if($periodStart && $periodEnd)
                                    {{ $periodStart }} → {{ $periodEnd }}
                                @else
                                    <span class="text-zinc-400">—</span>
                                @endif
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-4 flex gap-3 flex-wrap">
                        @if(Route::has('billing.portal'))
                            <a href="{{ route('billing.portal') }}"
                               class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                                {{ __('subscription.manage_billing') ?? 'Manage Billing' }}
                            </a>
                        @endif
                        @if(Route::has('support'))
                            <a href="{{ route('support') }}"
                               class="px-4 py-2 bg-zinc-100 dark:bg-zinc-700 dark:text-zinc-200 text-zinc-800 text-sm font-medium rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-600">
                                {{ __('subscription.need_help') ?? 'Need help?' }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                        <h2 class="text-lg font-semibold mb-4 text-zinc-900 dark:text-zinc-100">
                            {{ __('subscription.billing_details') ?? 'Billing Details' }}
                        </h2>
                        @if($billingBlock)
                            <pre class="whitespace-pre-line text-sm text-zinc-800 dark:text-zinc-200">{{ $billingBlock }}</pre>
                        @elseif(isset($address) && $address->billing)
                            <pre class="whitespace-pre-line text-sm text-zinc-800 dark:text-zinc-200">{{ $address->billing }}</pre>
                        @else
                            <p class="text-zinc-500 dark:text-zinc-400 italic">{{ __('subscription.billing_missing') ?? 'No billing address on file.' }}</p>
                        @endif
                    </div>

                    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                        <h2 class="text-lg font-semibold mb-4 text-zinc-900 dark:text-zinc-100">
                            {{ __('subscription.shipping_details') ?? 'Shipping Details' }}
                        </h2>
                        @if($shippingBlock)
                            <pre class="whitespace-pre-line text-sm text-zinc-800 dark:text-zinc-200">{{ $shippingBlock }}</pre>
                        @elseif(isset($address) && $address->shipping && empty($address->shipping))
                            <pre class="whitespace-pre-line text-sm text-zinc-800 dark:text-zinc-200">{{ $address->shipping }}</pre>
                        @else
                            <p class="text-zinc-500 dark:text-zinc-400 italic">{{ __('subscription.shipping_address_none') ?? 'No shipping address on file.' }}</p>
                        @endif
                    </div>
                </div>
            </div>

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

            {{-- Payment History --}}
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm mt-6">
                <h2 class="text-lg font-semibold mb-4 text-zinc-900 dark:text-zinc-100">
                    {{ __('subscription.payments_title') ?? 'Payment History' }}
                </h2>

                @if(empty($payments) || (is_countable($payments) && count($payments) === 0))
                    <p class="text-zinc-500 dark:text-zinc-400 italic">
                        {{ __('subscription.payments_empty') ?? 'No payments have been recorded yet.' }}
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-zinc-200 dark:border-zinc-700 rounded-lg">
                            <thead class="bg-zinc-50 dark:bg-zinc-700">
                                <tr class="text-left text-zinc-600 dark:text-zinc-300">
                                    <th class="px-4 py-2">{{ __('subscription.date') ?? 'Date' }}</th>
                                    <th class="px-4 py-2">{{ __('subscription.amount') ?? 'Amount' }}</th>
                                    <th class="px-4 py-2">{{ __('subscription.status') ?? 'Status' }}</th>
                                    <th class="px-4 py-2">{{ __('subscription.invoice_id') ?? 'Invoice ID' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                @foreach($payments as $p)
                                    @php
                                        $paidAt = $fmtDate($p->paid_at, 'M j, Y H:i');
                                        $amt    = $fmtMoney($p->amount, $p->currency ?? 'USD');
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-2 text-zinc-800 dark:text-zinc-200">{{ $paidAt ?? '—' }}</td>
                                        <td class="px-4 py-2 text-zinc-800 dark:text-zinc-200">{{ $amt ?? '—' }}</td>
                                        <td class="px-4 py-2">{!! $statusBadge($p->status ?? 'paid') !!}</td>
                                        <td class="px-4 py-2">
                                            @if(!empty($p->stripe_invoice_id))
                                                <code class="text-xs text-zinc-700 dark:text-zinc-300">{{ $p->stripe_invoice_id }}</code>
                                            @else
                                                <span class="text-zinc-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif
    </div>

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
