<?php

namespace App\Filament\Admin\Resources\SubscriptionOrders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubscriptionOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subscription_id')
                    ->required()
                    ->numeric(),
                TextInput::make('product_id')
                    ->required()
                    ->numeric(),
                TextInput::make('product_name')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
            ]);
    }
}
