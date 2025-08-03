<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    /**
     * Tampilkan halaman lupa password.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Tangani permintaan lupa password tanpa email.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Cek apakah email ada di database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

        // Redirect langsung ke halaman reset password tanpa email
        return redirect()->route('password.reset.direct', ['email' => $user->email]);
    }
}
