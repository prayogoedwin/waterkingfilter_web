<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use SoftDeletes, LogsActivity;

     public function getActivitylogOptions(): LogOptions
        {
            return LogOptions::defaults()
                ->logAll() // Log semua atribut
                ->logOnlyDirty() // Hanya log field yang berubah
                ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
                ->setDescriptionForEvent(fn(string $eventName) => "Order {$eventName}");
        }

    protected $table = 'orders';

    protected $fillable = [
        'member_id',
        'produk_stok_varians_id',
        'produk_id',
        'varian',
        'ukuran',
        'jumlah',
        'poin_satuan',
        'poin_total',
        'alamat_pengiriman',
        'status_order',
        'status_kirim',
        'resi',
        'foto',
    ];

    /**
     * Relasi ke Member
     */

     // Di model Produk (misal: Produk.php)
    protected static function boot()
    {
        parent::boot();

       // Hapus file saat record dihapus
        static::deleted(function ($produk) {
            Cache::forget('order_data');
        });

        // Hapus file lama saat foto diupdate
        static::updating(function ($produk) {
            if ($produk->isDirty('foto') && $produk->getOriginal('foto')) {
                Storage::disk('public')->delete($produk->getOriginal('foto'));
            }
            Cache::forget('order_data');
        });

        

        
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Relasi ke ProdukStokVarian
     */
    public function produkStokVarian()
    {
        return $this->belongsTo(ProdukStokVarian::class, 'produk_stok_varians_id');
    }
}
