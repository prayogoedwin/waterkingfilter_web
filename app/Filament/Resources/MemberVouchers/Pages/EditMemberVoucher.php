<?php

namespace App\Filament\Resources\MemberVouchers\Pages;

use App\Filament\Resources\MemberVouchers\MemberVoucherResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMemberVoucher extends EditRecord
{
    protected static string $resource = MemberVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
