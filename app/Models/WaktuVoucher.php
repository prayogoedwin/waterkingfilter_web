<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaktuVoucher extends Model
{
    protected $guarded = ['id'];

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    /**
     * Get label waktu untuk display
     */
    public function getLabelAttribute(): string
    {
        return match ($this->waktu) {
            'setiap_hari' => 'Setiap Hari',
            'tanggal_fix' => 'Tanggal Tetap',
            'periode_tanggal' => 'Periode Tanggal',
            'tanggal_tertentu' => 'Tanggal Tertentu',
            'hari_tertentu' => 'Hari Tertentu',
            default => $this->waktu,
        };
    }
}
