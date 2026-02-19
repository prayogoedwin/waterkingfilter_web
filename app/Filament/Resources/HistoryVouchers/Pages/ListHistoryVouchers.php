<?php

namespace App\Filament\Resources\HistoryVouchers\Pages;

use App\Filament\Resources\HistoryVouchers\HistoryVoucherResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHistoryVouchers extends ListRecords
{
    protected static string $resource = HistoryVoucherResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         CreateAction::make(),
    //     ];
    // }
}
