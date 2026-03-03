<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryKeuanganPartner extends Model
{
    protected $guarded = ['id'];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Scope untuk withdrawal saja
     */
    public function scopeWithdrawal($query)
    {
        return $query->where('tipe', 'withdrawal');
    }

    /**
     * Scope untuk topup saja
     */
    public function scopeTopup($query)
    {
        return $query->where('tipe', 'topup');
    }
}
