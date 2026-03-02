<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

// use Illuminate\Database\Eloquent\Model;

class Partner extends Authenticatable
{
    use HasApiTokens;
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
    ];

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class);
    }
}
