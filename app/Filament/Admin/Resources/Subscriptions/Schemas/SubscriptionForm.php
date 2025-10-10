<?php

namespace App\Filament\Admin\Resources\Subscriptions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                ->schema([
                    Text::make('Make sure you know what you are doing! Changes here will not be reflected in Stripe automatically and will lead to data anomalies.')
                        ->weight(FontWeight::Bold)
                        ->color('danger'),
                ]),
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
