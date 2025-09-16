<?php

namespace App\Filament\Admin\Resources\PaymentHistories\Pages;

use App\Filament\Admin\Resources\PaymentHistories\PaymentHistoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPaymentHistory extends EditRecord
{
    protected static string $resource = PaymentHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
