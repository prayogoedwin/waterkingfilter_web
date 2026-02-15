<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Kumpulkan semua permissions dari field dinamis
        $permissions = [];
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'permissions_')) {
                if (is_array($value)) {
                    $permissions = array_merge($permissions, $value);
                }
                unset($data[$key]);
            }
        }

        // Simpan permissions untuk di-sync setelah create
        $this->permissions = $permissions;

        return $data;
    }

    protected function afterCreate(): void
    {
        // Sync permissions setelah role dibuat
        if (!empty($this->permissions)) {
            $this->record->syncPermissions($this->permissions);
        }
    }

    protected array $permissions = [];
}
