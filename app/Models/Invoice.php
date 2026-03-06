<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log semua atribut
            ->logOnlyDirty() // Hanya log field yang berubah
            ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
            ->setDescriptionForEvent(fn(string $eventName) => "Invoice {$eventName}");
    }
    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
