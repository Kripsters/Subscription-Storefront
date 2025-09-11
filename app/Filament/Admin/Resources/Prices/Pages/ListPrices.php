<?php

namespace App\Filament\Admin\Resources\Prices\Pages;

use App\Filament\Admin\Resources\Prices\PriceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrices extends ListRecords
{
    protected static string $resource = PriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
