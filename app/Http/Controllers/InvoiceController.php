<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function print(Invoice $invoice)
    {
        $invoice->load(['member', 'voucher', 'items.product']);
        return view('invoice.print', compact('invoice'));
    }

    public function struk(Invoice $invoice)
    {
        $invoice->load(['member', 'voucher', 'items.product']);
        return view('invoice.struk', compact('invoice'));
    }
}
