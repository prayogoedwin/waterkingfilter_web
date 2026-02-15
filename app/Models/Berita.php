<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Berita extends Model
{
    protected $table = 'beritas';

     use LogsActivity;

    public function getActivitylogOptions(): LogOptions
        {
            return LogOptions::defaults()
                 ->logAll() // Log semua atribut
                ->logOnlyDirty() // Hanya log field yang berubah
                ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
                ->setDescriptionForEvent(fn(string $eventName) => "Berita {$eventName}");
        }


    protected $fillable = [
        'cover',
        'judul',
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
            Cache::forget('beritas_data');
        });

        static::deleted(function () {
            Cache::forget('beritas_data');
        });
    }
}
