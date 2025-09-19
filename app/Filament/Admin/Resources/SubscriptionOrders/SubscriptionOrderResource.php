<?php

namespace App\Filament\Admin\Resources\SubscriptionOrders;

use App\Filament\Admin\Resources\SubscriptionOrders\Pages\CreateSubscriptionOrder;
use App\Filament\Admin\Resources\SubscriptionOrders\Pages\EditSubscriptionOrder;
use App\Filament\Admin\Resources\SubscriptionOrders\Pages\ListSubscriptionOrders;
use App\Filament\Admin\Resources\SubscriptionOrders\Schemas\SubscriptionOrderForm;
use App\Filament\Admin\Resources\SubscriptionOrders\Tables\SubscriptionOrdersTable;
use App\Models\SubscriptionOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SubscriptionOrderResource extends Resource
{
    protected static ?string $model = SubscriptionOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'product_name';

    public static function form(Schema $schema): Schema
    {
        return SubscriptionOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubscriptionOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubscriptionOrders::route('/'),
            'create' => CreateSubscriptionOrder::route('/create'),
            'edit' => EditSubscriptionOrder::route('/{record}/edit'),
        ];
    }
}
