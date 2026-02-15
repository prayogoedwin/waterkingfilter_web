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
                        Voucher::whereHas('tipe', fn($q) => $q->where('tipe', 'khusus'))
                            ->pluck('name', 'id')
                    )
                    ->required()
                    ->reactive(),
                Select::make('member_id')
                    ->label('Member Penerima Voucher')
                    // ->multiple()
                    ->options(Member::pluck('name', 'id'))
                    ->required()
                    ->visible(
                        fn($get) =>
                        $get('voucher_id') &&
                            Voucher::find($get('voucher_id'))?->tipe?->tipe === 'khusus'
                    ),
                DatePicker::make('assigned_at')
                    ->required(),
            ]);
    }
}
