<?php

namespace App\Filament\Resources\HistoryVouchers\Tables;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HistoryVouchersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('voucher.name'),
                TextColumn::make('member.name'),
                TextColumn::make('claim_at')
                    ->label('Tanggal Claim')
                    ->dateTime('d F Y'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('view_invoice')
                    ->label('Lihat Invoice')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(
                        fn($record) => $record->invoice_id
                            ? InvoiceResource::getUrl('view', ['record' => $record->invoice_id])
                            : null
                    )
                    ->visible(fn($record) => $record->invoice_id !== null)
                    ->openUrlInNewTab(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
