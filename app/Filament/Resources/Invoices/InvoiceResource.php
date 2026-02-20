<?php

namespace App\Filament\Resources\Invoices;

use App\Filament\Resources\Invoices\Pages\CreateInvoice;
use App\Filament\Resources\Invoices\Pages\EditInvoice;
use App\Filament\Resources\Invoices\Pages\ListInvoices;
use App\Filament\Resources\Invoices\Pages\ViewInvoice;
use App\Filament\Resources\Invoices\Schemas\InvoiceForm;
use App\Filament\Resources\Invoices\Tables\InvoicesTable;
use App\Models\Invoice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Invoice';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->can('view invoice');
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->can('view invoice');
    }

    public static function canView(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('view invoice');
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->can('create invoice');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('edit invoice');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->can('delete invoice');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->check() && auth()->user()->can('delete invoice');
    }

    public static function form(Schema $schema): Schema
    {
        return InvoiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvoicesTable::configure($table);
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
            'index' => ListInvoices::route('/'),
            'create' => CreateInvoice::route('/create'),
            'edit' => EditInvoice::route('/{record}/edit'),
            'view' => ViewInvoice::route('/{record}'),
        ];
    }
}
