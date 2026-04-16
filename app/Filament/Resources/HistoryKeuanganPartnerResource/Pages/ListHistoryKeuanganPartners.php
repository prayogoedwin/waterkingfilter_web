<?php

namespace App\Filament\Resources\HistoryKeuanganPartnerResource\Pages;

use App\Filament\Resources\HistoryKeuanganPartnerResource;
use Filament\Resources\Pages\ListRecords;

class ListHistoryKeuanganPartners extends ListRecords
{
    protected static string $resource = HistoryKeuanganPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

