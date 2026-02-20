<?php

namespace App\Filament\Resources\MemberVouchers\Schemas;

use App\Models\Member;
use App\Models\Voucher;
use App\Models\VoucherTipe;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MemberVoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('voucher_id')
                    ->label('Voucher')
                    ->options(
                        Voucher::with('jenis')
                            ->get()
                            ->mapWithKeys(fn($voucher) => [
                                $voucher->id => $voucher->name . ' (' . $voucher->tipe->tipe . ')'
                            ])
                    )
                    ->required(),
                Select::make('member_id')
                    ->label('Member Penerima Voucher')
                    // ->multiple()
                    ->options(Member::pluck('name', 'id'))
                    ->required(),
                DatePicker::make('assigned_at')
                    ->required(),
            ]);
    }
}
