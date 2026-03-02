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
                        Voucher::with('tipe')
                            ->whereHas('tipe', function ($q) {
                                $q->where('tipe', 'khusus');
                            })
                            ->get()
                            ->mapWithKeys(fn($voucher) => [
                                $voucher->id => $voucher->name . ' (' . $voucher->tipe->tipe . ')'
                            ])
                    )
                    ->live()
                    ->afterStateHydrated(function ($state, callable $set) {

                        $voucher = Voucher::find($state);

                        if ($voucher) {
                            $set('tanggal_mulai', $voucher->tanggal_mulai);
                            $set('tanggal_selesai', $voucher->tanggal_selesai);
                        }
                    })
                    ->afterStateUpdated(function ($state, callable $set) {

                        $voucher = Voucher::find($state);

                        if ($voucher) {
                            $set('tanggal_mulai', $voucher->tanggal_mulai);
                            $set('tanggal_selesai', $voucher->tanggal_selesai);
                        } else {
                            $set('tanggal_mulai', null);
                            $set('tanggal_selesai', null);
                        }
                    })
                    ->required(),
                DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->disabled(),

                DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->disabled(),
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
