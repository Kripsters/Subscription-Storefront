<?php

namespace App\Filament\Admin\Resources\Reports\Pages;

use App\Filament\Admin\Resources\Reports\ReportResource;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
