<?php

namespace App\Filament\Resources\Vouchers\Pages;

use App\Filament\Resources\Vouchers\VoucherResource;
use App\Models\VoucherPartner;
use App\Models\VoucherTipe;
use Filament\Resources\Pages\CreateRecord;

class CreateVoucher extends CreateRecord
{
    protected static string $resource = VoucherResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $jenis = VoucherTipe::where('tipe', 'khusus')->first();

        $data['voucher_tipe_id'] = $jenis->id;

        return $data;
    }

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
