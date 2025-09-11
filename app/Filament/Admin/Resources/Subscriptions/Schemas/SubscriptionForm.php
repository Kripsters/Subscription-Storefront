<?php

namespace App\Filament\Admin\Resources\Subscriptions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('stripe_customer_id')
                    ->required(),
                TextInput::make('stripe_subscription_id'),
                TextInput::make('stripe_price_id'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                TextInput::make('billing_name'),
                TextInput::make('billing_email')
                    ->email(),
                TextInput::make('billing_address'),
                TextInput::make('shipping_address'),
            ]);
    }
}
