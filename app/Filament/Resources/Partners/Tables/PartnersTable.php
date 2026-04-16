<?php

namespace App\Filament\Resources\Partners\Tables;

use App\Filament\Resources\Partners\PartnerResource;
use App\Filament\Resources\HistoryKeuanganPartnerResource;
use App\Models\Partner;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class PartnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->visibility('public')
                    ->square(),
                TextColumn::make('name')
                    ->label('Nama Partner')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('settlement_method')
                    ->label('Tipe Settlement')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => Partner::settlementMethodOptions()[$state ?? Partner::SETTLEMENT_POSTPAID] ?? 'Postpaid (dicairkan akhir)')
                    ->color(fn (?string $state): string => ($state ?? Partner::SETTLEMENT_POSTPAID) === Partner::SETTLEMENT_PREPAID ? 'info' : 'warning')
                    ->sortable(),
                TextColumn::make('alamat'),
                TextColumn::make('saldo_wallet')
                    ->label('Wallet Saat Ini')
                    ->money('IDR', locale: 'id')
                    ->badge()
                    ->color('success'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('update_password')
                    ->label('Ubah Password')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->closeModalByClickingAway(false)
                    ->form([
                        Toggle::make('reset_to_email')
                            ->label('Reset ke Email')
                            ->default(true)
                            ->live()
                            ->helperText('Password akan di-set sama dengan email user'),

                        TextInput::make('custom_password')
                            ->label('Password Baru')
                            ->password()
                            ->minLength(8)
                            ->visible(fn(Get $get) => !$get('reset_to_email'))
                            ->required(fn(Get $get) => !$get('reset_to_email'))
                            ->revealable()
                            ->helperText('Minimal 8 karakter'),

                        TextInput::make('confirm_password')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->revealable()
                            ->same('custom_password')
                            ->visible(fn(Get $get) => !$get('reset_to_email'))
                            ->required(fn(Get $get) => !$get('reset_to_email'))
                            ->dehydrated(false),
                    ])
                    ->action(function ($record, array $data) {
                        $newPassword = $data['reset_to_email']
                            ? $record->email
                            : $data['custom_password'];

                        $record->update([
                            'password' => Hash::make($newPassword),
                        ]);

                        Notification::make()
                            ->title('Password Berhasil Diubah')
                            ->body(
                                $data['reset_to_email']
                                    ? 'Password untuk ' . $record->name . ' telah direset ke: ' . $record->email
                                    : 'Password untuk ' . $record->name . ' telah diperbarui.'
                            )
                            ->success()
                            ->send();
                    }),
                Action::make('history_keuangan')
                    ->label('History Keuangan')
                    ->icon('heroicon-o-banknotes')
                    ->color('info')
                    ->url(
                        fn(Partner $record): string =>
                        HistoryKeuanganPartnerResource::getUrl('index', [
                            'partner_id' => $record->id,
                        ])
                    )
                    ->openUrlInNewTab(false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
