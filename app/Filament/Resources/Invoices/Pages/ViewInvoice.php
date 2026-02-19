<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_a4')
                ->label('Cetak Invoice A4')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn() => route('invoice.print', $this->record))
                ->openUrlInNewTab(),

            Action::make('print_struk')
                ->label('Cetak Struk')
                ->icon('heroicon-o-receipt-percent')
                ->color('gray')
                ->url(fn() => route('invoice.struk', $this->record))
                ->openUrlInNewTab(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['invoice_items'] = Invoice::find($data['id'])
            ->items
            ->map(fn($item) => [
                'product_id'   => $item->product_id,
                'product_name' => $item->product->nama,
                'qty'          => $item->qty,
                'price'        => $item->price,
                'total'        => $item->total,
            ])
            ->toArray();

        return $data;
    }
}
