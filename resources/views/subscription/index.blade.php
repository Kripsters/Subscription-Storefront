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
            'paused'             => 'bg-gray-500',
            'canceled'           => 'bg-gray-500',
        ];
        $color = $colors[$status] ?? 'bg-gray-500';
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
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('subscription.title') ?? 'Subscription' }}</h1>

        {{-- No subscription --}}
        @if(!$subscription)
            <div class="bg-white border rounded-xl p-6 shadow-sm">
                <p class="text-gray-500">{{ __('subscription.no_subscription') ?? 'No active subscription found.' }}</p>
                <div class="mt-4 flex gap-3 flex-wrap">
                    @if(Route::has('plans'))
                        <a href="{{ route('plans') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                            {{ __('subscription.choose_plan') ?? 'Choose a Plan' }}
                        </a>
                    @endif
                    @if(Route::has('home'))
                        <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-100 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-200">
                            {{ __('common.back_home') ?? 'Back to Home' }}
                        </a>
                    @endif
                </div>
            </div>
        @else
            {{-- Summary + Addresses --}}
            <div class="grid gap-6 md:grid-cols-3">
                <div class="bg-white border rounded-xl p-6 shadow-sm md:col-span-2">
                    <h2 class="text-lg font-semibold mb-4">{{ __('subscription.summary') ?? 'Subscription Summary' }}</h2>

                    <div class="flex items-center gap-3 mb-6">
                        {!! $statusBadge($status) !!}
                        @if($interval)
                            <span class="bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded-full text-xs font-medium uppercase">{{ $interval }}</span>
                        @endif
                    </div>

                    <dl class="divide-y divide-gray-200">
                        <div class="flex justify-between py-3">
                            <dt class="text-gray-500">{{ __('subscription.plan') ?? 'Plan' }}</dt>
                            <dd class="font-medium">{{ $planName }}</dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-gray-500">{{ __('subscription.price') ?? 'Price' }}</dt>
                            <dd class="font-medium">
                                @if($amountDisp)
                                    {{ $amountDisp }} @if($interval)/ {{ $interval }}@endif
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-gray-500">{{ __('subscription.current_period') ?? 'Current period' }}</dt>
                            <dd class="font-medium">
                                @if($periodStart && $periodEnd)
                                    {{ $periodStart }} → {{ $periodEnd }}
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-gray-500">Stripe {{ __('subscription.subscription_id') ?? 'Subscription ID' }}</dt>
                            <dd class="font-mono text-sm">{{ $subscription->stripe_subscription_id ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-gray-500">Stripe {{ __('subscription.customer_id') ?? 'Customer ID' }}</dt>
                            <dd class="font-mono text-sm">{{ $subscription->stripe_customer_id ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-gray-500">Stripe {{ __('subscription.price_id') ?? 'Price ID' }}</dt>
                            <dd class="font-mono text-sm">{{ $subscription->stripe_price_id ?? '—' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-4 flex gap-3 flex-wrap">
                        @if(Route::has('billing.portal'))
                            <a href="{{ route('billing.portal') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                                {{ __('subscription.manage_billing') ?? 'Manage Billing' }}
                            </a>
                        @endif
                        @if(Route::has('support'))
                            <a href="{{ route('support') }}" class="px-4 py-2 bg-gray-100 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-200">
                                {{ __('subscription.need_help') ?? 'Need help?' }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white border rounded-xl p-6 shadow-sm">
                        <h2 class="text-lg font-semibold mb-4">{{ __('subscription.billing_details') ?? 'Billing Details' }}</h2>
                        @if($billingBlock)
                            <pre class="whitespace-pre-line text-sm text-gray-800">{{ $billingBlock }}</pre>
                        @elseif(isset($address) && $address->billing)
                            <pre class="whitespace-pre-line text-sm text-gray-800">{{ $address->billing }}</pre>
                        @else
                            <p class="text-gray-500 italic">{{ __('subscription.billing_missing') ?? 'No billing address on file.' }}</p>
                        @endif
                    </div>

                    <div class="bg-white border rounded-xl p-6 shadow-sm">
                        <h2 class="text-lg font-semibold mb-4">{{ __('subscription.shipping_details') ?? 'Shipping Details' }}</h2>
                        @if($shippingBlock)
                            <pre class="whitespace-pre-line text-sm text-gray-800">{{ $shippingBlock }}</pre>
                        @elseif(isset($address) && $address->shipping)
                            <pre class="whitespace-pre-line text-sm text-gray-800">{{ $address->shipping }}</pre>
                        @else
                            <p class="text-gray-500 italic">{{ __('subscription.shipping_missing') ?? 'No shipping address on file.' }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Payment History --}}
            <div class="bg-white border rounded-xl p-6 shadow-sm mt-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('subscription.payments_title') ?? 'Payment History' }}</h2>

                @if(empty($payments) || (is_countable($payments) && count($payments) === 0))
                    <p class="text-gray-500 italic">{{ __('subscription.payments_empty') ?? 'No payments have been recorded yet.' }}</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr class="text-left text-gray-600">
                                    <th class="px-4 py-2">{{ __('subscription.date') ?? 'Date' }}</th>
                                    <th class="px-4 py-2">{{ __('subscription.amount') ?? 'Amount' }}</th>
                                    <th class="px-4 py-2">{{ __('subscription.status') ?? 'Status' }}</th>
                                    <th class="px-4 py-2">{{ __('subscription.invoice_id') ?? 'Invoice ID' }}</th>
                                    <th class="px-4 py-2">{{ __('subscription.payment_intent') ?? 'Payment Intent' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($payments as $p)
                                    @php
                                        $paidAt = $fmtDate($p->paid_at, 'M j, Y H:i');
                                        $amt    = $fmtMoney($p->amount, $p->currency ?? 'USD');
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-2">{{ $paidAt ?? '—' }}</td>
                                        <td class="px-4 py-2">{{ $amt ?? '—' }}</td>
                                        <td class="px-4 py-2">{!! $statusBadge($p->status ?? 'paid') !!}</td>
                                        <td class="px-4 py-2">
                                            @if(!empty($p->stripe_invoice_id))
                                                <code class="text-xs">{{ $p->stripe_invoice_id }}</code>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">
                                            @if(!empty($p->stripe_payment_intent_id))
                                                <code class="text-xs">{{ $p->stripe_payment_intent_id }}</code>
                                            @else
                                                <span class="text-gray-400">—</span>
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
</x-app-layout>
