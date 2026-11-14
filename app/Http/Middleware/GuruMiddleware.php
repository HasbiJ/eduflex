<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Kalau belum login, arahkan ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Kalau sudah login tapi role bukan guru, logout lalu tolak
        if (Auth::user()->role !== 'guru') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Akses ditolak. Halaman ini khusus Guru.']);
        }

        // 3. Role guru, lanjutkan request
        return $next($request);
    }
}
