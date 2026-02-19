<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Member;
use App\Models\MemberVoucher;
use App\Models\Product;
use App\Models\Voucher;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema(function () {

            $recalculate = function (callable $get, callable $set) {
                $items = $get('invoice_items') ?? [];

                $subtotal = collect($items)->sum(function ($item) {
                    return ((int) ($item['qty'] ?? 0)) * ((int) ($item['price'] ?? 0));
                });

                $set('subtotal', $subtotal);

                $discount = 0;
                $voucherId = $get('voucher_id');

                if ($voucherId) {
                    $voucher = Voucher::with('jenis')->find($voucherId);

                    if ($voucher) {
                        $discount = match ($voucher->jenis->jenis) {
                            'gratis' => $subtotal,
                            'potongan_nominal' => min($voucher->value, $subtotal),
                            'potongan_persentase' => (int) ($subtotal * ($voucher->value / 100)),
                            default => 0,
                        };
                    }
                }

                $set('discount', $discount);
                $set('total', max(0, $subtotal - $discount));
            };

            return [
                Group::make()
                    ->schema([
                        Section::make('Informasi Member')
                            ->schema([
                                Select::make('member_id')
                                    ->label('Member')
                                    ->options(Member::pluck('name', 'id'))
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) use ($recalculate) {
                                        $set('voucher_id', null);
                                        $recalculate($get, $set);
                                    }),
                                Select::make('voucher_id')
                                    ->label('Voucher')
                                    ->searchable()
                                    ->options(function (callable $get) {
                                        $memberId = $get('member_id');

                                        if (! $memberId) {
                                            return [];
                                        }

                                        return \App\Models\MemberVoucher::query()
                                            ->where('member_id', $memberId)
                                            ->with('voucher')
                                            ->get()
                                            ->mapWithKeys(fn($mv) => [
                                                $mv->voucher_id => $mv->voucher->name,
                                            ]);
                                    })
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, $get, $set) => $recalculate($get, $set)),
                            ])->columns(2),
                        View::make('filament.components.product-catalog')
                            ->viewData([
                                'products' => Product::all(),
                            ])
                            ->hiddenOn('view'),
                    ])->columnSpan(2),

                Section::make()
                    ->description('Informasi Pembayaran')
                    ->schema([
                        View::make('filament.components.invoice-cart'),

                        Hidden::make('invoice_items')
                            ->default([])
                            ->reactive()
                            ->afterStateUpdated(fn($state, $get, $set) => $recalculate($get, $set)),

                        Grid::make(2)->schema([
                            TextInput::make('subtotal')
                                ->label('Subtotal')
                                ->numeric()
                                ->disabled(),
                            TextInput::make('discount')
                                ->label('Diskon')
                                ->numeric()
                                ->disabled()
                        ]),
                        TextInput::make('total')
                            ->label('Total Bayar')
                            ->numeric()
                            ->disabled()
                            ->extraAttributes(['class' => 'font-bold text-lg']),
                    ])
            ];
        })->columns(3);
    }
}
