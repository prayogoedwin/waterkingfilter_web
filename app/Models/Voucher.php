<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'specific_dates' => 'array',
        'specific_days' => 'array',
        'fixed_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function tipe()
    {
        return $this->belongsTo(VoucherTipe::class, 'voucher_tipe_id');
    }

    public function jenis()
    {
        return $this->belongsTo(VoucherJenis::class, 'voucher_jenis_id');
    }

    public function penggunaan()
    {
        return $this->belongsTo(VoucherPenggunaan::class, 'voucher_penggunaan_id');
    }

    public function voucherPartner()
    {
        return $this->belongsTo(VoucherPartner::class, 'voucher_partner_id');
    }

    public function memberVouchers()
    {
        return $this->hasMany(MemberVoucher::class);
    }

    public function invoice()
    {
        return $this->hasMany(Member::class);
    }

    public function partners()
    {
        return $this->belongsToMany(Partner::class, 'voucher_partner_details');
    }

    public function partnerDetails()
    {
        return $this->hasMany(VoucherPartnerDetail::class);
    }

    public function waktuVoucher()
    {
        return $this->belongsTo(WaktuVoucher::class);
    }

    /**
     * Check if voucher berlaku di partner tertentu
     */
    public function isValidForPartner(int $partnerId): bool
    {
        return $this->partnerDetails()->where('partner_id', $partnerId)->exists();
    }

    /**
     * Cek apakah voucher masih berlaku (belum expired)
     */
    public function isActive(): bool
    {
        $now = now();

        if ($this->start_date && $now->isBefore($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->isAfter($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Cek apakah voucher dapat digunakan pada tanggal tertentu
     */
    public function canBeUsedOn(Carbon $date): bool
    {
        if (!$this->waktuVoucher) {
            return true;
        }

        return match ($this->waktuVoucher->waktu) {
            'setiap_hari' => true,

            'tanggal_fix' => $this->fixed_date &&
                $date->isSameDay($this->fixed_date),

            'periode_tanggal' => $this->period_start &&
                $this->period_end &&
                $date->between($this->period_start, $this->period_end),

            'tanggal_tertentu' => $this->specific_dates &&
                in_array($date->day, $this->specific_dates),

            'hari_tertentu' => $this->specific_days &&
                in_array(strtolower($date->locale('id')->dayName), $this->specific_days),

            default => false,
        };
    }

    public function canBeUsedToday(): bool
    {
        return $this->canBeUsedOn(now());
    }

    public function getWaktuDescriptionAttribute(): string
    {
        if (!$this->waktuVoucher) {
            return '-';
        }

        return match ($this->waktuVoucher->waktu) {
            'setiap_hari' => 'Setiap hari',
            'tanggal_fix' => 'Tanggal ' . $this->fixed_date?->format('d F Y'),
            'periode_tanggal' => 'Periode ' .
                $this->period_start?->format('d M Y') . ' - ' .
                $this->period_end?->format('d M Y'),
            'tanggal_tertentu' => 'Setiap tanggal: ' .
                implode(', ', $this->specific_dates ?? []),
            'hari_tertentu' => 'Setiap hari: ' .
                implode(', ', array_map('ucfirst', $this->specific_days ?? [])),
            default => '-',
        };
    }

    /**
     * Hitung nominal claim berdasarkan jenis voucher
     */
    public function calculateNominalClaim(int $totalTransaction): int
    {
        return match ($this->jenis->jenis) {
            'gratis' => $totalTransaction,
            'potongan_nominal' => min($this->value, $totalTransaction),
            'potongan_persentase' => (int) ($totalTransaction * ($this->value / 100)),
            'cashback' => (int) $this->value,
            default => 0,
        };
    }
}
