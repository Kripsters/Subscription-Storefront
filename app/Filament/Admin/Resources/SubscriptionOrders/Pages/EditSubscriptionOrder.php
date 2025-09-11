<?php

namespace App\Filament\Admin\Resources\SubscriptionOrders\Pages;

use App\Filament\Admin\Resources\SubscriptionOrders\SubscriptionOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubscriptionOrder extends EditRecord
{
    protected static string $resource = SubscriptionOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
