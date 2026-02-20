<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use App\Models\Voucher;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditInvoice extends EditRecord
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
        $invoice = Invoice::find($data['id']);
        $data['invoice_items'] = Invoice::find($data['id'])
            ->items
            ->map(fn($item) => [
                'product_id' => $item->product_id,
                'qty'        => $item->qty,
                'price'      => $item->price,
                'total'      => $item->total,
            ])
            ->toArray();
        if ($invoice->subtotal > 0) {
            $data['discount_percent'] = ($invoice->discount / $invoice->subtotal) * 100;
        } else {
            $data['discount_percent'] = 0;
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {

            $items = $data['invoice_items'] ?? [];

            if (empty($items)) {
                throw new \Exception('Invoice harus memiliki minimal 1 produk');
            }

            $subtotal = collect($items)->sum(
                fn($item) => ((int) $item['qty']) * ((int) $item['price'])
            );

            $discountPercent = (float) ($data['discount_percent'] ?? 0);
            $discountPercent = min($discountPercent, 100); // Max 100%

            $discount = (int) ($subtotal * ($discountPercent / 100));
            $total = max(0, $subtotal - $discount);

            // Update invoice
            $record->update([
                'member_id'  => $data['member_id'] ?? null,
                // 'voucher_id' => $voucherId,
                'subtotal'   => $subtotal,
                'discount_percent' => $discountPercent,
                'discount'   => $discount,
                'total'      => $total,
            ]);

            // Hapus items lama, insert ulang
            $record->items()->delete();

            foreach ($items as $item) {
                $record->items()->create([
                    'invoice_id' => $record->id,
                    'product_id' => $item['product_id'],
                    'qty'        => $item['qty'],
                    'price'      => $item['price'],
                    'total'      => ((int) $item['qty']) * ((int) $item['price']),
                ]);
            }

            return $record;
        });
    }
}
