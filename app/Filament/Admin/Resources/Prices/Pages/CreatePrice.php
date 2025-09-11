<?php

namespace App\Filament\Admin\Resources\Prices\Pages;

use App\Filament\Admin\Resources\Prices\PriceResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrice extends CreateRecord
{
    protected static string $resource = PriceResource::class;
}
