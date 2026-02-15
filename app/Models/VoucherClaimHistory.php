<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherClaimHistory extends Model
{
    protected $fillable = [
        'voucher_id',
        'member_id',
        'partner_id',
        'invoice_id',
        'voucher_name',
        'voucher_jenis',
        'voucher_value',
        'dicount_amount',
        'subtotal',
        'total_before_discount',
        'total_after_discount',
        'claim_at',
    ];

    protected $casts = [
        'claim_at' => 'datetime',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function partner()
    {
        return $this->belongsTo(Member::class, 'partner_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
