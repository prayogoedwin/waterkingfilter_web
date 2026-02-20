<?php

namespace App\Filament\Resources\Vouchers;

use App\Filament\Resources\Vouchers\Pages\CreateVoucher;
use App\Filament\Resources\Vouchers\Pages\EditVoucher;
use App\Filament\Resources\Vouchers\Pages\ListVouchers;
use App\Filament\Resources\Vouchers\Schemas\VoucherForm;
use App\Filament\Resources\Vouchers\Tables\VouchersTable;
use App\Models\Voucher;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Voucher';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->can('view voucher');
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->can('view voucher');
    }

    public static function canView(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('view voucher');
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->can('create voucher');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('edit voucher');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('delete voucher');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->check() && auth()->user()->can('delete voucher');
    }

    public static function form(Schema $schema): Schema
    {
        return VoucherForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VouchersTable::configure($table);
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
            'index' => ListVouchers::route('/'),
            'create' => CreateVoucher::route('/create'),
            'edit' => EditVoucher::route('/{record}/edit'),
        ];
    }
}
