<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;

class SubscriptionUsersWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.subscription-users-list';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $users = User::query()
            ->with([
                'subscriptions.orders.product',
                'subscriptions.orders.replacements.product',
                'address',
            ])
            ->whereHas('subscriptions')
            ->get()
            ->map(function (User $user) {
                $orders = $user->subscriptions->flatMap(fn ($s) => $s->orders);

                $products = $orders
                    ->map(fn ($o) => $o->product?->title ?? $o->product_name)
                    ->filter()
                    ->unique()
                    ->values();

                $replacements = $orders
                    ->flatMap(fn ($o) => $o->replacements)
                    ->map(fn ($r) => $r->product?->title)
                    ->filter()
                    ->unique()
                    ->values();

                $shipping = null;
                $shippingIsBilling = false;

                $formatAddress = function (array $a): ?string {
                    $parts = array_filter([
                        $a['line1'] ?? null,
                        $a['line2'] ?? null,
                        $a['city'] ?? null,
                        $a['state'] ?? null,
                        $a['postal_code'] ?? null,
                        $a['country'] ?? null,
                    ]);
                    return $parts ? implode(', ', $parts) : null;
                };

                if ($user->address) {
                    if (! empty($user->address->shipping)) {
                        $shipping = $formatAddress($user->address->shipping);
                    } elseif (! empty($user->address->billing)) {
                        $shipping = $formatAddress($user->address->billing);
                        $shippingIsBilling = true;
                    }
                }

                return [
                    'name'               => $user->name,
                    'email'              => $user->email,
                    'products'           => $products,
                    'shipping'           => $shipping,
                    'shipping_is_billing' => $shippingIsBilling,
                    'replacements'       => $replacements,
                ];
            });

        return ['users' => $users];
    }
}
