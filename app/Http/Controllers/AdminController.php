<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        // Cari users dan kos berdasarkan query q (nama, email, title)
        $users = User::query()
            ->when($q, fn($query,$val) => $query->where(function($q2) use($val){
                $q2->where('name','like',"%{$val}%")
                   ->orWhere('email','like',"%{$val}%");
            }))
            ->orderBy('created_at','desc')
            ->paginate(20, ['*'], 'users_page');

        $koses = Kos::query()
            ->when($q, fn($query,$val) => $query->where(function($q2) use($val){
                $q2->where('name','like',"%{$val}%")
                   ->orWhere('city','like',"%{$val}%")
                   ->orWhere('address','like',"%{$val}%");
            }))
            ->with('owner')
            ->orderBy('created_at','desc')
            ->paginate(20, ['*'], 'koses_page');

        return view('admin.index', compact('users','koses','q'));
    }

    // ubah role user => owner (sudah ada)
    public function approveToOwner(Request $request, User $user)
    {
        // sebelumnya sudah ada validasi
        $user->role = 'owner';
        $user->save();
        return back()->with('success','User telah di-ACC menjadi owner.');
    }

    // Hapus akun user (hard delete)
    public function destroyUser(Request $request, User $user)
    {
        // mencegah admin hapus diri sendiri
        if (auth()->id() === $user->id) {
            return back()->with('error','Tidak bisa menghapus akun sendiri.');
        }

        // opsi: hapus semua kos milik user juga, atau biarkan admin hapus kos terpisah
        // Pilihan: hapus kos secara manual oleh admin; di sini kita juga hapus kos milik user
        DB::transaction(function() use($user) {
            // kalau menggunakan soft deletes di Kos, gunakan $user->kos()->delete();
            $user->kos()->each(fn($k)=> $k->delete()); // memanggil delete pada setiap kos
            $user->delete(); // hapus user
        });

        return back()->with('success','Akun user berhasil dihapus.');
    }

    // Hapus kos
    public function destroyKos(Request $request, Kos $kos)
    {
        // optional: cek jika kos terkait ada
        $kos->delete();
        return back()->with('success','Kos berhasil dihapus.');
    }

    // revoke owner sudah ada
    public function revokeOwner(Request $request, User $user)
    {
        if ($user->role !== 'owner') {
            return back()->with('info','User bukan owner.');
        }
        $user->role = 'user';
        $user->save();
        return back()->with('success','Role owner dicabut.');
    }
}
