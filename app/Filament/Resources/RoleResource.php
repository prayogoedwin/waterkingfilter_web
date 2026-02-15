<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\RoleResource\Pages\ListRoles;
use App\Filament\Resources\RoleResource\Pages\CreateRole;
use App\Filament\Resources\RoleResource\Pages\EditRole;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms\Components\CheckboxList;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

     //setting letak grup menu
    protected static string | \UnitEnum | null $navigationGroup = 'Sistem';
    protected static ?int $navigationSort = 2; // Urutan setelah Kategori

    // Label
    protected static ?string $modelLabel = 'Role Admin';
    protected static ?string $pluralModelLabel = 'Role Admin';

      public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->can('view roles');
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->can('view roles');
    }

    public static function canView(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('view roles');
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->can('create roles');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('edit roles');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('delete roles');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->check() && auth()->user()->can('delete roles');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Role Name')
                    ->required(),

                TextInput::make('guard_name')
                    ->default('web')
                    ->required(),


                Fieldset::make('Permissions for this Role')
                    ->schema(
                        self::getPermissionGroups()
                    )
            ]);
    }

    public static function getPermissionGroups(): array
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return ucfirst(explode(' ', $permission->name)[1] ?? $permission->name);
        });

        $groups = [];

        foreach ($permissions as $module => $perms) {
            $groups[] = CheckboxList::make("permissions_{$module}")
                ->label($module)
                ->options($perms->pluck('name', 'id'))
                ->columns(2)
                ->bulkToggleable();
        }

        return $groups;
    }

    

    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('guard_name'),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }


    
}
