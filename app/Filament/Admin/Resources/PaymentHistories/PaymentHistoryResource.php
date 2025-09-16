<?php

namespace App\Filament\Admin\Resources\PaymentHistories;

use App\Filament\Admin\Resources\PaymentHistories\Pages\CreatePaymentHistory;
use App\Filament\Admin\Resources\PaymentHistories\Pages\EditPaymentHistory;
use App\Filament\Admin\Resources\PaymentHistories\Pages\ListPaymentHistories;
use App\Filament\Admin\Resources\PaymentHistories\Pages\ViewPaymentHistory;
use App\Filament\Admin\Resources\PaymentHistories\Schemas\PaymentHistoryForm;
use App\Filament\Admin\Resources\PaymentHistories\Schemas\PaymentHistoryInfolist;
use App\Filament\Admin\Resources\PaymentHistories\Tables\PaymentHistoriesTable;
use App\Models\PaymentHistory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaymentHistoryResource extends Resource
{
    protected static ?string $model = PaymentHistory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'PaymentHistory';

    public static function form(Schema $schema): Schema
    {
        return PaymentHistoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentHistoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentHistoriesTable::configure($table);
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
            'index' => ListPaymentHistories::route('/'),
            'create' => CreatePaymentHistory::route('/create'),
            'view' => ViewPaymentHistory::route('/{record}'),
            'edit' => EditPaymentHistory::route('/{record}/edit'),
        ];
    }
}
