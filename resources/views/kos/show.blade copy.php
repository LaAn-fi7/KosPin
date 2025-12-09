@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">{{ $kos->name }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Alamat:</strong> {{ $kos->address }}</p>
            <p><strong>Harga:</strong> Rp{{ number_format($kos->price_per_month, 0, ',', '.') }}</p>
            <p><strong>Deskripsi:</strong></p>
            <p>{{ $kos->description }}</p>
        </div>
    </div>

    <h4>Kamar yang tersedia:</h4>
    <div class="row">
        @foreach ($kos->rooms as $room)
            <div class="col-md-3 mb-3">
                <div class="card {{ $room->is_occupied ? 'border-danger' : 'border-success' }}">
                    <div class="card-body">
                        <h5>Kamar {{ $room->room_number }}</h5>
                        <p>Status:
                            <span class="badge {{ $room->is_occupied ? 'bg-danger' : 'bg-success' }}">
                                {{ $room->is_occupied ? 'Terisi' : 'Tersedia' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <a href="{{ url('/') }}" class="btn btn-secondary mt-3">← Kembali</a>
</div>
@endsection