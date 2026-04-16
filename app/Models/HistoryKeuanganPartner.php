<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HistoryKeuanganPartner extends Model
{
    use LogsActivity;
    public const TIPE_WITHDRAWAL = 'withdrawal';
    public const TIPE_TOPUP = 'topup';
    public const TIPE_CLAIM_DEBIT = 'claim_debit';

    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_PROSES = 'proses';
    public const STATUS_TERBAYAR = 'terbayar';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log semua atribut
            ->logOnlyDirty() // Hanya log field yang berubah
            ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
            ->setDescriptionForEvent(fn(string $eventName) => "History Keuangan Partner {$eventName}");
    }
    protected $guarded = ['id'];

    public static function statusOptions(): array
    {
        return [
            self::STATUS_MENUNGGU => 'Menunggu',
            self::STATUS_PROSES => 'Proses',
            self::STATUS_TERBAYAR => 'Terbayar',
        ];
    }

    public static function statusHelper(): string
    {
        return 'Menunggu: request baru masuk, Proses: sedang diproses admin, Terbayar: withdrawal selesai dibayarkan.';
    }

    public static function tipeOptions(): array
    {
        return [
            self::TIPE_WITHDRAWAL => 'Penarikan',
            self::TIPE_TOPUP => 'Kredit Modal',
            self::TIPE_CLAIM_DEBIT => 'Debit Claim Voucher',
        ];
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Scope untuk withdrawal saja
     */
    public function scopeWithdrawal($query)
    {
        return $query->where('tipe', self::TIPE_WITHDRAWAL);
    }

    /**
     * Scope untuk topup saja
     */
    public function scopeTopup($query)
    {
        return $query->where('tipe', self::TIPE_TOPUP);
    }

    public function scopeClaimDebit($query)
    {
        return $query->where('tipe', self::TIPE_CLAIM_DEBIT);
    }
}
