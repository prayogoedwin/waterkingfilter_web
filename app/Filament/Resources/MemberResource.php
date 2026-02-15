<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\MemberResource\Pages\ListMembers;
use App\Filament\Resources\MemberResource\Pages\CreateMember;
use App\Filament\Resources\MemberResource\Pages\EditMember;
use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    //setting letak grup menu
    protected static string | \UnitEnum | null $navigationGroup = 'Pengguna';
    protected static ?int $navigationSort = 4; // Urutan setelah Kategori

    // Label
    protected static ?string $modelLabel = 'Member';
    protected static ?string $pluralModelLabel = 'Member';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->can('view members');
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->can('view members');
    }

    public static function canView(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('view members');
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->can('create members');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('edit members');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('delete members');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->check() && auth()->user()->can('delete members');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->unique(
                        table: 'members',
                        column: 'email',
                        ignoreRecord: true
                    ),
                TextInput::make('password')
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->confirmed()
                    ->rules(['nullable'])
                    ->dehydrated(fn($state) => filled($state))
                    ->autocomplete('new-password')
                    ->prefixIcon('heroicon-o-lock-closed')
                    ->placeholder(
                        fn(string $operation): string =>
                        $operation === 'edit' ? 'Kosongkan jika tidak ingin mengubah password' : ''
                    ),
                TextInput::make('password_confirmation')
                    ->requiredWith('password')
                    ->password()
                    ->revealable()
                    ->label('Confirm Password')
                    ->dehydrated(false)
                    ->placeholder(
                        fn(string $operation): string =>
                        $operation === 'edit' ? 'Kosongkan jika tidak ingin mengubah password' : ''
                    ),
                TextInput::make('whatsapp')
                    ->label('No Whatsapp')
                    ->default(0),
                TextInput::make('poin_terkini')
                    ->label('Poin Member')
                    ->readonly()
                    ->default(0),

                // Field tambahan_poin hanya muncul di edit
                TextInput::make('tambahan_poin')
                    ->label('Tambahan Poin')
                    ->numeric()
                    ->placeholder('Masukkan tambahan poin')
                    ->hidden(fn(string $operation): bool => $operation === 'create')
                    ->afterStateUpdated(function (Get $get, Set $set, $state, $record) {
                        if ($state !== null && $state !== '' && $record) {
                            $originalPoin = $record->poin_terkini; // Poin asli dari database
                            $tambahanPoin = (int) $state;
                            $newTotal = $originalPoin + $tambahanPoin;
                            $set('poin_terkini', $newTotal);
                        } elseif (($state === null || $state === '') && $record) {
                            // Jika field kosong, kembalikan ke poin asli
                            $set('poin_terkini', $record->poin_terkini);
                        }
                    })
                    ->live()
                    ->dehydrated(false), // Tidak disimpan ke database

                Toggle::make('status')
                    ->label('Status'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'member');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Member')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('whatsapp')
                    ->label('Whatsapp')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('poin_terkini')
                    ->label('Poin')
                    ->sortable()
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => number_format($state, 0) . ' poin')
                    ->icon('heroicon-o-star')
                    ->iconColor('warning'),

                IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->trueColor('success')
                    ->falseIcon('heroicon-o-x-circle')
                    ->falseColor('danger'),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMembers::route('/'),
            'create' => CreateMember::route('/create'),
            'edit' => EditMember::route('/{record}/edit'),
        ];
    }
}
