<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WebInfoWidget extends Widget
{
    protected string $view = 'filament.widgets.web-info-widget';

    public static function canView(): bool
    {
        return true;
    }
}
