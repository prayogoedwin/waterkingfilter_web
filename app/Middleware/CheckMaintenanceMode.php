<?php

namespace App\Middleware;

use Closure;
use Illuminate\Support\Facades\Storage;

class CheckMaintenanceMode
{
    public function handle($request, Closure $next)
    {
        if (Storage::exists('maintenance.json')) {
            $data = json_decode(Storage::get('maintenance.json'), true);
            $enabled = $data['enabled'] ?? false;

            if ($enabled && !$request->is('maintenance') && !$request->is('admin/*')) {
                return redirect('/maintenance');
            }
        }

        return $next($request);
    }
}

?>