<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherPartnerDetail extends Model
{
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
