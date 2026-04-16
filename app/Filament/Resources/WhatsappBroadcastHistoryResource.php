<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WhatsappBroadcastHistoryResource\Pages\CreateWhatsappBroadcastHistory;
use App\Filament\Resources\WhatsappBroadcastHistoryResource\Pages\ListWhatsappBroadcastHistories;
use App\Models\Member;
use App\Models\WhatsappBroadcastHistory;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WhatsappBroadcastHistoryResource extends Resource
{
    protected static ?string $model = WhatsappBroadcastHistory::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static string | \UnitEnum | null $navigationGroup = 'Web Setting';

    protected static ?int $navigationSort = 99;

    protected static ?string $modelLabel = 'Broadcast WhatsApp';

    protected static ?string $pluralModelLabel = 'Broadcast WhatsApp';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->can('view whatsapp broadcast');
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->can('view whatsapp broadcast');
    }

    public static function canView(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('view whatsapp broadcast');
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->can('create whatsapp broadcast');
    }

    public static function canEdit(Model $record): bool
    {
        return false;
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
                TextInput::make('judul_pesan')
                    ->label('Judul Pesan')
                    ->required()
                    ->maxLength(255),
                Textarea::make('isi_pesan')
                    ->label('Isi Pesan')
                    ->required()
                    ->rows(6),
                Radio::make('target_type')
                    ->label('Pilihan Target')
                    ->required()
                    ->inline()
                    ->default('all')
                    ->live()
                    ->options([
                        'all' => 'All Nomor',
                        'selected' => 'Pilih Beberapa Nomor',
                    ]),
                Select::make('selected_members')
                    ->label('Nomor Tujuan')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(fn () => Member::query()
                        ->whereNotNull('whatsapp')
                        ->where('whatsapp', '!=', '')
                        ->orderBy('name')
                        ->get()
                        ->mapWithKeys(fn (Member $member) => [
                            $member->id => "{$member->name} - {$member->whatsapp}",
                        ])
                        ->toArray())
                    ->visible(fn (Get $get): bool => $get('target_type') === 'selected')
                    ->required(fn (Get $get): bool => $get('target_type') === 'selected')
                    ->dehydrated(false)
                    ->helperText('Pilih member yang akan menerima broadcast.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sent_at')
                    ->label('Waktu Kirim')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('batch_id')
                    ->label('Batch ID')
                    ->copyable()
                    ->limit(12)
                    ->tooltip(fn ($record) => $record->batch_id),
                TextColumn::make('judul_pesan')
                    ->label('Judul Pesan')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('member.name')
                    ->label('Member')
                    ->default('-')
                    ->searchable(),
                TextColumn::make('no_whatsapp')
                    ->label('No. Whatsapp')
                    ->searchable(),
                BadgeColumn::make('target_type')
                    ->label('Target')
                    ->formatStateUsing(fn (string $state): string => $state === 'all' ? 'All Nomor' : 'Selected')
                    ->colors([
                        'info' => 'all',
                        'warning' => 'selected',
                    ]),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => $state === 'success' ? 'Berhasil' : 'Gagal')
                    ->colors([
                        'success' => 'success',
                        'danger' => 'failed',
                    ]),
                TextColumn::make('error_message')
                    ->label('Keterangan')
                    ->default('-')
                    ->limit(50),
            ])
            ->defaultSort('sent_at', 'desc')
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWhatsappBroadcastHistories::route('/'),
            'create' => CreateWhatsappBroadcastHistory::route('/create'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['member']);
    }
}

