<?php

namespace App\Filament\Resources\HistoryVouchers;

use App\Filament\Resources\HistoryVouchers\Pages\CreateHistoryVoucher;
use App\Filament\Resources\HistoryVouchers\Pages\EditHistoryVoucher;
use App\Filament\Resources\HistoryVouchers\Pages\ListHistoryVouchers;
use App\Filament\Resources\HistoryVouchers\Schemas\HistoryVoucherForm;
use App\Filament\Resources\HistoryVouchers\Tables\HistoryVouchersTable;
use App\Models\VoucherClaimHistory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class HistoryVoucherResource extends Resource
{
    protected static ?string $model = VoucherClaimHistory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'VoucherClaimHistory';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->can('view history voucher');
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->can('view history voucher');
    }

    public static function canView(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('view history voucher');
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->can('create history voucher');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('edit history voucher');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('delete history voucher');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->check() && auth()->user()->can('delete history voucher');
    }

    public static function form(Schema $schema): Schema
    {
        return HistoryVoucherForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HistoryVouchersTable::configure($table);
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
            'index' => ListHistoryVouchers::route('/'),
            'create' => CreateHistoryVoucher::route('/create'),
            'edit' => EditHistoryVoucher::route('/{record}/edit'),
        ];
    }
}
