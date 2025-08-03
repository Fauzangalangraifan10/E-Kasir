@php
    $appName = config('app.name', 'E-Kasir');
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password - {{ $appName }}</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .email-content {
            max-width: 500px;
            background: #ffffff;
            margin: 30px auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .email-header {
            background: #2f855a;
            color: #ffffff;
            padding: 15px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }
        .email-body {
            padding: 20px;
            font-size: 14px;
            color: #333333;
        }
        .email-body p {
            margin-bottom: 15px;
            line-height: 1.5;
        }
        .btn-reset {
            display: inline-block;
            background: #2f855a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
            margin-top: 15px;
        }
        .email-footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #aaa;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-content">
            <div class="email-header">
                {{ $appName }} - Reset Password
            </div>
            <div class="email-body">
                <p>Halo,</p>
                <p>Kami menerima permintaan untuk mereset password akun Anda di <strong>{{ $appName }}</strong>.</p>
                <p>Silakan klik tombol di bawah ini untuk mengatur password baru Anda:</p>

                <p style="text-align: center;">
                    <a href="{{ $actionUrl }}" class="btn-reset">Reset Password</a>
                </p>

                <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
                <p>Terima kasih,<br>Tim {{ $appName }}</p>
            </div>
            <div class="email-footer">
                &copy; {{ date('Y') }} {{ $appName }}. Semua hak dilindungi.
            </div>
        </div>
    </div>
</body>
</html>
