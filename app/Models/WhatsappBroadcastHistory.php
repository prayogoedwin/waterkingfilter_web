<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappBroadcastHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'judul_pesan',
        'isi_pesan',
        'target_type',
        'member_id',
        'no_whatsapp',
        'status',
        'error_message',
        'response_body',
        'created_by',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

