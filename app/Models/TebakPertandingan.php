<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TebakPertandingan extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

     public function getActivitylogOptions(): LogOptions
        {
            return LogOptions::defaults()
                ->logAll() // Log semua atribut
                ->logOnlyDirty() // Hanya log field yang berubah
                ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
                ->setDescriptionForEvent(fn(string $eventName) => "Tebakan {$eventName}");
        }

    protected $table = 'tebak_pertandingans';

    protected $fillable = [
        'member_id',
        'pertandingan_id',
        'tebak_pemenang_id',
        'tebak_pemenang',
        'status_tebak_pemenang',
        'poin_tebak_pemenang',
        'tebak_metode',
        'status_tebak_metode',
        'poin_tebak_metode',
        'tebak_ronde',
        'status_tebak_ronde',
        'poin_tebak_ronde',
        'poin_all',
        'update_poin_to_profil'
    ];

    protected $casts = [
        'status_tebak_pemenang' => 'integer',
        'poin_tebak_pemenang' => 'integer',
        'status_tebak_metode' => 'integer',
        'poin_tebak_metode' => 'integer',
        'status_tebak_ronde' => 'integer',
        'poin_tebak_ronde' => 'integer',
        'poin_all' => 'integer',
        'update_poin_to_profil' => 'integer',
    ];

    protected $attributes = [
        'tebak_pemenang_id' => 0,
        'status_tebak_pemenang' => 0,
        'poin_tebak_pemenang' => 0,
        'status_tebak_metode' => 0,
        'poin_tebak_metode' => 0,
        'status_tebak_ronde' => 0,
        'poin_tebak_ronde' => 0,
        'poin_all' => 0,
        'update_poin_to_profil' => 0,
    ];

    /**
     * Relasi ke model Member
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    /**
     * Relasi ke model Pertandingan
     */
    public function pertandingan()
    {
        return $this->belongsTo(Pertandingan::class, 'pertandingan_id', 'id');
    }
}