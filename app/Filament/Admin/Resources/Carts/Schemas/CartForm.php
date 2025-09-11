<?php

namespace App\Filament\Admin\Resources\Carts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                Select::make('status')
                    ->options(['active' => 'Active', 'converted' => 'Converted', 'abandoned' => 'Abandoned'])
                    ->default('active')
                    ->required(),
            ]);
    }
}
