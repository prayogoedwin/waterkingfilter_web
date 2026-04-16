<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistoryKeuanganPartnerResource\Pages\EditHistoryKeuanganPartner;
use App\Filament\Resources\HistoryKeuanganPartnerResource\Pages\ListHistoryKeuanganPartners;
use App\Models\HistoryKeuanganPartner;
use App\Models\Partner;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HistoryKeuanganPartnerResource extends Resource
{
    protected static ?string $model = HistoryKeuanganPartner::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $modelLabel = 'History Keuangan';

    protected static ?string $pluralModelLabel = 'History Keuangan';

    protected static ?int $navigationSort = 6;

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->can('view partner');
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->can('view partner');
    }

    public static function canView(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('view partner');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('edit partner');
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->label('Status Withdrawal')
                    ->options(HistoryKeuanganPartner::statusOptions())
                    ->required()
                    ->helperText(HistoryKeuanganPartner::statusHelper()),
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->rows(3)
                    ->placeholder('Catatan tambahan dari admin (opsional)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('partner.name')
                    ->label('Nama Partner')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('tipe')
                    ->label('Jenis')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'withdrawal' => 'Penarikan',
                        'topup' => 'Top Up',
                    })
                    ->colors([
                        'danger' => 'withdrawal',
                        'success' => 'topup',
                    ])
                    ->sortable(),
                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => HistoryKeuanganPartner::statusOptions()[$state] ?? $state)
                    ->colors([
                        'gray' => HistoryKeuanganPartner::STATUS_MENUNGGU,
                        'warning' => HistoryKeuanganPartner::STATUS_PROSES,
                        'success' => HistoryKeuanganPartner::STATUS_TERBAYAR,
                    ])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Request')
                    ->dateTime('d F Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(HistoryKeuanganPartner::statusOptions()),
                SelectFilter::make('tipe')
                    ->options([
                        'withdrawal' => 'Penarikan',
                        'topup' => 'Top Up',
                    ]),
                SelectFilter::make('partner_id')
                    ->label('Partner')
                    ->options(
                        Partner::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->visible(fn (HistoryKeuanganPartner $record): bool => $record->tipe === 'withdrawal'),
            ])
            ->toolbarActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHistoryKeuanganPartners::route('/'),
            'edit' => EditHistoryKeuanganPartner::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['partner']);
        $partnerId = request()->integer('partner_id');

        if ($partnerId > 0) {
            $query->where('partner_id', $partnerId);
        }

        return $query;
    }
}

