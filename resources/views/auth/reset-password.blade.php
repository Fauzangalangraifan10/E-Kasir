<x-guest-layout>
    <div class="login-container">
        <div class="login-card">
            <h1 class="login-title">Reset Password</h1>
            <p class="text-muted">Masukkan password baru Anda</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="text-input" required>
                    @error('password') <span style="color:red; font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="text-input" required>
                </div>

                <button type="submit" class="btn-primary">Reset Password</button>
                <div class="extra-links">
                    <p><a href="{{ route('login') }}">Kembali ke Login</a></p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
