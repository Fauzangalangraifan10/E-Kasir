<x-guest-layout>
    <style>
        /* Gaya umum yang sama dengan halaman login */
        body {
            background: linear-gradient(135deg, #2f855a, #38a169);
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        .register-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 10px;
        }

        .register-card {
            background: #fff;
            width: 100%;
            /* Lebar sesuai permintaan */
            max-width: 1500px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            /* Mengurangi padding untuk membuatnya lebih pendek */
            padding: 15px; 
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
            margin: 0 auto;
        }

        .register-logo {
            max-width: 60px;
            /* Mengurangi margin bawah */
            margin: 0 auto 5px; 
            display: block;
        }

        .register-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2f855a;
            /* Mengurangi margin bawah */
            margin-bottom: 5px; 
        }

        .text-muted {
            color: #718096;
            font-size: 0.8rem;
            /* Mengurangi margin bawah */
            margin-bottom: 10px; 
        }

        .form-group {
            text-align: left;
            /* Mengurangi margin bawah */
            margin-bottom: 8px; 
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
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            /* Mengurangi padding vertikal */
            padding: 6px 12px; 
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .text-input:focus {
            outline: none;
            border-color: #38a169;
            box-shadow: 0 0 0 1px rgba(56, 161, 105, 0.25);
        }

        .select-input {
            width: 100%;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            /* Mengurangi padding vertikal */
            padding: 6px 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%234a5568' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M6 9l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 12px;
        }

        .select-input:focus {
            outline: none;
            border-color: #38a169;
            box-shadow: 0 0 0 1px rgba(56, 161, 105, 0.25);
        }
        
        .btn-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }

        .btn-primary {
            background-color: #2f855a;
            border: none;
            color: white;
            /* Mengurangi padding vertikal */
            padding: 6px 16px; 
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #276749;
        }
        
        .extra-links {
            font-size: 0.75rem;
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
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .register-card {
                max-width: 95%;
                padding: 15px;
            }
            .register-logo {
                max-width: 50px;
            }
            .register-title {
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

    <div class="register-container">
        <div class="register-card">
            <img src="{{ asset('image/logo.png') }}" alt="Logo Toko" class="register-logo">
            <h1 class="register-title">Pendaftaran Akun</h1>
            <p class="text-muted">Buat akun baru untuk mengelola kasir</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name" class="input-label">Nama</label>
                    <input id="name" class="text-input"
                           type="text"
                           name="name"
                           :value="old('name')"
                           required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="email" class="input-label">Email</label>
                    <input id="email" class="text-input"
                           type="email"
                           name="email"
                           :value="old('email')"
                           required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="password" class="input-label">Kata Sandi</label>
                    <input id="password" class="text-input"
                           type="password"
                           name="password"
                           required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="input-label">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" class="text-input"
                           type="password"
                           name="password_confirmation"
                           required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="role" class="input-label">Pilih Role</label>
                    <select id="role" name="role" class="select-input">
                        <option value="admin">Admin</option>
                        <option value="kasir">Kasir</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <div class="btn-container">
                    <a class="extra-links" href="{{ route('login') }}">
                        Sudah punya akun?
                    </a>
                    
                    <button type="submit" class="btn-primary">
                        Daftar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>