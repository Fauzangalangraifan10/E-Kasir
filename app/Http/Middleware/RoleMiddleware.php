<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Cek apakah user dinonaktifkan
        if ($user->status === 'inactive') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda sudah dinonaktifkan.');
        }

        // Jika role user sesuai dengan salah satu yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak sesuai, arahkan ke dashboard dengan pesan peringatan
        return redirect()
            ->route('dashboard')
            ->with('warning', 'Anda tidak memiliki akses ke fitur ini.');
    }
}
