<?php

namespace App\Filament\Admin\Resources\SubscriptionOrders\Pages;

use App\Filament\Admin\Resources\SubscriptionOrders\SubscriptionOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubscriptionOrders extends ListRecords
{
    protected static string $resource = SubscriptionOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
