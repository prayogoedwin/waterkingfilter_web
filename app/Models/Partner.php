<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

// use Illuminate\Database\Eloquent\Model;

class Partner extends Authenticatable
{
    use HasApiTokens, LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log semua atribut
            ->logOnlyDirty() // Hanya log field yang berubah
            ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
            ->setDescriptionForEvent(fn(string $eventName) => "Partner {$eventName}");
    }
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
    ];

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class);
    }

    public function historyKeuangan()
    {
        return $this->hasMany(HistoryKeuanganPartner::class);
    }

    public function historyClaimVoucher()
    {
        return $this->hasMany(VoucherClaimHistory::class, 'partner_id');
    }

    /**
     * Hitung total pendapatan dari claim voucher
     */
    public function getTotalPendapatanAttribute(): int
    {
        return $this->historyClaimVoucher()->sum('nominal_claim');
    }

    /**
     * Hitung total penarikan (withdrawal)
     */
    public function getTotalWithdrawalAttribute(): int
    {
        return $this->historyKeuangan()->withdrawal()->sum('nominal');
    }

    /**
     * Hitung total topup dari admin
     */
    public function getTotalTopupAttribute(): int
    {
        return $this->historyKeuangan()->topup()->sum('nominal');
    }

    /**
     * Hitung saldo wallet
     * Formula: (Total Pendapatan + Total Topup) - Total Withdrawal
     */
    public function getSaldoWalletAttribute(): int
    {
        return ($this->total_pendapatan + $this->total_topup) - $this->total_withdrawal;
    }
}
