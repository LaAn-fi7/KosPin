@extends('layouts.app')

@section('title', 'KosPin - Cari Kos Terbaik untuk Anda')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">
                    Temukan Kos Impian Anda
                </h1>
                <p class="lead mb-4">
                    Platform terpercaya untuk mencari dan menyewa kos dengan fasilitas lengkap dan harga terjangkau di seluruh Palembang
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center text-white-50">
                        <i class="fas fa-check-circle me-2"></i>
                        <span>Kos Terverifikasi</span>
                    </div>
                    <div class="d-flex align-items-center text-white-50">
                        <i class="fas fa-shield-alt me-2"></i>
                        <span>Transaksi Aman</span>
                    </div>
                    <div class="d-flex align-items-center text-white-50">
                        <i class="fas fa-headset me-2"></i>
                        <span>Support 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Card -->
<section class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card search-card shadow-lg">
                <form action="{{ route('search') }}" method="GET" class="row g-3">
                    <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-search me-1"></i>Nama
                            </label>
                            <input type="text" class="form-control" name="q" placeholder="Nama kos" value="{{ request('q') }}">
                        </div>

                        <!-- <div class="col-lg-2 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-map-marker-alt me-1"></i>Lokasi
                            </label>
                            <input type="text" class="form-control" name="city" placeholder="Daerah" value="{{ request('city') }}">
                        </div> -->

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">Kecamatan</label>
                            <select class="form-select" name="district">
                                <option value="">Semua Kecamatan</option>
                                @foreach(config('palembang.districts', []) as $d)
                                    <option value="{{ $d }}" {{ request('district') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label class="form-label fw-semibold">Harga Min</label>
                            <select class="form-select" name="min_price">
                                <option value="">Tidak ada</option>
                                <option value="500000" {{ request('min_price') == '500000' ? 'selected' : '' }}>Rp 500rb</option>
                                <option value="1000000" {{ request('min_price') == '1000000' ? 'selected' : '' }}>Rp 1jt</option>
                                <option value="1500000" {{ request('min_price') == '1500000' ? 'selected' : '' }}>Rp 1.5jt</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label class="form-label fw-semibold">Harga Max</label>
                            <select class="form-select" name="max_price">
                                <option value="">Tidak ada</option>
                                <option value="500000" {{ request('max_price') == '500000' ? 'selected' : '' }}>Rp 500rb</option>
                                <option value="1000000" {{ request('max_price') == '1000000' ? 'selected' : '' }}>Rp 1jt</option>
                                <option value="1500000" {{ request('max_price') == '1500000' ? 'selected' : '' }}>Rp 1.5jt</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label class="form-label fw-semibold">Gender</label>
                            <select class="form-select" name="gender">
                                <option value="">Semua</option>
                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Pria</option>
                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Wanita</option>
                                <option value="mixed" {{ request('gender') == 'mixed' ? 'selected' : '' }}>Campur</option>
                            </select>
                        </div>
                        
                        <div class="col-lg-12 col-md-12 d-flex justify-content-end">
                            <div class="w-100 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Cari
                                </button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section 
<section class="container my-5">
    <div class="row text-center">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <i class="fas fa-home fa-2x mb-3"></i>
                <h3 class="fw-bold">1000+</h3>
                <p class="mb-0">Kos Terdaftar</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <i class="fas fa-users fa-2x mb-3"></i>
                <h3 class="fw-bold">5000+</h3>
                <p class="mb-0">Penghuni Puas</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <i class="fas fa-map-marked-alt fa-2x mb-3"></i>
                <h3 class="fw-bold">50+</h3>
                <p class="mb-0">Kota di Indonesia</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <i class="fas fa-star fa-2x mb-3"></i>
                <h3 class="fw-bold">4.8</h3>
                <p class="mb-0">Rating Pengguna</p>
            </div>
        </div>
    </div>
</section>-->

<!-- Featured Kos -->
<section class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold text-center mb-2">Rekomendasi</h2>
            <p class="text-muted text-center">Kos-kos terbaik dengan rating tinggi dan fasilitas lengkap</p>
        </div>
    </div>
    
    <div class="row">
        @forelse($featuredKos as $kos)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="position-relative">
                        <img src="{{ $kos->main_image }}" class="card-img-top" alt="{{ $kos->name }}" style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-success">
                                {{ $kos->getAvailableRoomsCount() }} kamar tersedia
                            </span>
                        </div>
                        @if($kos->gender == 'male')
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-info">
                                    <i class="fas fa-mars me-1"></i>Pria
                                </span>
                            </div>
                        @elseif($kos->gender == 'female')
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-warning">
                                    <i class="fas fa-venus me-1"></i>Wanita
                                </span>
                            </div>
                        @else
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-secondary">
                                    <i class="fas fa-venus-mars me-1"></i>Campur
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">{{ $kos->name }}</h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $kos->city }}, {{ $kos->province }}
                        </p>
                        <p class="card-text text-muted small">{{ Str::limit($kos->description, 80) }}</p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="h5 fw-bold text-primary mb-0">
                                        Rp {{ number_format($kos->price_per_month, 0, ',', '.') }}
                                    </span>
                                    <small class="text-muted">/bulan</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">
                                        {{ $kos->getTotalRoomsCount() }} total kamar
                                    </small>
                                </div>
                            </div>
                            
                            @if($kos->facilities)
                                <div class="mb-3">
                                    @foreach(array_slice($kos->facilities, 0, 3) as $facility)
                                        <span class="badge bg-light text-dark me-1 mb-1">
                                            <i class="fas fa-check me-1"></i>{{ $facility }}
                                        </span>
                                    @endforeach
                                    @if(count($kos->facilities) > 3)
                                        <span class="badge bg-light text-dark">
                                            +{{ count($kos->facilities) - 3 }} lainnya
                                        </span>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('kos.show', $kos->id) }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <div class="py-5">
                    <i class="fas fa-home fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum ada kos terdaftar</h4>
                    <p class="text-muted">Daftarkan kos Anda sekarang juga!</p>
                    @auth
                        @if(auth()->user()->isOwner())
                            <a href="{{ route('owner.create-kos') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Kos
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        @endforelse
    </div>
    
    @if($featuredKos->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('search') }}" class="btn btn-outline-primary btn-lg">
                Lihat Semua Kos <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    @endif
