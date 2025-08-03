<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('image/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles') {{-- <=== WAJIB supaya CSS custom di halaman masuk --}}
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-green-50 to-green-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        {{ $slot ?? '' }}
        @yield('content')
    </div>

    @stack('scripts') {{-- <=== WAJIB supaya JS custom di halaman masuk --}}
</body>
</html>
