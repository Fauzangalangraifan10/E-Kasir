@php
    use App\Models\Setting;
    $settings = Setting::first();
@endphp

<x-guest-layout>
    <style>
        body {
            background: linear-gradient(135deg, #2f855a, #38a169);
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 10px;
        }

        .login-card {
            background: #fff;
            width: 100%;
            max-width: 370px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 25px;
            text-align: center;
            animation: fadeIn 0.6s ease-in-out;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }

        /* Background efek */
        .login-card::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(56, 161, 105, 0.08) 0%, transparent 70%);
            transform: rotate(25deg);
            z-index: 0;
            pointer-events: none; /* FIX supaya input & tombol bisa diklik */
        }

        /* Konten form berada di atas layer dekorasi */
        .login-card * {
            position: relative;
            z-index: 1;
        }

        .login-logo {
            max-width: 70px;
            margin: 0 auto 12px;
            display: block;
            animation: scaleUp 0.5s ease;
        }

        .login-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2f855a;
            margin-bottom: 5px;
        }

        .text-muted {
            color: #718096;
            font-size: 0.85rem;
            margin-bottom: 15px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .input-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .text-input {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 10px 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .text-input:focus {
            outline: none;
            border-color: #38a169;
            box-shadow: 0 0 0 2px rgba(56, 161, 105, 0.2);
        }

        .btn-primary {
            background: linear-gradient(to right, #2f855a, #38a169);
            border: none;
            color: white;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary:hover {
            transform: scale(1.03);
            background: linear-gradient(to right, #276749, #2f855a);
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }

        .remember-me label {
            font-size: 0.8rem;
            color: #4a5568;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 14px;
            height: 14px;
            cursor: pointer;
        }

        .extra-links {
            margin-top: 15px;
            font-size: 0.8rem;
            text-align: center;
        }

        .extra-links a {
            color: #2f855a;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .extra-links a:hover {
            color: #276749;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleUp {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        @media (max-width: 480px) {
            .login-card {
                max-width: 92%;
                padding: 20px;
            }
            .login-logo {
                max-width: 55px;
            }
        }
    </style>

    <div class="login-container">
        <div class="login-card">
            <img src="{{ $settings && $settings->logo ? asset('storage/'.$settings->logo) : asset('image/logo.png') }}" 
                 alt="Logo Toko" class="login-logo">

            <h1 class="login-title">{{ $settings->store_name ?? 'E-Kasir Login' }}</h1>
            <p class="text-muted">Masuk untuk mengelola transaksi kasir</p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="input-label">Email</label>
                    <input id="email" class="text-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="password" class="input-label">Password</label>
                    <input id="password" class="text-input" type="password" name="password" required autocomplete="current-password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="remember-me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Ingat saya</label>
                </div>

                <button type="submit" class="btn-primary">Masuk</button>

                <div class="extra-links">
                    @if (Route::has('password.request'))
                        <p><a href="{{ route('password.request') }}">Lupa kata sandi?</a></p>
                    @endif
                    <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
