<?php

namespace App\Filament\Admin\Resources\Prices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PriceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('plan')
                    ->required(),
                TextInput::make('price')
                    ->required(),
                TextInput::make('currency')
                    ->required(),
                TextInput::make('lookup_key')
                    ->required(),
            ]);
    }
}
