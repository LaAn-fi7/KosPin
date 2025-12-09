@extends('layouts.app')

@section('title', 'Kelola Kamar - {{ $kos->name }} - KosPin')

@section('content')
<div class="container my-4">
    <div class="row">
        <!-- Header -->
        <div class="col-12 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Kelola Kamar</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="h3 fw-bold">{{ $kos->name }}</h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-map-marker-alt me-1"></i>{{ $kos->address }}, {{ $kos->city }}
                    </p>
                    <p class="text-muted">
                        <span class="fw-bold text-primary">Rp {{ number_format($kos->price_per_month, 0, ',', '.') }}</span>/bulan
                        <span class="mx-2">•</span>
                        <i class="fas fa-bed me-1"></i>{{ $kos->rooms->count() }} total kamar
                    </p>
                </div>
                <div>
                    <button class="btn btn-outline-primary me-2" onclick="toggleAllRooms()">
                        <i class="fas fa-sync me-1"></i>Toggle All
                    </button>
                    <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-bed fa-2x mb-2"></i>
                    <h3 class="fw-bold mb-0">{{ $kos->rooms->count() }}</h3>
                    <p class="mb-0">Total Kamar</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-door-open fa-2x mb-2"></i>
                    <h3 class="fw-bold mb-0" id="availableCount">{{ $kos->getAvailableRoomsCount() }}</h3>
                    <p class="mb-0">Kamar Kosong</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <i class="fas fa-door-closed fa-2x mb-2"></i>
                    <h3 class="fw-bold mb-0" id="occupiedCount">{{ $kos->rooms->where('is_occupied', true)->count() }}</h3>
                    <p class="mb-0">Kamar Terisi</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Room Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-cog me-2"></i>Kelola Status Kamar
                        </h5>
                        <div>
                            <span class="badge bg-success me-2">
                                <i class="fas fa-square me-1"></i>Kosong (Klik untuk ubah ke Terisi)
                            </span>
                            <span class="badge bg-danger">
                                <i class="fas fa-square me-1"></i>Terisi (Klik untuk ubah ke Kosong)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="room-management-grid">
                        @foreach($kos->rooms->chunk(10) as $roomChunk)
                            <div class="row mb-4">
                                @foreach($roomChunk as $room)
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
                                        <div class="room-card {{ $room->is_occupied ? 'occupied' : 'available' }}" 
                                             data-room-id="{{ $room->id }}"
                                             onclick="toggleRoomStatus({{ $room->id }})">
                                            <div class="room-number">{{ $room->room_number }}</div>
                                            <div class="room-status">
                                                <i class="fas {{ $room->is_occupied ? 'fa-door-closed' : 'fa-door-open' }} me-1"></i>
                                                {{ $room->getStatusText() }}
                                            </div>
                                            <div class="room-price">
                                                Rp {{ number_format($room->price ?? $kos->price_per_month, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    
                    @if($kos->rooms->count() == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum ada kamar</h4>
                            <p class="text-muted">Tambahkan kamar untuk kos ini</p>
                            <button class="btn btn-primary" onclick="addRoom()">
                                <i class="fas fa-plus me-2"></i>Tambah Kamar
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-6 mb-3">
                            <button class="btn btn-outline-success w-100" onclick="setAllRoomsAvailable()">
                                <i class="fas fa-door-open me-1"></i>
                                Kosongkan Semua
                            </button>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <button class="btn btn-outline-danger w-100" onclick="setAllRoomsOccupied()">
                                <i class="fas fa-door-closed me-1"></i>
                                Isi Semua
                            </button>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <button class="btn btn-outline-primary w-100" onclick="showAddRoomModal()">
                                <i class="fas fa-plus me-1"></i>
                                Tambah Kamar
                            </button>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <button class="btn btn-outline-info w-100" onclick="refreshRooms()">
                                <i class="fas fa-sync me-1"></i>
                                Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .room-card {
        border: 3px solid #e5e7eb;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .room-card:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        transition: all 0.3s ease;
    }
    
    .room-card.available {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border-color: #10b981;
        color: #065f46;
    }
    
    .room-card.available:before {
        background-color: #10b981;
    }
    
    .room-card.occupied {
        background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
        border-color: #ef4444;
        color: #991b1b;
    }
    
    .room-card.occupied:before {
        background-color: #ef4444;
    }
    
    .room-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
    }
    
    .room-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .room-status {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .room-price {
        font-size: 0.75rem;
        opacity: 0.8;
        font-weight: 500;
    }
    
    .room-card.loading {
        opacity: 0.6;
        pointer-events: none;
    }
    
    @media (max-width: 576px) {
        .room-card {
            height: 100px;
            padding: 0.75rem;
        }
        
        .room-number {
            font-size: 1.25rem;
        }
        
        .room-status {
            font-size: 0.8rem;
        }
        
        .room-price {
            font-size: 0.7rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Setup CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

function toggleRoomStatus(roomId) {
    const roomCard = $(`.room-card[data-room-id="${roomId}"]`);
    roomCard.addClass('loading');
    
    $.ajax({
        url: `/owner/room/${roomId}/toggle-status`,
        type: 'PATCH',
        success: function(response) {
            if (response.success) {
                // Update room card appearance
                if (response.status) {
                    roomCard.removeClass('available').addClass('occupied');
                    roomCard.find('.room-status').html('<i class="fas fa-door-closed me-1"></i>Terisi');
                } else {
                    roomCard.removeClass('occupied').addClass('available');
                    roomCard.find('.room-status').html('<i class="fas fa-door-open me-1"></i>Kosong');
                }
                
                // Update counters
                updateCounters();
                
                // Show success message
                showToast('success', response.message);
            }
        },
        error: function(xhr) {
            showToast('error', 'Gagal mengupdate status kamar');
            console.error('Error:', xhr.responseJSON);
        },
        complete: function() {
            roomCard.removeClass('loading');
        }
    });
}

function updateCounters() {
    const availableCount = $('.room-card.available').length;
    const occupiedCount = $('.room-card.occupied').length;
    
    $('#availableCount').text(availableCount);
    $('#occupiedCount').text(occupiedCount);
}

function setAllRoomsAvailable() {
    if (!confirm('Apakah Anda yakin ingin mengosongkan semua kamar?')) return;
    
    $('.room-card.occupied').each(function() {
        const roomId = $(this).data('room-id');
        toggleRoomStatus(roomId);
    });
}

function setAllRoomsOccupied() {
    if (!confirm('Apakah Anda yakin ingin mengisi semua kamar?')) return;
    
    $('.room-card.available').each(function() {
        const roomId = $(this).data('room-id');
        toggleRoomStatus(roomId);
    });
}

function toggleAllRooms() {
    $('.room-card').each(function() {
        const roomId = $(this).data('room-id');
        toggleRoomStatus(roomId);
    });
}

function refreshRooms() {
    location.reload();
}

function showAddRoomModal() {
    // This would open a modal to add new rooms
    alert('Fitur tambah kamar akan segera tersedia!');
}

function showToast(type, message) {
    const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const toast = `
        <div class="toast align-items-center text-white ${bgClass} border-0 position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas ${icon} me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    $('body').append(toast);
    $('.toast').toast({delay: 3000}).toast('show');
    
    // Remove toast after it's hidden
    $('.toast').on('hidden.bs.toast', function() {
        $(this).remove();
    });
}
</script>
@endsection