<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberVoucher extends Model
{
    protected $guarded = ['id'];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
