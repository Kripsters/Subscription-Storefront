<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('title')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('category')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('image')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
