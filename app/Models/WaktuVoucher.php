<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WaktuVoucher extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log semua atribut
            ->logOnlyDirty() // Hanya log field yang berubah
            ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
            ->setDescriptionForEvent(fn(string $eventName) => "Voucher Waktu {$eventName}");
    }
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
