<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class MaintenanceHelper
{
    public static function isEnabled(): bool
    {
        if (!Storage::exists('maintenance.json')) return false;

        $data = json_decode(Storage::get('maintenance.json'), true);
        return $data['enabled'] ?? false;
    }

    public static function toggle(): void
    {
        $newStatus = !self::isEnabled();
        Storage::put('maintenance.json', json_encode([
            'enabled' => $newStatus,
            'updated_at' => now()->toDateTimeString(),
        ]));
    }
}
