@extends('layouts.app')

@section('title', $kos->name ?? 'Detail Kos')

@section('content')
<div class="container my-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Left: Gallery -->
        <div class="col-lg-7 mb-4">
            <div class="card">
                @php
                    // Gunakan accessor image_urls jika sudah tersedia di model; 
                    // fallback ke images (array path relatif) dan ubah ke Storage::url
                    $images = method_exists($kos, 'getImageUrlsAttribute') ? $kos->image_urls : ($kos->images ?? []);
                @endphp

                @if(!empty($images) && count($images) > 0)
                    <div id="kosCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" style="max-height:480px; overflow:hidden;">
                            @foreach($images as $index => $imgUrl)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $imgUrl }}" class="d-block w-100" alt="Gambar {{ $index + 1 }}" style="object-fit:cover; height:480px;">
                                </div>
                            @endforeach
                        </div>
                        @if(count($images) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#kosCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#kosCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        @endif

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h2 class="h5 mb-1">{{ $kos->name }}</h2>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $kos->address }} 
                                        @if($kos->city), {{ $kos->city }}@endif
                                        @if($kos->province), {{ $kos->province }}@endif
                                    </p>
                                </div>
                                <div class="text-end">
                                    <div class="h5 fw-bold mb-0">Rp{{ number_format($kos->price_per_month ?? 0, 0, ',', '.') }}</div>
                                    <small class="text-muted">/ bulan</small>
                                    <div class="mt-2">
                                        <span class="badge 
                                            {{ $kos->gender === 'male' ? 'bg-info' : ($kos->gender === 'female' ? 'bg-warning' : 'bg-secondary') }}">
                                            {{ $kos->gender === 'male' ? 'Pria' : ($kos->gender === 'female' ? 'Wanita' : 'Campur') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card-body text-center py-5">
                        <div class="mb-3" style="font-size:14rem; color:#f1f1f1;">
                            <i class="fas fa-building"></i>
                        </div>
                        <h5 class="mb-1">Belum ada gambar</h5>
                        <p class="text-muted">Pemilik belum mengunggah foto untuk kos ini.</p>
                    </div>
                @endif
            </div>

            <!-- Facilities -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="mb-3">Fasilitas</h5>
                    @if(!empty($kos->facilities))
                        @php
                            // facilities diasumsikan dipisah koma
                            $facilities = is_array($kos->facilities) ? $kos->facilities : array_filter(array_map('trim', explode(',', $kos->facilities)));
                        @endphp
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($facilities as $f)
                                <span class="badge bg-light text-dark border">{{ $f }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada informasi fasilitas.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Details & Rooms -->
        <div class="col-lg-5">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Informasi Kos</h5>
                    <p class="mb-1"><strong>Alamat:</strong></p>
                    <p class="text-muted mb-3">{{ $kos->address ?? '-' }}</p>

                    <p class="mb-1"><strong>Kecamatan:</strong></p>
                    <p class="text-muted mb-3">{{ $kos->district ?? '-' }}</p>

                    <p class="mb-1"><strong>Deskripsi:</strong></p>
                    <p class="text-muted mb-3" style="white-space:pre-line;">{{ $kos->description ?? '-' }}</p>

                    <p class="mb-1"><strong>Jumlah Kamar:</strong></p>
                    <p class="text-muted mb-3">{{ $kos->total_rooms ?? ($kos->rooms->count() ?? 0) }} kamar</p>

                    <p class="mb-1"><strong>Availability:</strong></p>
                    @php
                        $availableCount = $kos->rooms->where('is_occupied', false)->count() ?? 0;
                    @endphp
                    <p class="text-muted mb-3">
                        <span class="badge {{ $availableCount > 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $availableCount > 0 ? $availableCount . ' kamar tersedia' : 'Kosong / Semua terisi' }}
                        </span>
                    </p>

                    <!-- Optional: Contact / Action -->
                    @if(isset($kos->owner) && isset($kos->owner->phone))
                        <p class="mb-1"><strong>Kontak Pemilik:</strong></p>
                        <p class="text-muted mb-3">{{ $kos->owner->phone }}</p>
                    @endif

                    <a href="{{ url('/') }}" class="btn btn-outline-secondary w-100">← Kembali</a>
                </div>
            </div>

            <!-- Rooms Grid -->
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Daftar Kamar</h5>
                    @if($kos->rooms && $kos->rooms->count() > 0)
                        <div class="row">
                            @foreach ($kos->rooms as $room)
                                <div class="col-12 mb-2">
                                    <div class="d-flex justify-content-between align-items-center border rounded p-2">
                                        <div>
                                            <strong>Kamar {{ $room->room_number }}</strong>
                                            <div class="text-muted small">
                                                @if($room->price_override)
                                                    Harga: Rp{{ number_format($room->price_override, 0, ',', '.') }}
                                                @else
                                                    Harga: Rp{{ number_format($kos->price_per_month ?? 0, 0, ',', '.') }}
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <span class="badge {{ $room->is_occupied ? 'bg-danger' : 'bg-success' }}">
                                                {{ $room->is_occupied ? 'Terisi' : 'Tersedia' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada data kamar untuk kos ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
