<?php

namespace App\Filament\Admin\Resources\Prices\Pages;

use App\Filament\Admin\Resources\Prices\PriceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPrice extends EditRecord
{
    protected static string $resource = PriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
