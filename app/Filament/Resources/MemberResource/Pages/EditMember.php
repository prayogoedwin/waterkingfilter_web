<?php

namespace App\Filament\Resources\MemberResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle tambahan_poin
        if (isset($data['tambahan_poin']) && $data['tambahan_poin'] > 0) {
            // Ambil poin asli dari record yang sedang diedit
            $originalPoin = $this->record->poin_terkini;
            $tambahanPoin = (int) $data['tambahan_poin'];
            $data['poin_terkini'] = $originalPoin + $tambahanPoin;
        }
        
        // Hapus tambahan_poin dari data yang akan disave
        unset($data['tambahan_poin']);
        
        // Handle password - jika kosong, hapus dari data
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Redirect ke halaman edit yang sama untuk "refresh" form
        // Ini akan membuat tambahan_poin kembali kosong
        $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
    }
}
