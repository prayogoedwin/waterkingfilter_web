<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Helpers\MaintenanceHelper;

class MaintenanceToggle extends Widget
{
    protected string $view = 'filament.widgets.maintenance-toggle';

    public function toggleMaintenance()
    {
        MaintenanceHelper::toggle();
        return redirect(request()->header('Referer'));
    }

    protected function getViewData(): array
    {
        return [
            'enabled' => MaintenanceHelper::isEnabled(),
        ];
    }
}
