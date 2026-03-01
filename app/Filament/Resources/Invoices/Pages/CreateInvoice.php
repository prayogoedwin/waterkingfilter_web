<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\MemberVoucher;
use App\Models\Voucher;
use App\Models\VoucherClaimHistory;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['invoice_number'] = 'INV-' . now()->format('YmdHis');
        $data['status'] = 'paid';

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {

            /* ================= ITEMS ================= */

            $items = $data['invoice_items'] ?? [];

            if (empty($items)) {
                throw new \Exception('Invoice harus memiliki minimal 1 produk');
            }

            $subtotal = collect($items)->sum(function ($item) {
                return ((int) $item['qty']) * ((int) $item['price']);
            });

            /* ================= VOUCHER ================= */

            // $discount = 0;
            // $voucherId = $data['voucher_id'] ?? null;

            // if ($voucherId) {

            //     $voucher = Voucher::with('jenis')->findOrFail($voucherId);

            //     $discount = match ($voucher->jenis->jenis) {
            //         'gratis' => $subtotal,
            //         'potongan_nominal' => min($voucher->value, $subtotal),
            //         'potongan_persentase' => (int) ($subtotal * ($voucher->value / 100)),
            //         default => 0,
            //     };

            //     // Validasi kepemilikan voucher
            //     MemberVoucher::where('member_id', $data['member_id'])
            //         ->where('voucher_id', $voucherId)
            //         // ->whereNull('used_at')
            //         ->lockForUpdate()
            //         ->firstOrFail();
            // }

            $discountPercent = (float) ($data['discount_percent'] ?? 0);
            $discountPercent = min($discountPercent, 100); // Max 100%

            $discount = (int) ($subtotal * ($discountPercent / 100));

            $total = max(0, $subtotal - $discount);

            /* ================= POIN MEMBER ================= */

            $member = Member::find($data['member_id']);
            $member->poin_terkini = $member->poin_terkini + $data['tambahan_poin'];
            $member->save();

            /* ================= INVOICE ================= */

            $invoice = Invoice::create([
                'invoice_number' => $data['invoice_number'],
                'status'         => $data['status'],
                'member_id'  => $data['member_id'] ?? null,
                // 'voucher_id' => $voucherId,
                'discount_percent' => $discountPercent,
                'subtotal'   => $subtotal,
                'discount'   => $discount,
                'total'      => $total,
            ]);

            /* ================= ITEMS SAVE ================= */

            foreach ($items as $item) {
                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'qty'        => $item['qty'],
                    'price'      => $item['price'],
                    'total'      => $item['qty'] * $item['price'],
                ]);
            }

            /* ================= MARK VOUCHER USED ================= */

            if ($invoice->voucher_id) {

                $voucher = Voucher::with('jenis')->find($invoice->voucher_id);

                VoucherClaimHistory::create([
                    'voucher_id' => $voucher->id,
                    'member_id' => $invoice->member_id,
                    'invoice_id' => $invoice->id,

                    // snapshot voucher
                    'voucher_name' => $voucher->name,
                    'voucher_jenis' => $voucher->jenis->jenis,
                    'voucher_value' => $voucher->value,
                    'dicount_amount' => $invoice->discount,

                    // transaksi
                    'subtotal' => $invoice->subtotal,
                    'total_before_discount' => $invoice->subtotal,
                    'total_after_discount' => $invoice->total,

                    'claim_at' => now(),
                ]);
            }

            return $invoice;
        });
    }
}
