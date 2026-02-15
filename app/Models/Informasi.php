<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Informasi extends Model
{
    protected $table = 'informasis';

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
        {
            return LogOptions::defaults()
                 ->logAll() // Log semua atribut
                ->logOnlyDirty() // Hanya log field yang berubah
                ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
                ->setDescriptionForEvent(fn(string $eventName) => "Informasi {$eventName}");
        }


    protected $fillable = [
        'slug',
        'nama',
        'description',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('cara_main');
        });

        static::deleted(function () {
            Cache::forget('cara_main');
        });
    }
}
