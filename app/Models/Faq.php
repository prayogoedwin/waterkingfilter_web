<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Faq extends Model
{
    protected $table = 'faqs';

     use LogsActivity;

    public function getActivitylogOptions(): LogOptions
        {
            return LogOptions::defaults()
                 ->logAll() // Log semua atribut
                ->logOnlyDirty() // Hanya log field yang berubah
                ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
                ->setDescriptionForEvent(fn(string $eventName) => "FAQ {$eventName}");
        }


    protected $fillable = [
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
            Cache::forget('faqs_data');
        });

        static::deleted(function () {
            Cache::forget('faqs_data');
        });
    }
}
