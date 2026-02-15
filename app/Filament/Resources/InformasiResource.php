<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\InformasiResource\Pages\ListInformasis;
use App\Filament\Resources\InformasiResource\Pages\CreateInformasi;
use App\Filament\Resources\InformasiResource\Pages\EditInformasi;
use App\Filament\Resources\InformasiResource\Pages;
use App\Filament\Resources\InformasiResource\RelationManagers;
use App\Models\Informasi;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;


class InformasiResource extends Resource
{
    protected static ?string $model = Informasi::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';


    //setting letak grup menu
    protected static string | \UnitEnum | null $navigationGroup = 'Web Setting';
    protected static ?int $navigationSort = 3; // Urutan setelah Kategori

    // Label
    protected static ?string $modelLabel = 'Informasi';
    protected static ?string $pluralModelLabel = 'Informasi';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->can('view informasis');
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->can('view informasis');
    }

    public static function canView(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('view informasis');
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->can('create informasis');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('edit informasis');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('delete informasis');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->check() && auth()->user()->can('delete informasis');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                 TextInput::make('slug')->required(),
                 TextInput::make('nama')->required(),
                 RichEditor::make('description')->required()->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Informasi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
            ])
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
            'index' => ListInformasis::route('/'),
            'create' => CreateInformasi::route('/create'),
            'edit' => EditInformasi::route('/{record}/edit'),
        ];
    }
}
