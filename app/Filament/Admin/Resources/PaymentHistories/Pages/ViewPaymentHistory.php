<?php

namespace App\Filament\Admin\Resources\PaymentHistories\Pages;

use App\Filament\Admin\Resources\PaymentHistories\PaymentHistoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentHistory extends ViewRecord
{
    protected static string $resource = PaymentHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
