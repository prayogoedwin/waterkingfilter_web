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

class MemberVoucherResource extends Resource
{
    protected static ?string $model = MemberVoucher::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'MemberVoucher';

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
