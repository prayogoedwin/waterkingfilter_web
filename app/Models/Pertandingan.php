<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Pertandingan extends Model
{
    use SoftDeletes, LogsActivity;

     public function getActivitylogOptions(): LogOptions
        {
            return LogOptions::defaults()
                ->logAll() // Log semua atribut
                ->logOnlyDirty() // Hanya log field yang berubah
                ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
                ->setDescriptionForEvent(fn(string $eventName) => "Pertandingan {$eventName}");
        }

    protected $table = 'pertandingans';
    protected $primaryKey = 'id';

    protected static function booted()
    {
        static::deleted(function (Pertandingan $pertandingan) {
            // Hapus file saat model dihapus
            if ($pertandingan->pemain_1_foto) {
                Storage::disk('public')->delete($pertandingan->pemain_1_foto);
            }
            if ($pertandingan->pemain_2_foto) {
                Storage::disk('public')->delete($pertandingan->pemain_2_foto);
            }
            if ($pertandingan->poster_pertand) {
                Storage::disk('public')->delete($pertandingan->poster_pertand);
            }

            Cache::forget('tonton_data');
        });

        static::saved(function () {
            Cache::forget('tonton_data');
        });

        
    }

    protected $fillable = [
        'judul',
        'is_special',
        'kategori',
        'pemain_1_id',
        'pemain_1_nama',
        'pemain_1_foto',
        'pemain_1_url_detail',
        'pemain_1_jago',
        'pemain_2_id',
        'pemain_2_nama',
        'pemain_2_foto',
        'pemain_2_url_detail',
        'pemain_2_jago',
        'pemenang',
        'pemenang_poin',
        'metode_menang',
        'metode_menang_poin',
        'ronde',
        'ronde_poin',
        'tanggal_pertandingan',
        'jam_pertandingan',
        'url_nonton_1',
        'url_nonton_2',
        'url_nonton_3',
        'poster_pertand',
        'expired_at',
        'status'
    ];

    protected $casts = [
        'tanggal_pertandingan' => 'date:Y-m-d',
        'jam_pertandingan' => 'datetime:H:i:s',
        'expired_at' => 'datetime:Y-m-d',
    ];

    protected $dates = [
        'tanggal_pertandingan',
        'jam_pertandingan',
        'expired_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $attributes = [
        'is_special' => 0,
        'kategori' => 0,
        'pemain_1_jago' => 0,
        'pemain_2_jago' => 0,
        'pemenang' => 0,
        'pemenang_poin' => 0,
        'metode_menang' => 0,
        'metode_menang_poin' => 0,
        'ronde' => 0,
        'ronde_poin' => 0,
        'status' => 0,
    ];

    public function tebakPertandingans()
    {
        return $this->hasMany(TebakPertandingan::class, 'pertandingan_id');
    }

     
    
}
