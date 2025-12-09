@extends('layouts.app')

@section('title', 'Tambah Kos Baru - KosPin')

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tambah Kos Baru</li>
                    </ol>
                </nav>
                
                <h1 class="h3 fw-bold">Tambah Kos Baru</h1>
                <p class="text-muted">Lengkapi informasi kos Anda untuk mulai menerima penyewa</p>
            </div>
            
            <!-- Form -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Informasi Kos
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.store-kos') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    Nama Kos <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Contoh: Kos Angrek Putih">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label fw-semibold">
                                    Tipe Gender <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">Pilih Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Pria</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Wanita</option>
                                    <option value="mixed" {{ old('gender') == 'mixed' ? 'selected' : '' }}>Campur</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label fw-semibold">
                                    Deskripsi <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Deskripsikan kos Anda dengan detail, termasuk fasilitas dan keunggulan">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Location Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Informasi Lokasi
                                </h6>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label fw-semibold">
                                    Alamat Lengkap <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2" 
                                          placeholder="Jl. Contoh No. 123, RT/RW 01/02">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label fw-semibold">
                                    Kota <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city') }}" 
                                       placeholder="Contoh: Yogyakarta">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="province" class="form-label fw-semibold">
                                    Provinsi <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('province') is-invalid @enderror" 
                                       id="province" name="province" value="{{ old('province') }}" 
                                       placeholder="Contoh: DI Yogyakarta">
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Pricing & Room Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-money-bill-wave me-2"></i>Harga & Kamar
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="price_per_month" class="form-label fw-semibold">
                                    Harga per Bulan (Rp) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('price_per_month') is-invalid @enderror" 
                                           id="price_per_month" name="price_per_month" value="{{ old('price_per_month') }}" 
                                           placeholder="1000000" min="0">
                                    @error('price_per_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Harga sewa per kamar per bulan</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="total_rooms" class="form-label fw-semibold">
                                    Jumlah Kamar <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('total_rooms') is-invalid @enderror" 
                                       id="total_rooms" name="total_rooms" value="{{ old('total_rooms', 10) }}" 
                                       placeholder="10" min="1" max="50">
                                @error('total_rooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 50 kamar per kos</small>
                            </div>
                        </div>
                        
                        <!-- Facilities -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-list-check me-2"></i>Fasilitas
                                </h6>
                                
                                <label for="facilities" class="form-label fw-semibold">
                                    Fasilitas Kos
                                </label>
                                <textarea class="form-control" id="facilities" name="facilities" rows="3" 
                                          placeholder="WiFi, AC, Kamar Mandi Dalam, Dapur Bersama, Parkir Motor, dll. (Pisahkan dengan koma)">{{ old('facilities') }}</textarea>
                                <small class="text-muted">Tuliskan fasilitas yang tersedia, pisahkan dengan koma</small>
                            </div>
                        </div>
                        
                        <!-- Preview Section -->
                        <div class="row mb-4" id="previewSection" style="display: none;">
                            <div class="col-12">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="fas fa-eye me-2"></i>Preview Kos
                                </h6>
                                <div class="card border-success">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h5 class="fw-bold mb-1" id="previewName">-</h5>
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    <span id="previewLocation">-</span>
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <span class="h5 fw-bold text-primary mb-0" id="previewPrice">Rp 0</span>
                                                <small class="text-muted d-block">/bulan</small>
                                                <span class="badge bg-secondary" id="previewGender">-</span>
                                            </div>
                                        </div>
                                        <p class="card-text" id="previewDescription">-</p>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-bed text-success me-2"></i>
                                            <span id="previewRooms">0</span> kamar tersedia
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-3">
                                    <button type="button" class="btn btn-outline-primary" onclick="showPreview()">
                                        <i class="fas fa-eye me-2"></i>Preview
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Simpan Kos
                                    </button>
                                    <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

function showPreview() {
    const name = $('#name').val() || 'Nama Kos';
    const city = $('#city').val() || 'Kota';
    const province = $('#province').val() || 'Provinsi';
    const price = $('#price_per_month').val() || 0;
    const description = $('#description').val() || 'Deskripsi kos';
    const gender = $('#gender').val() || '';
    const totalRooms = $('#total_rooms').val() || 0;
    
    $('#previewName').text(name);
    $('#previewLocation').text(city + ', ' + province);
    $('#previewPrice').text('Rp ' + formatNumber(price));
    $('#previewDescription').text(description);
    $('#previewRooms').text(totalRooms);
    
    // Gender badge
    let genderText = '';
    let genderClass = 'bg-secondary';
    
    switch(gender) {
        case 'male':
            genderText = 'Pria';
            genderClass = 'bg-info';
            break;
        case 'female':
            genderText = 'Wanita';
            genderClass = 'bg-warning';
            break;
        case 'mixed':
            genderText = 'Campur';
            genderClass = 'bg-secondary';
            break;
        default:
            genderText = '-';
    }
    
    $('#previewGender').text(genderText).removeClass().addClass('badge ' + genderClass);
    
    $('#previewSection').show();
    
    // Scroll to preview
    $('html, body').animate({
        scrollTop: $('#previewSection').offset().top - 100
    }, 500);
}

// Real-time preview updates
$('#name, #city, #province, #price_per_month, #description, #gender, #total_rooms').on('input change', function() {
    if ($('#previewSection').is(':visible')) {
        showPreview();
    }
});

// Format price input
$('#price_per_month').on('input', function() {
    const value = $(this).val().replace(/[^0-9]/g, '');
    $(this).val(value);
});
</script>
@endsection