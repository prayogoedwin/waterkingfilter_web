<?php

namespace App\Filament\Resources\HistoryKeuanganPartnerResource\Pages;

use App\Filament\Resources\HistoryKeuanganPartnerResource;
use App\Models\HistoryKeuanganPartner;
use Filament\Resources\Pages\CreateRecord;

class CreateHistoryKeuanganPartner extends CreateRecord
{
    protected static string $resource = HistoryKeuanganPartnerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = HistoryKeuanganPartner::STATUS_TERBAYAR;
        $data['keterangan'] = $data['keterangan'] ?? 'Kredit modal dari admin';

        return $data;
    }
}

