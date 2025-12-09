@extends('layouts.app')

@section('content')
<div class="container my-6">
  <h1 class="mb-4">Admin — Kelola Users & Kos</h1>

  <!-- Search form -->
  <form method="GET" action="{{ route('admin.index') }}" class="mb-4">
    <div class="input-group">
      <input type="text" name="q" value="{{ old('q', $q ?? '') }}" class="form-control" placeholder="Cari user (nama/email) atau kos (judul/kota/alamat)..." />
      <button class="btn btn-primary" type="submit"><i class="fas fa-search me-1"></i>Cari</button>
    </div>
  </form>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

  <div class="row">
    <div class="col-lg-6 mb-4">
      <div class="card p-3">
        <h5>Daftar Users</h5>
        <table class="table table-striped">
          <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
          <tbody>
            @foreach($users as $user)
              <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                  @if($user->role !== 'owner')
                    <form action="{{ route('admin.approve.owner', $user->id) }}" method="POST" style="display:inline">
                      @csrf
                      <button class="btn btn-sm btn-success" onclick="return confirm('Set user ini menjadi owner?')">Set Owner</button>
                    </form>
                  @else
                    <form action="{{ route('admin.revoke.owner', $user->id) }}" method="POST" style="display:inline">
                      @csrf
                      <button class="btn btn-sm btn-warning" onclick="return confirm('Cabut role owner?')">Revoke</button>
                    </form>
                  @endif

                  <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin ingin menghapus akun ini? Semua kos milik user akan ikut dihapus.')">Hapus</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        {{ $users->withQueryString()->links() }}
      </div>
    </div>

    <div class="col-lg-6 mb-4">
      <div class="card p-3">
        <h5>Daftar Kos</h5>
        <table class="table table-striped">
          <thead><tr><th>Judul</th><th>Pemilik</th><th>Kota</th><th>Aksi</th></tr></thead>
          <tbody>
            @foreach($koses as $kos)
              <tr>
                <td>{{ $kos->name }}</td>
                <td>{{ $kos->owner->name ?? '-' }}</td>
                <td>{{ $kos->city ?? '-' }}</td>
                <td>
                  <a href="{{ route('kos.show', $kos->id) }}" class="btn btn-sm btn-info">Lihat</a>

                  <form action="{{ route('admin.delete.kos', $kos->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus kos ini?')">Hapus</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        {{ $koses->withQueryString()->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
