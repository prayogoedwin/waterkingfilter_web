<?php

namespace App\Filament\Resources\Partners\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class HistoryKeuanganRelationManager extends RelationManager
{
    protected static string $relationship = 'historyKeuangan';
    protected static ?string $title = 'History Keuangan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tipe')
                    ->options([
                        'withdrawal' => 'Withdrawal',
                        'topup' => 'Top Up',
                    ])
                    ->required()
                    ->default('topup'),

                TextInput::make('nominal')
                    ->label('Nominal')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(1000),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('tipe')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'withdrawal' => 'danger',
                        'topup' => 'success',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'withdrawal' => 'Penarikan',
                        'topup' => 'Top Up',
                    })
                    ->sortable(),

                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->color(fn($record) => $record->status === 'withdrawal' ? 'danger' : 'success'),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d F Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tipe')
                    ->label('Jenis Transaksi')
                    ->options([
                        'withdrawal' => 'Withdrawal',
                        'topup' => 'Top Up',
                    ]),
            ])
            ->headerActions([
                // CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                // EditAction::make(),
                // DissociateAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DissociateBulkAction::make(),
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
