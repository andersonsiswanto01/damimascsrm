<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FollowupsWidget;
use Filament\Pages\Dashboard as FilamentDashboard;

class Dashboard extends FilamentDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            FollowupsWidget::class, // Add the FollowupsWidget here
        ];
    }
}