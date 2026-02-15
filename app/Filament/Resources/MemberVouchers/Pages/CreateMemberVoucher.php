<?php

namespace App\Filament\Resources\MemberVouchers\Pages;

use App\Filament\Resources\MemberVouchers\MemberVoucherResource;
use App\Models\MemberVoucher;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateMemberVoucher extends CreateRecord
{
    protected static string $resource = MemberVoucherResource::class;

    // protected function handleRecordCreation(array $data): Model
    // {
    //     $memberIds = $data['member_ids'];
    //     unset($data['member_ids']);

    //     DB::transaction(function () use ($data, $memberIds) {
    //         foreach ($memberIds as $memberId) {
    //             MemberVoucher::firstOrCreate([
    //                 'voucher_id' => $data['voucher_id'],
    //                 'member_id'  => $memberId,
    //             ], [
    //                 'assigned_at' => $data['assigned_at'],
    //             ]);
    //         }
    //     });
    //     return new MemberVoucher();
    // }

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
}
