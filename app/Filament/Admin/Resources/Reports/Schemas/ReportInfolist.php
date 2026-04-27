<?php

namespace App\Filament\Admin\Resources\Reports\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('Submitted By'),
                TextEntry::make('user.email')
                    ->label('User Email'),
                TextEntry::make('title'),
                TextEntry::make('description'),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'reviewing' => 'info',
                        'done'      => 'success',
                        'closed'    => 'gray',
                        default     => 'gray',
                    }),
                TextEntry::make('admin_comment')
                    ->label('Admin Comment')
                    ->placeholder('No comment yet'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
