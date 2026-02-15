<?php

namespace App\Filament\Resources\MemberVouchers\Pages;

use App\Filament\Resources\MemberVouchers\MemberVoucherResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMemberVouchers extends ListRecords
{
    protected static string $resource = MemberVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
