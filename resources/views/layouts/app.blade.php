<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KosPin - Platform Kos Terpercaya')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }
        
        .nav-link {
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
        
        .card {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            color: white;
            padding: 4rem 0;
        }
        
        .search-card {
            border-radius: 16px;
            padding: 2rem;
            margin-top: -3rem;
            position: relative;
            z-index: 10;
        }
        
        .room-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
            gap: 10px;
            max-width: 500px;
        }
        
        .room-box {
            aspect-ratio: 1;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .room-box.available {
            background-color: #dcfce7;
            border-color: var(--success-color);
            color: var(--success-color);
        }
        
        .room-box.occupied {
            background-color: #fee2e2;
            border-color: var(--danger-color);
            color: var(--danger-color);
        }
        
        .room-box:hover {
            transform: scale(1.05);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
        }
        
        .footer {
            background-color: #1f2937;
            color: #d1d5db;
            padding: 2rem 0;
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem 0;
            }
            
            .search-card {
                margin: 1rem;
                padding: 1.5rem;
                margin-top: -2rem;
            }
            
            .room-grid {
                grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-home me-2"></i>KosPin
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('search') }}">Cari Kos</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        {{-- Tampilkan Dashboard Owner jika owner (atau admin juga bisa lihat Dashboard owner jika dia owner) --}}
                        @if(auth()->user()->isOwner())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('owner.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                        @endif

                        {{-- Tampilkan link Admin khusus untuk admin --}}
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.index') }}">
                                    <i class="fas fa-user-shield me-1"></i>Admin Panel
                                </a>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>

                                {{-- Tampilkan link pengaturan admin di dalam dropdown juga (opsional) --}}
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.index') }}">
                                        <i class="fas fa-user-shield me-2"></i>Admin Panel
                                    </a></li>
                                @endif

                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-home me-2"></i>KosPin
                    </h5>
                    <p>Platform terpercaya untuk mencari dan mengelola kos di seluruh Indonesia. Temukan kos impian Anda dengan mudah dan aman.</p>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="text-white mb-3">Navigasi</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-decoration-none text-light">Beranda</a></li>
                        <li><a href="{{ route('search') }}" class="text-decoration-none text-light">Cari Kos</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6 class="text-white mb-3">Kontak</h6>
                    <p><i class="fas fa-envelope me-2"></i>kospincenter@gmail.com</p>
                    <p><i class="fas fa-phone me-2"></i>+6285333271808</p>
                    <p><i class="fab fa-whatsapp me-2"></i>+6285333271808</p>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6 class="text-white mb-3">Ikuti Kami</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; 2025 KosPin. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @yield('scripts')
</body>
</html>