<?php

namespace App\Filament\Resources\HistoryKeuanganPartnerResource\Pages;

use App\Filament\Resources\HistoryKeuanganPartnerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHistoryKeuanganPartner extends EditRecord
{
    protected static string $resource = HistoryKeuanganPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(false),
        ];
    }
}