</section>

<!-- Call to Action for Owners -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3">Punya Kos? Daftarkan Sekarang!</h2>
                <p class="lead mb-4">
                    Bergabunglah dengan ribuan pemilik kos yang sudah merasakan kemudahan mengelola properti melalui platform kami
                </p>
                <div class="row justify-content-center mb-4">
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-chart-line fa-2x mb-3"></i>
                        <h5>Tingkatkan Okupansi</h5>
                        <p class="mb-0">Jangkauan lebih luas untuk calon penghuni</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-cogs fa-2x mb-3"></i>
                        <h5>Kelola dengan Mudah</h5>
                        <p class="mb-0">Dashboard lengkap untuk manajemen kos</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-money-check-alt fa-2x mb-3"></i>
                        <h5>Pembayaran Aman</h5>
                        <p class="mb-0">Sistem pembayaran terintegrasi</p>
                    </div>
                </div>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-user-plus me-2"></i>Daftar Sebagai Owner
                    </a>
                @else
                    @if(!auth()->user()->isOwner())
                        <p class="mb-3">Ingin menjadi owner? Hubungi tim kami!</p>
                        <a href="#" class="btn btn-light btn-lg">
                            <i class="fas fa-phone me-2"></i>Hubungi Kami
                        </a>
                    @else
                        <a href="{{ route('owner.create-kos') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-plus me-2"></i>Tambah Kos Baru
                        </a>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</section>
@endsection