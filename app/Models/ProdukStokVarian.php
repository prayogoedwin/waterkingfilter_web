<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProdukStokVarian extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

     public function getActivitylogOptions(): LogOptions
        {
            return LogOptions::defaults()
                ->logAll() // Log semua atribut
                ->logOnlyDirty() // Hanya log field yang berubah
                ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
                ->setDescriptionForEvent(fn(string $eventName) => "Varian & Stok {$eventName}");
        }
    protected $table = 'produk_stok_varians';

    protected $fillable = [
        'produk_id',
        'varian',
        'ukuran',
        'stok'
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
