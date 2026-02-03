<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        // 2. Cek apakah role user adalah 'admin'
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            // Jika bukan admin, abort 403 (Forbidden) atau redirect ke dashboard siswa
            abort(403, 'AKSES DITOLAK: Halaman ini hanya untuk Administrator.');
        }

        return $next($request);
    }
}