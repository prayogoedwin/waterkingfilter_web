<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $guarded = ['id'];

    public function tipe()
    {
        return $this->belongsTo(VoucherTipe::class, 'voucher_tipe_id');
    }

    public function jenis()
    {
        return $this->belongsTo(VoucherJenis::class, 'voucher_jenis_id');
    }

    public function penggunaan()
    {
        return $this->belongsTo(VoucherPenggunaan::class, 'voucher_penggunaan_id');
    }

    public function partner()
    {
        return $this->belongsTo(VoucherPartner::class, 'voucher_partner_id');
    }

    public function memberVouchers()
    {
        return $this->hasMany(MemberVoucher::class);
    }

    public function invoice()
    {
        return $this->hasMany(Member::class);
    }
}
