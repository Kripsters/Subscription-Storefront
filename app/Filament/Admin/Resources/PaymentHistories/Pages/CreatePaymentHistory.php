<?php

namespace App\Filament\Admin\Resources\PaymentHistories\Pages;

use App\Filament\Admin\Resources\PaymentHistories\PaymentHistoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentHistory extends CreateRecord
{
    protected static string $resource = PaymentHistoryResource::class;
}
