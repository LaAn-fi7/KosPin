@extends('layouts.app')

@section('title', 'Hasil Pencarian Kos - KosPin')

@section('content')
<div class="container my-4">
    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('search') }}" method="GET" class="row g-3">
                        <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-search me-1"></i>Nama
                                </label>
                                <input type="text" class="form-control" name="q" placeholder="Nama" value="{{ request('q') }}">
                            </div>

                            <!-- <div class="col-lg-3 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt me-1"></i>Lokasi
                                </label>
                                <input type="text" class="form-control" name="city" placeholder="Daerah" value="{{ request('city') }}">
                            </div> -->

                            <div class="col-lg-2 col-md-6">
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
    </div>
    
    <!-- Search Results Header -->
    <div class="row mb-3">
        <div class="col-lg-8 col-md-6">
            <h4 class="fw-bold mb-1">
                @if(request()->hasAny(['city', 'district', 'min_price', 'max_price', 'gender', 'available_only']))
                    Hasil Pencarian
                    <!-- @if(request('name'))
                        untuk "{{ request('name') }}"
                    @endif -->
                @else
                    Semua Kos Tersedia
                @endif
            </h4>
            <p class="text-muted mb-0">
                Ditemukan {{ $kos->total() }} kos
                @if($kos->currentPage() > 1)
                    (halaman {{ $kos->currentPage() }} dari {{ $kos->lastPage() }})
                @endif
            </p>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="d-flex justify-content-end">
                <select class="form-select w-auto" onchange="sortResults(this.value)">
                    <option value="">Urutkan berdasarkan</option>
                    <option value="price_asc">Harga terendah</option>
                    <option value="price_desc">Harga tertinggi</option>
                    <option value="name_asc">Nama A-Z</option>
                    <option value="available_desc">Ketersediaan</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Active Filters -->
    @if(request()->hasAny(['city', 'min_price', 'max_price', 'gender', 'available_only']))
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <span class="fw-semibold me-2">Filter aktif:</span>
                    
                    @if(request('district'))
                        <span class="badge bg-primary">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ request('district') }}
                            <a href="{{ request()->fullUrlWithQuery(['city' => null]) }}" class="text-white ms-1">×</a>
                        </span>
                    @endif

                    @if(request('city'))
                        <span class="badge bg-primary">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ request('city') }}
                            <a href="{{ request()->fullUrlWithQuery(['city' => null]) }}" class="text-white ms-1">×</a>
                        </span>
                    @endif
                    
                    @if(request('min_price'))
                        <span class="badge bg-success">
                            Min: Rp {{ number_format(request('min_price'), 0, ',', '.') }}
                            <a href="{{ request()->fullUrlWithQuery(['min_price' => null]) }}" class="text-white ms-1">×</a>
                        </span>
                    @endif
                    
                    @if(request('max_price'))
                        <span class="badge bg-warning">
                            Max: Rp {{ number_format(request('max_price'), 0, ',', '.') }}
                            <a href="{{ request()->fullUrlWithQuery(['max_price' => null]) }}" class="text-white ms-1">×</a>
                        </span>
                    @endif
                    
                    @if(request('gender'))
                        <span class="badge bg-info">
                            {{ request('gender') == 'male' ? 'Pria' : (request('gender') == 'female' ? 'Wanita' : 'Campur') }}
                            <a href="{{ request()->fullUrlWithQuery(['gender' => null]) }}" class="text-white ms-1">×</a>
                        </span>
                    @endif
                    
                    @if(request('available_only'))
                        <span class="badge bg-secondary">
                            Hanya yang tersedia
                            <a href="{{ request()->fullUrlWithQuery(['available_only' => null]) }}" class="text-white ms-1">×</a>
                        </span>
                    @endif
                    
                    <a href="{{ route('search') }}" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-times me-1"></i>Hapus semua filter
                    </a>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Search Results -->
    <div class="row">
        @forelse($kos as $kosItem)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="position-relative">
                        <img src="{{ $kosItem->main_image }}" class="card-img-top" alt="{{ $kosItem->name }}" style="height: 200px; object-fit: cover;">                        
                        <!-- Availability Badge -->
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($kosItem->getAvailableRoomsCount() > 0)
                                <span class="badge bg-success">
                                    {{ $kosItem->getAvailableRoomsCount() }} kamar tersedia
                                </span>
                            @else
                                <span class="badge bg-danger">Penuh</span>
                            @endif
                        </div>
                        
                        <!-- Gender Badge -->
                        <div class="position-absolute top-0 start-0 m-2">
                            @if($kosItem->gender == 'male')
                                <span class="badge bg-info">
                                    <i class="fas fa-mars me-1"></i>Pria
                                </span>
                            @elseif($kosItem->gender == 'female')
                                <span class="badge bg-warning">
                                    <i class="fas fa-venus me-1"></i>Wanita
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-venus-mars me-1"></i>Campur
                                </span>
                            @endif
                        </div>
                        
                        <!-- Favorite Button -->
                        <div class="position-absolute bottom-0 end-0 m-2">
                            <button class="btn btn-light btn-sm rounded-circle" onclick="toggleFavorite({{ $kosItem->id }})">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">{{ $kosItem->name }}</h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $kosItem->city }}, {{ $kosItem->province }}
                        </p>
                        <p class="card-text text-muted small">{{ Str::limit($kosItem->description, 80) }}</p>
                        
                        <!-- Price -->
                        <div class="mb-3">
                            <span class="h5 fw-bold text-primary mb-0">
                                Rp {{ number_format($kosItem->price_per_month, 0, ',', '.') }}
                            </span>
                            <small class="text-muted">/bulan</small>
                        </div>
                        
                        <!-- Facilities Preview -->
                        @if($kosItem->facilities)
                            <div class="mb-3">
                                @foreach(array_slice($kosItem->facilities, 0, 3) as $facility)
                                    <span class="badge bg-light text-dark me-1 mb-1">
                                        <i class="fas fa-check me-1"></i>{{ $facility }}
                                    </span>
                                @endforeach
                                @if(count($kosItem->facilities) > 3)
                                    <span class="badge bg-light text-dark">
                                        +{{ count($kosItem->facilities) - 3 }} lainnya
                                    </span>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Room Info -->
                        <div class="mb-3 small text-muted">
                            <i class="fas fa-bed me-1"></i>
                            {{ $kosItem->getTotalRoomsCount() }} total kamar
                            <span class="mx-1">•</span>
                            <i class="fas fa-user me-1"></i>
                            {{ $kosItem->owner->name }}
                        </div>
                        
                        <!-- Actions -->
                        <div class="mt-auto">
                            <div class="d-grid gap-2">
                                <a href="{{ route('kos.show', $kosItem->id) }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>Lihat Detail
                                </a>
                                <!-- @if($kosItem->getAvailableRoomsCount() > 0)
                                    <a href="{{ route('kos.rooms', $kosItem->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-bed me-2"></i>Lihat Kamar
                                    </a>
                                @endif -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-4"></i>
                        <h4 class="text-muted">Tidak ada kos ditemukan</h4>
                        <p class="text-muted mb-4">
                            Coba ubah kriteria pencarian Anda atau 
                            <a href="{{ route('search') }}">lihat semua kos yang tersedia</a>
                        </p>
                        
                        @if(request()->hasAny(['city', 'min_price', 'max_price', 'gender', 'available_only']))
                            <a href="{{ route('search') }}" class="btn btn-primary">
                                <i class="fas fa-refresh me-2"></i>Lihat Semua Kos
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($kos->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $kos->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function sortResults(sortBy) {
    if (sortBy) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortBy);
        window.location.href = url.toString();
    }
}

function toggleFavorite(kosId) {
    // Implementation for favorite functionality
    alert('Fitur favorit akan segera tersedia!');
}

// Set current sort value
$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const currentSort = urlParams.get('sort');
    if (currentSort) {
        $('select[onchange="sortResults(this.value)"]').val(currentSort);
    }
});
</script>
@endsection