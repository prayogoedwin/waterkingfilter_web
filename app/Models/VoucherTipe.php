<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherTipe extends Model
{
    protected $guarded = ['id'];

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }
}
