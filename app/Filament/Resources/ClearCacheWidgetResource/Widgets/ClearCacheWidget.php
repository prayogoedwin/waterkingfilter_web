<?php

namespace App\Filament\Resources\ClearCacheWidgetResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;

class ClearCacheWidget extends Widget
{
    protected string $view = 'filament.resources.clear-cache-widget-resource.widgets.clear-cache-widget';

    protected int | string | array $columnSpan = 1;

    // 🔥 Tambahkan ini supaya Livewire snapshot bisa disimpan
    protected static bool $isLazy = false;

    public function clearCache(): void
    {
        Cache::flush();

        Notification::make()
            ->title('Cache berhasil dibersihkan!')
            ->success()
            ->send();
    }
}
