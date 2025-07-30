<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir App</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('image/logo.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --sidebar-bg: #e8f5e9; /* Hijau muda untuk sidebar */
            --sidebar-width: 300px; /* Sidebar lebih lebar */
            --content-bg: #ffffff; /* Putih untuk konten utama */
            --active-color: #2e7d32; /* Warna hijau lebih gelap untuk aktif */
            --hover-color: #c8e6c9; /* Warna hover lebih terang */
        }
        
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            min-height: 100vh;
            transition: all 0.3s;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-brand {
            padding: 2rem 1rem;
            text-align: center;
            border-bottom: 1px solid #c8e6c9;
        }

        .sidebar-brand .brand-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-brand img {
            height: 80px; /* Logo lebih besar */
            margin-right: 14px;
        }

        .sidebar-brand .brand-text {
            font-size: 2rem; /* Nama aplikasi lebih besar */
            font-weight: bold;
            color: #2e7d32;
        }
        
        .sidebar-nav {
            padding: 0;
            list-style: none;
            margin-top: 1rem;
        }
        
        .sidebar-nav .nav-item {
            margin: 0;
        }
        
        .sidebar-nav .nav-link {
            padding: 1rem 1.5rem;
            color: #333;
            display: flex;
            align-items: center;
            font-size: 1.1rem; /* Font menu sedikit lebih besar */
            transition: all 0.2s;
        }
        
        .sidebar-nav .nav-link i {
            margin-right: 12px;
            width: 24px;
            text-align: center;
            font-size: 1.2rem;
        }
        
        .sidebar-nav .nav-link:hover {
            background-color: var(--hover-color);
            color: #000;
        }
        
        .sidebar-nav .nav-link.active {
            background-color: var(--active-color);
            color: white;
        }
        
        .main-content {
            flex: 1;
            background-color: var(--content-bg);
            padding: 20px;
            min-height: 100vh;
        }
        
        .user-info {
            padding: 1rem;
            border-top: 1px solid #c8e6c9;
            margin-top: auto;
            font-size: 1rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1000;
                margin-left: -300px;
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
        
        .navbar-toggler {
            display: none;
        }
        
        .logout-btn {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            padding: 0.75rem 1.5rem;
            color: #333;
            font-size: 1rem;
        }
        
        .logout-btn:hover {
            background-color: var(--hover-color);
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-wrapper">
                <img src="{{ asset('image/logo.png') }}" alt="Logo">
                <span class="brand-text">KasirApp</span>
            </div>
        </div>
        
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}"
                   href="{{ route('products.index') }}">
                    <i class="fas fa-box"></i> Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('categories*') ? 'active' : '' }}"
                   href="{{ route('categories.index') }}">
                    <i class="fas fa-tags"></i> Kategori
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('transactions*') ? 'active' : '' }}"
                   href="{{ route('transactions.index') }}">
                    <i class="fas fa-shopping-cart"></i> Transaksi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('reports*') ? 'active' : '' }}"
                   href="{{ route('reports.index') }}">
                    <i class="fas fa-chart-bar"></i> Laporan
                </a>
            </li>
        </ul>
        
        @auth
        <div class="user-info">
            <div class="d-flex align-items-center">
                <i class="fas fa-user me-2"></i>
                <span>Halo, {{ Auth::user()->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </div>
        @endauth
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <button class="btn btn-light d-lg-none mb-3" type="button" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000
                });
                <?php session()->forget('success'); ?>
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                });
                <?php session()->forget('error'); ?>
            @endif
        });

        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                if (!sidebar.contains(event.target) && event.target !== sidebarToggle) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
