<?php

namespace App\Filament\Resources\HistoryVouchers\Pages;

use App\Filament\Resources\HistoryVouchers\HistoryVoucherResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHistoryVoucher extends EditRecord
{
    protected static string $resource = HistoryVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
