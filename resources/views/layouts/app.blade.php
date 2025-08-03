@php
    use App\Models\Setting;
    $settings = Setting::first();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings->store_name ?? 'Kasir App' }}</title>

    <link rel="icon" type="image/png"
          href="{{ $settings && $settings->logo ? asset('storage/'.$settings->logo) : asset('image/logo.png') }}">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --sidebar-bg: #e8f5e9;
            --sidebar-width: 200px;
            --content-bg: #ffffff;
            --active-color: #2e7d32;
            --hover-color: #c8e6c9;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f5f5;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            min-height: 100vh;
            transition: all 0.3s ease;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        .sidebar.collapsed {
            margin-left: -200px;
        }

        .sidebar-brand {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid #c8e6c9;
        }

        .sidebar-brand .brand-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-brand img {
            height: 45px;
            margin-right: 8px;
        }

        .sidebar-brand .brand-text {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2e7d32;
        }

        .sidebar-nav {
            padding: 0;
            list-style: none;
            margin-top: 1rem;
            flex: 1;
        }

        .sidebar-nav .nav-item {
            margin: 0;
        }

        .sidebar-nav .nav-link {
            padding: 0.7rem 1rem;
            color: #333;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            transition: all 0.2s;
            text-decoration: none;
        }

        .sidebar-nav .nav-link i {
            margin-right: 8px;
            width: 18px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar-nav .nav-link:hover {
            background-color: var(--hover-color);
            color: #000;
        }

        .sidebar-nav .nav-link.active {
            background-color: var(--active-color);
            color: white;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            background-color: var(--content-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            width: 100%;
        }

        .main-content.full {
            margin-left: 0;
        }

        /* Top Navbar */
        .top-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 8px 15px;
            border-bottom: 1px solid #ddd;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .hamburger-btn {
            background: none;
            border: none;
            font-size: 1.3rem;
            color: #2e7d32;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-menu .username {
            font-weight: bold;
            color: #2e7d32;
            font-size: 0.9rem;
        }

        .logout-btn {
            background: none;
            border: none;
            color: #333;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .logout-btn:hover {
            color: red;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                margin-left: -200px;
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-wrapper">
                @if($settings && $settings->logo)
                    <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo">
                @else
                    <img src="{{ asset('image/logo.png') }}" alt="Logo">
                @endif
                <span class="brand-text">{{ $settings->store_name ?? 'KasirApp' }}</span>
            </div>
        </div>

        <ul class="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="fas fa-box"></i> Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <i class="fas fa-tags"></i> Kategori
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('transactions*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                    <i class="fas fa-shopping-cart"></i> Transaksi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('reports*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                    <i class="fas fa-chart-bar"></i> Laporan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                    <i class="fas fa-cogs"></i> Pengaturan Sistem
                </a>
            </li>
            @if(Auth::check() && in_array(Auth::user()->role, ['admin','super_admin']))
            <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i> Manajemen User
                </a>
            </li>
            @endif
        </ul>
    </div>

    <div class="main-content" id="main-content">
        <div class="top-navbar">
            <button class="hamburger-btn" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            @auth
            <div class="user-menu">
                <i class="fas fa-user text-success"></i>
                <span class="username">Halo, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
            @endauth
        </div>

        <div class="p-3">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleBtn = document.getElementById('sidebarToggle');

            toggleBtn.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('full');
                }
            });

            // Tampil SweetAlert2 untuk pesan session
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                });
            @endif
        });

        // Menutup sidebar di mobile saat mengklik di luar sidebar
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                // Periksa apakah klik terjadi di luar sidebar dan bukan pada tombol toggle
                if (!sidebar.contains(event.target) && event.target !== toggleBtn) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>