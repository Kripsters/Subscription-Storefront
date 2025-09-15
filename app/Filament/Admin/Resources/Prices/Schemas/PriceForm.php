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
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->minvalue(0.01)
                    ->step(0.01)
                    ->required(),
                TextInput::make('currency')
                    ->required(),
                TextInput::make('lookup_key')
                    ->required(),
            ]);
    }
}
