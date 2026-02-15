<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Banner extends Model
{

    use SoftDeletes,  LogsActivity;

    public function getActivitylogOptions(): LogOptions
        {
            return LogOptions::defaults()
                 ->logAll() // Log semua atribut
                ->logOnlyDirty() // Hanya log field yang berubah
                ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
                ->setDescriptionForEvent(fn(string $eventName) => "Banner {$eventName}");
        }


    protected $table = 'banners';
    protected $primaryKey = 'id';

    protected static function booted()
    {
        static::deleted(function (Banner $banner) {
            // Hapus file saat model dihapus
            if ($banner->foto) {
                Storage::disk('public')->delete($banner->foto);
            }
            Cache::forget('banner_data');
        });

        static::saved(function () {
            Cache::forget('banner_data');
        });
    }

     protected $fillable = [
        'judul',
        'status',
        'foto'
    ];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
