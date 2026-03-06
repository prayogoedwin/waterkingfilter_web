<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class VoucherClaimHistory extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log semua atribut
            ->logOnlyDirty() // Hanya log field yang berubah
            ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
            ->setDescriptionForEvent(fn(string $eventName) => "Voucher Claim History {$eventName}");
    }
    protected $fillable = [
        'voucher_id',
        'member_id',
        'partner_id',
        'invoice_id',
        'persentase_claim',
        'nominal_claim'
    ];

    protected $casts = [
        'claim_at' => 'datetime',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function partner()
    {
        return $this->belongsTo(Member::class, 'partner_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
