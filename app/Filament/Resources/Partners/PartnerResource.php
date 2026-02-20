<?php

namespace App\Filament\Resources\Partners;

use App\Filament\Resources\Partners\Pages\CreatePartner;
use App\Filament\Resources\Partners\Pages\EditPartner;
use App\Filament\Resources\Partners\Pages\ListPartners;
use App\Filament\Resources\Partners\Schemas\PartnerForm;
use App\Filament\Resources\Partners\Tables\PartnersTable;
use App\Models\Partner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Partner';
    protected static ?string $modelLabel = 'Partner';
    protected static ?string $pluralModelLabel = 'Partner';

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
        return auth()->check() && auth()->user()->can('create partner');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('edit partner');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('delete partner');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->check() && auth()->user()->can('delete partner');
    }

    public static function form(Schema $schema): Schema
    {
        return PartnerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnersTable::configure($table);
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
            'index' => ListPartners::route('/'),
            'create' => CreatePartner::route('/create'),
            'edit' => EditPartner::route('/{record}/edit'),
        ];
    }
}
