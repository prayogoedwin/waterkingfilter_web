<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Produk extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

     public function getActivitylogOptions(): LogOptions
        {
            return LogOptions::defaults()
                ->logAll() // Log semua atribut
                ->logOnlyDirty() // Hanya log field yang berubah
                ->dontSubmitEmptyLogs() // Skip jika tidak ada perubahan
                ->setDescriptionForEvent(fn(string $eventName) => "Produk {$eventName}");
        }
    protected $table = 'produks';

    protected $fillable = [
        'nama',
        'deskripsi',
        'tipe_produk',
        'kategori_produk',
        'kategori_produk_2',
        'poin',
        'status',
        'foto',
    
    ];

    // Di model Produk (misal: Produk.php)
    protected static function boot()
    {
        parent::boot();

       // Hapus file saat record dihapus
        static::deleted(function ($produk) {
            // if ($produk->foto) {
            //     Storage::disk('public')->delete($produk->foto);
            // }
            // Hapus semua varian terkait (soft delete)
            $produk->variants()->delete();
            Cache::forget('produk_data');
        });

        // Hapus file lama saat foto diupdate
        static::updating(function ($produk) {
            if ($produk->isDirty('foto') && $produk->getOriginal('foto')) {
                Storage::disk('public')->delete($produk->getOriginal('foto'));
            }
            Cache::forget('produk_data');
        });

        

        
    }

    // Relasi ke KategoriProduk
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KateegoriProduk::class, 'kategori_produk');
    }

    // Relasi ke TipeProduk
    public function tipe(): BelongsTo
    {
        return $this->belongsTo(TipeProduk::class, 'tipe_produk');
    }

    public function variants(): HasMany // Menggunakan nama 'variants'
    {
        return $this->hasMany(ProdukStokVarian::class, 'produk_id');
    }

    

}
