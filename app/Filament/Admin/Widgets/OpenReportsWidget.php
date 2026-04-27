<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Report;
use Filament\Widgets\Widget;

class OpenReportsWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.open-reports';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $reports = Report::query()
            ->where('status', '!=', 'closed')
            ->orderBy('created_at', 'desc')
            ->get(['id', 'title', 'status']);

        return ['reports' => $reports];
    }
}
