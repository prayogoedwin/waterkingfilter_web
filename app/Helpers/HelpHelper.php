<?php

use Illuminate\Support\Facades\Cache;

if (!function_exists('addCacheWithRegistry')) {
    function addCacheWithRegistry($key, $expiration, $callback) {
        $value = Cache::remember($key, $expiration, $callback);

        $cacheKeys = Cache::get('custom_cache_keys', []);
        if (!in_array($key, $cacheKeys)) {
            $cacheKeys[] = $key;
            Cache::forever('custom_cache_keys', $cacheKeys);
        }

        return $value;
    }
}


if (!function_exists('clearRegisteredCaches')) {
    function clearRegisteredCaches() {
        $keys = Cache::get('custom_cache_keys', []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget('custom_cache_keys');
    }
}
