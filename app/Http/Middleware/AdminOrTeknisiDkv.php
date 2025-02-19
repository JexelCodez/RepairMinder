<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class AdminOrTeknisiDkv
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized access - Please log in.');
        }

        if ($user->role === 'admin') {
            return $next($request);
        }

        if ($user->role === 'teknisi' && Str::lower(optional($user->zoneUser)->zone_name) === 'dkv') {
            return $next($request);
        }

        if ($user->role === 'teknisi' && Str::lower(optional($user->zoneUser)->zone_name) === 'sarpras') {
            return $next($request);
        }

        abort(403, 'Unauthorized access - Admin or Teknisi DKV required.');
    }
}