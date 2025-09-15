<?php

namespace App\Filament\Admin\Resources\Subscriptions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SubscriptionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('stripe_customer_id'),
                TextEntry::make('stripe_subscription_id'),
                TextEntry::make('stripe_price_id'),
                TextEntry::make('status'),
                TextEntry::make('billing_name'),
                TextEntry::make('billing_email'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
