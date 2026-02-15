<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Member;
use App\Models\MemberVoucher;
use App\Models\Product;
use App\Models\Voucher;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema(function () {

            $recalculate = function (callable $get, callable $set) {
                $items = $get('../../invoice_items') ?? $get('invoice_items') ?? [];

                $subtotal = collect($items)->sum(function ($item) {
                    return ((int) ($item['qty'] ?? 0)) * ((int) ($item['price'] ?? 0));
                });

                $set('../../subtotal', $subtotal);

                $discount = 0;
                $voucherId = $get('../../voucher_id') ?? $get('voucher_id');

                if ($voucherId) {
                    $voucher = \App\Models\Voucher::with('jenis')->find($voucherId);

                    if ($voucher) {
                        $discount = match ($voucher->jenis->jenis) {
                            'gratis' => $subtotal,
                            'potongan_nominal' => min($voucher->value, $subtotal),
                            'potongan_persentase' => (int) ($subtotal * ($voucher->value / 100)),
                            default => 0,
                        };
                    }
                }

                $set('../../discount', $discount);
                $set('../../total', max(0, $subtotal - $discount));
            };

            return [

                /* ================= MEMBER ================= */

                Select::make('member_id')
                    ->label('Member')
                    ->options(\App\Models\Member::pluck('name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) use ($recalculate) {
                        $set('voucher_id', null);
                        $recalculate($get, $set);
                    }),

                /* ================= ITEMS ================= */

                Repeater::make('invoice_items')
                    ->label('Produk')
                    // ->relationship('items')
                    ->schema([

                        Select::make('product_id')
                            ->label('Produk')
                            ->options(\App\Models\Product::pluck('nama', 'id'))
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) use ($recalculate) {
                                $price = (int) Product::find($state)?->harga ?? 0;
                                $set('price', $price);
                                $set('qty', 0);
                                $set('total', $price);
                                $recalculate($get, $set);
                            }),
                        TextInput::make('qty')
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) use ($recalculate) {
                                $set('total', $state * ($get('price') ?? 0));
                                $recalculate($get, $set);
                            }),
                        TextInput::make('price')
                            ->numeric()
                            ->readOnly(),

                        TextInput::make('total')
                            ->numeric()
                            ->readOnly(),
                    ])
                    ->columns(4),

                /* ================= SUMMARY ================= */

                TextInput::make('subtotal')
                    ->numeric()
                    ->disabled(),

                Select::make('voucher_id')
                    ->label('Voucher')
                    ->searchable()
                    ->options(function (callable $get) {

                        $memberId = $get('member_id');

                        if (! $memberId) {
                            return [];
                        }

                        return MemberVoucher::query()
                            ->where('member_id', $memberId)
                            ->with('voucher')
                            ->get()
                            ->mapWithKeys(fn($mv) => [
                                $mv->voucher_id => $mv->voucher->name,
                            ]);
                    })
                    ->reactive()
                    ->afterStateUpdated(fn($state, $get, $set) => $recalculate($get, $set)),

                TextInput::make('discount')
                    ->numeric()
                    ->disabled(),

                TextInput::make('total')
                    ->numeric()
                    ->disabled(),
            ];
        });
    }
}
