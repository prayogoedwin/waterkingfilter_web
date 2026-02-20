<?php

namespace App\Filament\Resources\Vouchers\Pages;

use App\Filament\Resources\Vouchers\VoucherResource;
use App\Models\VoucherPartner;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVoucher extends EditRecord
{
    protected static string $resource = VoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $tipe = VoucherPartner::find($this->record->voucher_partner_id)?->name;

        if ($tipe === 'semua_partner') {
            $this->record->partners()->detach(); // bersihkan pivot jika diganti ke semua
        }
    }
}
