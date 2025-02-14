<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMaintenanceDueMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek hanya jika user sudah login
        if (auth()->check()) {
            $user = auth()->user();
            // Membuat cache key yang unik per user per hari
            $cacheKey = 'maintenance_notified_' . $user->id . '_' . now()->toDateString();

            if (!cache()->has($cacheKey)) {
                \App\Models\PeriodePemeliharaan::checkAllMaintenanceDue();
                // Simpan flag hingga akhir hari
                cache()->put($cacheKey, true, now()->endOfDay());
            }
        }

        return $next($request);
    }
}