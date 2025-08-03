@extends('layouts.auth')

@section('content')
    <style>
        .reset-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 10px;
        }

        .reset-card {
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

        .reset-card::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(56, 161, 105, 0.08) 0%, transparent 70%);
            transform: rotate(25deg);
            pointer-events: none; /* FIX supaya input & tombol bisa diklik */
        }

        /* Semua elemen di dalam card harus berada di atas layer dekorasi */
        .reset-card * {
            position: relative;
            z-index: 1;
        }

        .reset-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2f855a;
            margin-bottom: 5px;
        }

        .reset-subtitle {
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

        .back-link {
            display: block;
            margin-top: 15px;
            font-size: 0.8rem;
            color: #2f855a;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #276749;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="reset-container">
        <div class="reset-card">
            <h1 class="reset-title">🔒 Reset Password</h1>
            <p class="reset-subtitle">Masukkan password baru Anda untuk melanjutkan</p>

            <form method="POST" action="{{ route('password.reset.direct') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label for="password" class="input-label">Password Baru</label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="text-input"
                           placeholder="Minimal 8 karakter"
                           required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="input-label">Konfirmasi Password</label>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           class="text-input"
                           placeholder="Ulangi password baru"
                           required>
                </div>

                <button type="submit" class="btn-primary">Reset Password</button>

                <a href="{{ route('login') }}" class="back-link">← Kembali ke Halaman Login</a>
            </form>
        </div>
    </div>
@endsection
