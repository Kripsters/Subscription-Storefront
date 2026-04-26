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
                if ($user->address && ! empty($user->address->shipping)) {
                    $s = $user->address->shipping;
                    $parts = array_filter([
                        $s['line1'] ?? null,
                        $s['line2'] ?? null,
                        $s['city'] ?? null,
                        $s['state'] ?? null,
                        $s['postal_code'] ?? null,
                        $s['country'] ?? null,
                    ]);
                    $shipping = implode(', ', $parts);
                }

                return [
                    'name'         => $user->name,
                    'email'        => $user->email,
                    'products'     => $products,
                    'shipping'     => $shipping,
                    'replacements' => $replacements,
                ];
            });

        return ['users' => $users];
    }
}
