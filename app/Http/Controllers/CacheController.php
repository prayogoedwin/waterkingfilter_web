<?php

// Controller/CacheController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    // public function clearAll()
    // {
    //     Cache::flush();
    //     return response()->json(['message' => 'Semua cache berhasil dihapus']);
    // }


    public function clearAll()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Cache::flush();
        return response()->json(['message' => 'Semua cache berhasil dihapus']);
    }

}
