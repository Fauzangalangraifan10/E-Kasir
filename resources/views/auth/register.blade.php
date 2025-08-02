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
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
            margin: 0 auto;
        }

        .register-logo {
            max-width: 60px;
            margin: 0 auto 10px;
            display: block;
        }

        .register-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2f855a;
            margin-bottom: 6px;
        }

        .text-muted {
            color: #718096;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 8px;
        }

        .input-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .text-input, .select-input {
            width: 100%;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .text-input:focus, .select-input:focus {
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
        }

        .extra-links a:hover {
            text-decoration: underline;
        }
    </style>

    <div class="register-container">
        <div class="register-card">
            <img src="{{ $settings && $settings->logo ? asset('storage/'.$settings->logo) : asset('image/logo.png') }}" 
                 alt="Logo Toko" class="register-logo">

            <h1 class="register-title">Pendaftaran Akun</h1>
            <p class="text-muted">Buat akun baru untuk mengelola kasir</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name" class="input-label">Nama</label>
                    <input id="name" class="text-input" type="text" name="name" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="email" class="input-label">Email</label>
                    <input id="email" class="text-input" type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="password" class="input-label">Kata Sandi</label>
                    <input id="password" class="text-input" type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="input-label">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" class="text-input" type="password" name="password_confirmation" required />
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
                    <a class="extra-links" href="{{ route('login') }}">Sudah punya akun?</a>
                    <button type="submit" class="btn-primary">Daftar</button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
