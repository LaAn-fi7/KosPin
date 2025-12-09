@extends('layouts.app')

@section('title', 'Owner Dashboard - KosPin')

@section('content')
<div class="container my-4">
    <div class="row">
        <!-- Header -->
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold">Dashboard Owner</h1>
                    <p class="text-muted mb-0">Kelola kos Anda dengan mudah</p>
                </div>
                <a href="{{ route('owner.create-kos') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Kos Baru
                </a>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h2 class="fw-bold mb-0">{{ $stats['total_kos'] }}</h2>
                            <p class="mb-0">Total Kos</p>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-home fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h2 class="fw-bold mb-0">{{ $stats['total_rooms'] }}</h2>
                            <p class="mb-0">Total Kamar</p>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-bed fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h2 class="fw-bold mb-0">{{ $stats['available_rooms'] }}</h2>
                            <p class="mb-0">Kamar Kosong</p>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-door-open fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h2 class="fw-bold mb-0">{{ $stats['occupied_rooms'] }}</h2>
                            <p class="mb-0">Kamar Terisi</p>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-door-closed fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Kos Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-list me-2"></i>Daftar Kos Anda
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($kos->count() > 0)
                        <div class="row">
                            @foreach($kos as $kosItem)
                                <div class="col-lg-6 mb-4">
                                    <div class="card border">
                                        <div class="card-header bg-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 fw-bold">{{ $kosItem->name }}</h6>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-cog"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Edit Kos</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('owner.manage-rooms', $kosItem->id) }}"><i class="fas fa-bed me-2"></i>Kelola Kamar</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>Hapus</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-8">
                                                    <p class="text-muted mb-1">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $kosItem->district }}, {{ $kosItem->city }}, {{ $kosItem->province }}
                                                    </p>
                                                    <p class="mb-1">
                                                        <span class="fw-bold text-primary">Rp {{ number_format($kosItem->price_per_month, 0, ',', '.') }}</span>
                                                        <small class="text-muted">/bulan</small>
                                                    </p>
                                                </div>
                                                <div class="col-4 text-end">
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
                                            </div>
                                            
                                            <!-- Room Status Overview -->
                                            <div class="mb-3">
                                                <h6 class="fw-bold mb-2">Status Kamar:</h6>
                                                <div class="room-grid">
                                                    @foreach($kosItem->rooms as $room)
                                                        <div class="room-box {{ $room->is_occupied ? 'occupied' : 'available' }}" 
                                                             title="Kamar {{ $room->room_number }} - {{ $room->getStatusText() }}">
                                                            {{ $room->room_number }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="small text-muted">
                                                    <i class="fas fa-door-open text-success me-1"></i>{{ $kosItem->getAvailableRoomsCount() }} kosong
                                                    <span class="mx-1">•</span>
                                                    <i class="fas fa-door-closed text-danger me-1"></i>{{ $kosItem->rooms->where('is_occupied', true)->count() }} terisi
                                                </div>
                                                <a href="{{ route('owner.manage-rooms', $kosItem->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-cog me-1"></i>Kelola
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-home fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum ada kos terdaftar</h4>
                            <p class="text-muted mb-4">Mulai daftarkan kos pertama Anda sekarang!</p>
                            <a href="{{ route('owner.create-kos') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Tambah Kos Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .room-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(35px, 1fr));
        gap: 5px;
        max-width: 300px;
    }
    
    .room-box {
        aspect-ratio: 1;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .room-box.available {
        background-color: #dcfce7;
        border-color: #10b981;
        color: #10b981;
    }
    
    .room-box.occupied {
        background-color: #fee2e2;
        border-color: #ef4444;
        color: #ef4444;
    }
    
    .room-box:hover {
        transform: scale(1.1);
    }
    
    @media (max-width: 576px) {
        .room-grid {
            grid-template-columns: repeat(auto-fill, minmax(30px, 1fr));
        }
        
        .room-box {
            font-size: 0.7rem;
        }
    }
</style>
@endsection