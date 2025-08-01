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
            /* min-height tidak perlu diubah, biarkan 100vh agar tetap di tengah halaman */
            min-height: 100vh;
            padding: 10px;
        }

        .login-card {
            background: #fff;
            width: 100%;
            max-width: 350px; /* Lebar lebih kecil agar lebih ringkas */
            border-radius: 10px; /* Radius lebih kecil */
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1); /* Bayangan lebih tipis */
            padding: 20px; /* Mengurangi padding dalam agar tidak terlalu tinggi */
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
            margin: 0 auto;
        }

        .login-logo {
            max-width: 60px; /* Ukuran logo lebih kecil */
            margin: 0 auto 10px; /* Mengurangi margin bawah */
            display: block;
        }

        .login-title {
            font-size: 1.1rem; /* Ukuran font lebih kecil */
            font-weight: 700;
            color: #2f855a;
            margin-bottom: 6px; /* Mengurangi margin bawah */
        }

        .text-muted {
            color: #718096;
            font-size: 0.8rem; /* Ukuran font lebih kecil */
            margin-bottom: 15px; /* Mengurangi margin bawah */
        }

        .form-group {
            text-align: left;
            margin-bottom: 10px; /* Mengurangi margin bawah */
        }

        .input-label {
            display: block;
            font-size: 0.8rem; /* Ukuran font lebih kecil */
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 4px; /* Mengurangi margin bawah */
        }

        .text-input {
            width: 100%;
            border-radius: 6px; /* Mengurangi radius sudut */
            border: 1px solid #e2e8f0;
            padding: 8px 12px; /* Mengurangi padding */
            font-size: 0.9rem; /* Ukuran font lebih kecil */
            transition: all 0.3s ease;
        }

        .text-input:focus {
            outline: none;
            border-color: #38a169;
            box-shadow: 0 0 0 1px rgba(56, 161, 105, 0.25);
        }

        .btn-primary {
            background-color: #2f855a;
            border: none;
            color: white;
            width: 100%;
            padding: 10px; /* Mengurangi padding */
            border-radius: 6px; /* Mengurangi radius sudut */
            font-size: 0.9rem; /* Ukuran font lebih kecil */
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #276749;
        }

        .extra-links {
            margin-top: 15px; /* Mengurangi margin atas */
            font-size: 0.75rem; /* Ukuran font lebih kecil */
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

        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px; /* Mengurangi jarak */
            margin-bottom: 10px; /* Mengurangi margin bawah */
        }

        .remember-me label {
            font-size: 0.8rem; /* Ukuran font lebih kecil */
            color: #4a5568;
        }

        .remember-me input[type="checkbox"] {
            border-radius: 3px; /* Mengurangi radius sudut */
            border: 1px solid #e2e8f0;
            color: #2f855a;
            background-color: #fff;
            width: 14px; /* Ukuran checkbox lebih kecil */
            height: 14px;
            cursor: pointer;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); } /* Mengurangi translasi */
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 480px) {
            .login-card {
                max-width: 95%;
                padding: 15px;
            }
            .login-logo {
                max-width: 50px;
            }
            .login-title {
                font-size: 1rem;
            }
            .text-muted {
                font-size: 0.75rem;
            }
            .btn-primary {
                font-size: 0.85rem;
                padding: 8px;
            }
            .extra-links {
                font-size: 0.7rem;
            }
        }
    </style>

    <div class="login-container">
        <div class="login-card">
            <img src="{{ asset('image/logo.png') }}" alt="Logo Toko" class="login-logo">

            <h1 class="login-title">E-Kasir Login</h1>
            <p class="text-muted">Masuk untuk mengelola transaksi kasir</p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="input-label">Email</label>
                    <input id="email" class="text-input"
                                        type="email"
                                        name="email"
                                        :value="old('email')"
                                        required autofocus
                                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="password" class="input-label">Password</label>
                    <input id="password" class="text-input"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="remember-me">
                    <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500"
                                name="remember">
                    <label for="remember_me" class="text-sm text-gray-600">Ingat saya</label>
                </div>

                <div>
                    <button type="submit" class="btn-primary">
                        Masuk
                    </button>
                </div>

                <div class="extra-links">
                    @if (Route::has('password.request'))
                        <p><a href="{{ route('password.request') }}">Lupa kata sandi?</a></p>
                    @endif
                    <p class="mt-2">
                        Belum punya akun?
                        <a href="{{ route('register') }}">Daftar di sini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>