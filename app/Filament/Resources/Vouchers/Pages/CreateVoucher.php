<?php

namespace App\Filament\Resources\Vouchers\Pages;

use App\Filament\Resources\Vouchers\VoucherResource;
use App\Models\VoucherPartner;
use Filament\Resources\Pages\CreateRecord;

class CreateVoucher extends CreateRecord
{
    protected static string $resource = VoucherResource::class;

    protected function afterCreate(): void
    {
        $tipe = VoucherPartner::find($this->record->voucher_partner_id)?->name;

        if ($tipe === 'semua_partner') {
            // Tidak perlu simpan ke pivot
            $this->record->partners()->detach();
            return;
        }
    }
}
