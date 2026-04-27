<?php

namespace App\Filament\Admin\Resources\Reports\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->disabled(),
                Textarea::make('description')
                    ->disabled()
                    ->rows(4),
                Select::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'reviewing' => 'Reviewing',
                        'done'      => 'Done',
                        'closed'    => 'Closed',
                    ])
                    ->required(),
                Textarea::make('admin_comment')
                    ->label('Admin Comment')
                    ->rows(4)
                    ->nullable(),
            ]);
    }
}
