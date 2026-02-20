<?php

namespace App\Filament\Resources\MemberVouchers;

use App\Filament\Resources\MemberVouchers\Pages\CreateMemberVoucher;
use App\Filament\Resources\MemberVouchers\Pages\EditMemberVoucher;
use App\Filament\Resources\MemberVouchers\Pages\ListMemberVouchers;
use App\Filament\Resources\MemberVouchers\Schemas\MemberVoucherForm;
use App\Filament\Resources\MemberVouchers\Tables\MemberVouchersTable;
use App\Models\MemberVoucher;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MemberVoucherResource extends Resource
{
    protected static ?string $model = MemberVoucher::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'MemberVoucher';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->can('view member voucher');
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->can('view member voucher');
    }

    public static function canView(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('view member voucher');
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->can('create member voucher');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('edit member voucher');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('delete member voucher');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->check() && auth()->user()->can('delete member voucher');
    }

    public static function form(Schema $schema): Schema
    {
        return MemberVoucherForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MemberVouchersTable::configure($table);
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
            'index' => ListMemberVouchers::route('/'),
            'create' => CreateMemberVoucher::route('/create'),
            'edit' => EditMemberVoucher::route('/{record}/edit'),
        ];
    }
}
