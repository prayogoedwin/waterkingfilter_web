<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Permission;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    protected function handleRecordCreation(array $data): Permission
    {
        $name = strtolower(trim($data['name']));
        $guard = $data['guard_name'] ?? 'web';

        // List action yang mau dibuat otomatis
        $actions = ['view', 'create', 'edit', 'delete'];

        $lastCreated = null;

        foreach ($actions as $action) {
            $lastCreated = Permission::firstOrCreate([
                'name' => "{$action} {$name}",
                'guard_name' => $guard
            ]);
        }

        return $lastCreated; // kembalikan salah satu (supaya Filament nggak error)
    }
}
