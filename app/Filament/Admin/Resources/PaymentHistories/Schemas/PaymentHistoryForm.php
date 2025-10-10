<?php

namespace App\Filament\Admin\Resources\PaymentHistories\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\FontWeight;

class PaymentHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                ->schema([
                    Text::make('These should generally not be modified manually. Changes here will not be reflected in Stripe automatically and may lead to inaccurate data.')
                        ->weight(FontWeight::Bold)
                        ->color('danger'),
                ]),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('stripe_payment_intent_id'),
                TextInput::make('stripe_invoice_id'),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required(),
                TextInput::make('status')
                    ->required(),
                DateTimePicker::make('paid_at'),
                TextInput::make('raw_data'),
            ]);
    }
}
