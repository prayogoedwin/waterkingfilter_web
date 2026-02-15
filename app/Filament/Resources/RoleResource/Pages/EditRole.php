<?php

namespace App\Filament\Resources\RoleResource\Pages;

use Filament\Actions\DeleteAction;
use Log;
use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing permissions untuk ditampilkan di form
        $permissions = $this->record->permissions->groupBy(function ($permission) {
            return ucfirst(explode(' ', $permission->name)[1] ?? $permission->name);
        });
        
        foreach ($permissions as $module => $perms) {
            $data["permissions_{$module}"] = $perms->pluck('id')->toArray();
        }
        
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        Log::info('handleRecordUpdate called with data:', $data);
        
        $permissionIds = [];
        
        // Kumpulkan permissions dari field dinamis
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'permissions_') && is_array($value)) {
                Log::info("Found permission field: {$key}", $value);
                $permissionIds = array_merge($permissionIds, $value);
            }
        }
        
        Log::info('Collected permission IDs:', $permissionIds);
        
        // Hapus field permissions dari data
        $cleanData = collect($data)->reject(function ($value, $key) {
            return str_starts_with($key, 'permissions_');
        })->toArray();
        
        // Update record
        $record->update($cleanData);
        
        // Sync permissions
        Log::info('Syncing permissions to role ID: ' . $record->id);
        $record->permissions()->sync($permissionIds);
        Log::info('Permissions synced successfully');
        
        return $record;
    }
}