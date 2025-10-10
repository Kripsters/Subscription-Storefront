<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Schemas\Components\Section;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\FontWeight;

class SubscriptionsOverview extends BaseWidget
{

    protected function getCards(): array
    {
        // Raw DB sum using Eloquent (single SQL SUM)
        $total = Subscription::query()->sum('amount');

        // If your amounts are stored as integer cents, convert:
        // $displayValue = number_format($total / 100, 2);
        // Otherwise (decimal stored), format directly:
        $displayValue = number_format((float)$total, 2);

        return [
            Section::make('Earnings Overview')
            ->schema([
                Text::make('Projected Monthly Recurring Revenue based on current subscriptions:')
                    ->weight(FontWeight::Bold)
                    ->color('neutral'),
                    Text::make($displayValue ? '€' . $displayValue : '€0.00')
                    ->weight(FontWeight::Bold)
                    ->color('success'),
            ]),
        ];
    }
}