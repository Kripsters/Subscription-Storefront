<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\FontWeight;

class SubscriptionsOverview extends BaseWidget
{

    protected function getCards(): array
    {
        return [];
    }
}