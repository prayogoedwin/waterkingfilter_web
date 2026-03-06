<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HistoryKeuanganPartner extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log semua atribut
            ->logOnlyDirty() // Hanya log field yang berubah
            ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
            ->setDescriptionForEvent(fn(string $eventName) => "History Keuangan Partner {$eventName}");
    }
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
