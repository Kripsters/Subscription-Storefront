<?php

namespace App\Filament\Admin\Resources\Prices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\FontWeight;

class PriceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                ->schema([
                    Text::make('These are only for (website) display purposes. Changes here will not be reflected in Stripe automatically and may lead to inaccurate data.')
                        ->weight(FontWeight::Bold)
                        ->color('danger'),
                ]),
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
