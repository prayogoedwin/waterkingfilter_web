<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use App\Models\VoucherJenis;
use App\Models\VoucherPartner;
use App\Models\VoucherPenggunaan;
use App\Models\VoucherTipe;
use App\Models\WaktuVoucher;
use Closure;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                // Select::make('voucher_tipe_id')
                //     ->label('Tipe Voucher')
                //     ->options(VoucherTipe::query()->pluck('tipe', 'id'))
                //     ->required()
                //     ->searchable(),
                Select::make('voucher_jenis_id')
                    ->label('Jenis Voucher')
                    ->options(VoucherJenis::query()->pluck('jenis', 'id'))
                    ->required()
                    ->searchable()
                    ->reactive(),
                TextInput::make('value')
                    ->label(fn(callable $get) => match (VoucherJenis::find($get('voucher_jenis_id'))?->jenis) {
                        'potongan_persentase' => 'Nilai Diskon (%)',
                        'potongan_nominal'    => 'Nilai Diskon (Rp)',
                        'cashback'            => 'Nilai Cashback (Rp)',
                        default               => 'Nilai Voucher',
                    })
                    ->helperText(fn(callable $get) => match (VoucherJenis::find($get('voucher_jenis_id'))?->jenis) {
                        'potongan_persentase' => 'Isi angka tanpa % (contoh: 10)',
                        'potongan_nominal'    => 'Nominal rupiah',
                        'cashback'            => 'Nominal cashback',
                        default               => null,
                    })
                    ->numeric()
                    ->required(
                        fn(callable $get) =>
                        VoucherJenis::find($get('voucher_jenis_id'))?->jenis !== 'gratis'
                    )
                    ->visible(
                        fn(callable $get) =>
                        VoucherJenis::find($get('voucher_jenis_id'))?->jenis !== 'gratis'
                    ),
                Select::make('voucher_penggunaan_id')
                    ->label('Penggunaan Voucher')
                    ->options(VoucherPenggunaan::query()->pluck('penggunaan', 'id'))
                    ->required()
                    ->searchable()
                    ->reactive(),
                TextInput::make('jumlah')
                    ->label(
                        fn(callable $get) =>
                        VoucherPenggunaan::find($get('voucher_penggunaan_id'))?->penggunaan === 'terbatas' ? 'Jumlah Penggunaan' : 'Jumlah User Penggunaan'
                    )
                    ->numeric()
                    ->required()
                    ->visible(
                        fn(callable $get) =>
                        VoucherPenggunaan::find($get('voucher_penggunaan_id'))?->penggunaan === 'terbatas' ||
                            VoucherPenggunaan::find($get('voucher_penggunaan_id'))?->penggunaan === 'terbatas_per_user'
                    ),
                Select::make('voucher_partner_id')
                    ->label('Tipe Partner')
                    ->relationship('voucherPartner', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn(Set $set) => $set('partners', [])), // reset saat ganti tipe

                Select::make('partners')
                    ->label('Pilih Partner')
                    ->relationship('partners', 'name')
                    ->multiple()
                    ->preload()
                    ->visible(fn(Get $get) => in_array(
                        VoucherPartner::find($get('voucher_partner_id'))?->name,
                        ['satu_partner', 'beberapa_partner']
                    ))
                    ->rules([
                        fn(Get $get) => function (string $attribute, $value, Closure $fail) use ($get) {
                            $tipe = VoucherPartner::find($get('voucher_partner_id'))?->name;
                            if ($tipe === 'satu_partner' && count($value) > 1) {
                                $fail('Hanya boleh memilih 1 partner.');
                            }
                        }
                    ]),
                DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->required(),
                DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->required(),
                Section::make('Waktu Penggunaan')
                    ->schema([

                        Select::make('waktu_id')
                            ->label('Tipe Waktu')
                            ->relationship('waktuVoucher', 'waktu')
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->label)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Reset semua field detail
                                $set('fixed_date', null);
                                $set('period_start', null);
                                $set('period_end', null);
                                $set('specific_dates', null);
                                $set('specific_days', null);
                            }),

                        // TANGGAL FIX
                        DatePicker::make('fixed_date')
                            ->label('Tanggal Penggunaan')
                            ->required()
                            ->visible(function (Get $get) {
                                $waktuId = $get('waktu_id');
                                if (!$waktuId) return false;
                                $waktu = WaktuVoucher::find($waktuId);
                                return $waktu?->waktu === 'tanggal_fix';
                            }),

                        // PERIODE TANGGAL
                        Grid::make(2)->schema([
                            DatePicker::make('period_start')
                                ->label('Tanggal Mulai')
                                ->required()
                                ->visible(function (Get $get) {
                                    $waktuId = $get('waktu_id');
                                    if (!$waktuId) return false;
                                    $waktu = WaktuVoucher::find($waktuId);
                                    return $waktu?->waktu === 'periode_tanggal';
                                }),

                            DatePicker::make('period_end')
                                ->label('Tanggal Selesai')
                                ->required()
                                ->after('period_start')
                                ->visible(function (Get $get) {
                                    $waktuId = $get('waktu_id');
                                    if (!$waktuId) return false;
                                    $waktu = WaktuVoucher::find($waktuId);
                                    return $waktu?->waktu === 'periode_tanggal';
                                }),
                        ]),

                        // TANGGAL TERTENTU
                        CheckboxList::make('specific_dates')
                            ->label('Pilih Tanggal (akan berulang setiap bulan)')
                            ->options(array_combine(range(1, 31), range(1, 31)))
                            ->columns(7)
                            ->gridDirection('row')
                            ->required()
                            ->visible(function (Get $get) {
                                $waktuId = $get('waktu_id');
                                if (!$waktuId) return false;
                                $waktu = WaktuVoucher::find($waktuId);
                                return $waktu?->waktu === 'tanggal_tertentu';
                            })
                            ->helperText('Voucher dapat digunakan pada tanggal-tanggal ini setiap bulan'),

                        // HARI TERTENTU
                        CheckboxList::make('specific_days')
                            ->label('Pilih Hari')
                            ->options([
                                'senin' => 'Senin',
                                'selasa' => 'Selasa',
                                'rabu' => 'Rabu',
                                'kamis' => 'Kamis',
                                'jumat' => 'Jumat',
                                'sabtu' => 'Sabtu',
                                'minggu' => 'Minggu',
                            ])
                            ->columns(4)
                            ->required()
                            ->visible(function (Get $get) {
                                $waktuId = $get('waktu_id');
                                if (!$waktuId) return false;
                                $waktu = WaktuVoucher::find($waktuId);
                                return $waktu?->waktu === 'hari_tertentu';
                            })
                            ->helperText('Voucher dapat digunakan pada hari-hari ini setiap minggu'),
                    ])
            ]);
    }
}
