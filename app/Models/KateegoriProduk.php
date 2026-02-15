<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class KateegoriProduk extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
        {
            return LogOptions::defaults()
                 ->logAll() // Log semua atribut
                ->logOnlyDirty() // Hanya log field yang berubah
                ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
                ->setDescriptionForEvent(fn(string $eventName) => "Kategori Produk {$eventName}");
        }

    protected $table = 'kateegori_produks';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('kategori_produk_data');
        });

        static::deleted(function () {
            Cache::forget('kategori_produk_data');
        });
    }
}
