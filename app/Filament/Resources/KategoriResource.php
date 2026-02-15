<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\KategoriResource\Pages\ListKategoris;
use App\Filament\Resources\KategoriResource\Pages\CreateKategori;
use App\Filament\Resources\KategoriResource\Pages\EditKategori;
use App\Filament\Resources\KategoriResource\Pages;
use App\Filament\Resources\KategoriResource\RelationManagers;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;


class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

      //setting letak grup menu
    protected static string | \UnitEnum | null $navigationGroup = 'Web Setting';
    protected static ?int $navigationSort = 5; // Urutan setelah Kategori

    // Label
    protected static ?string $modelLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategori';

     public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->can('view kategoris');
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->can('view kategoris');
    }

    public static function canView(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('view kategoris');
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->can('create kategoris');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('edit kategoris');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('delete kategoris');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->check() && auth()->user()->can('delete kategoris');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')->required(),
                TextInput::make('deskripsi')->required(),
                Toggle::make('tambahan_pilihan')
                 ->label('Multi Poin')
                ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tambahan_pilihan')
                    ->label('Tambahan Pilihan')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => $state ? 'ON' : 'OFF')
                    ->color(fn (int $state): string => $state ? 'success' : 'danger'),
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
            'index' => ListKategoris::route('/'),
            'create' => CreateKategori::route('/create'),
            'edit' => EditKategori::route('/{record}/edit'),
        ];
    }
}
