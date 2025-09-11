<?php

namespace App\Filament\Admin\Resources\SubscriptionOrders\Pages;

use App\Filament\Admin\Resources\SubscriptionOrders\SubscriptionOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubscriptionOrder extends CreateRecord
{
    protected static string $resource = SubscriptionOrderResource::class;
}
