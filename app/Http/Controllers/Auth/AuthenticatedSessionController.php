<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Melakukan autentikasi user
        $request->authenticate();

        // Regenerasi session setelah login
        $request->session()->regenerate();

        // Semua role (admin & kasir) diarahkan ke dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Logout user dan hancurkan sesi.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout user
        Auth::guard('web')->logout();

        // Hapus semua sesi
        $request->session()->invalidate();

        // Regenerasi token sesi
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman login
        return redirect('/');
    }
}
