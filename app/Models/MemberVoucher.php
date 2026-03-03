<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberVoucher extends Model
{
    protected $guarded = ['id'];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function historyClaims()
    {
        return $this->hasMany(VoucherClaimHistory::class, 'member_id', 'member_id')
            ->where('voucher_id', $this->voucher_id);
    }

    /**
     * Generate barcode untuk member voucher
     * Format: MV-{member_id}-{voucher_id}-{hash}
     */
    public function getBarcodeAttribute(): string
    {
        return 'MV-' . $this->member_id . '-' . $this->voucher_id . '-' . md5($this->id . $this->member_id . $this->voucher_id);
    }

    /**
     * Cek apakah voucher pernah di-claim (ada history)
     */
    public function hasBeenClaimed(): bool
    {
        return $this->historyClaims()->exists();
    }

    /**
     * Get total berapa kali voucher ini sudah di-claim
     */
    public function getClaimCountAttribute(): int
    {
        return $this->historyClaims()->count();
    }

    /**
     * Get last claim date
     */
    public function getLastClaimDateAttribute(): ?string
    {
        $lastClaim = $this->historyClaims()->latest()->first();
        return $lastClaim?->created_at->format('d F Y H:i');
    }

    /**
     * Static method untuk decode barcode
     */
    public static function findByBarcode(string $barcode): ?self
    {
        // Extract dari barcode format: MV-{member_id}-{voucher_id}-{hash}
        $parts = explode('-', $barcode);

        if (count($parts) !== 4 || $parts[0] !== 'MV') {
            return null;
        }

        $memberId = $parts[1];
        $voucherId = $parts[2];
        $hash = $parts[3];

        $memberVoucher = self::where('member_id', $memberId)
            ->where('voucher_id', $voucherId)
            ->first();

        // Validasi hash
        if ($memberVoucher && $hash === md5($memberVoucher->id . $memberVoucher->member_id . $memberVoucher->voucher_id)) {
            return $memberVoucher;
        }

        return null;
    }
}
