<?php

namespace App\Filament\Admin\Resources\Addresses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('billing')
                    ->required(),
                TextInput::make('shipping')
                    ->required(),
            ]);
    }
}
