<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    /**
     * Menampilkan form reset password langsung
     */
    public function showResetForm(Request $request)
    {
        $email = $request->email;

        // Pastikan email diisi
        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Silakan masukkan email yang valid.']);
        }

        // Cek apakah email ada di database
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Email tidak ditemukan di database.']);
        }

        return view('auth.reset-password-direct', compact('email'));
    }

    /**
     * Proses reset password langsung
     */
    public function resetDirect(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('status', '✅ Password berhasil direset, silakan login kembali.');
    }
}
