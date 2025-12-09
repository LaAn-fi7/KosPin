<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rapi dan terstruktur: public routes, auth, owner (protected).
|
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Menggunakan route-model binding (parameter {kos} akan di-resolve oleh Laravel)
// Pastikan KosController@show menerima Kos $kos atau menyesuaikan jika masih expect id
Route::get('/kos/{kos}', [KosController::class, 'show'])->name('kos.show');
Route::get('/kos/{kos}/rooms', [KosController::class, 'rooms'])->name('kos.rooms');

// Authentication routes (single registration)
Auth::routes();

// Optional: /home landing (kadang digunakan oleh Auth scaffolding)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Owner routes (harus login)
Route::middleware(['auth'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard & basic CRUD
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/create-kos', [OwnerController::class, 'createKos'])->name('create-kos');
    Route::post('/create-kos', [OwnerController::class, 'storeKos'])->name('store-kos');

    // Edit / update / delete kos
    Route::get('/kos/{kos}/edit', [OwnerController::class, 'editKos'])->name('kos.edit');           // jika kamu implementasikan edit view
    Route::put('/kos/{kos}', [OwnerController::class, 'updateKos'])->name('kos.update');           // update kos (tambahan gambar dsb)
    Route::delete('/kos/{kos}', [OwnerController::class, 'destroyKos'])->name('kos.destroy');      // hapus kos beserta gambarnya

    // Hapus satu gambar dari kos (dipanggil dari form thumbnail)
    Route::post('/kos/{kos}/remove-image', [OwnerController::class, 'removeImage'])->name('kos.removeImage');

    // Rooms management
    Route::get('/kos/{kos}/rooms', [OwnerController::class, 'manageRooms'])->name('manage-rooms');
    Route::patch('/room/{id}/toggle-status', [OwnerController::class, 'updateRoomStatus'])->name('update-room-status');
});

Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/', [AdminController::class,'index'])->name('index'); // admin dashboard
    Route::post('/approve-to-owner/{user}', [AdminController::class,'approveToOwner'])->name('approve.owner');
    Route::post('/revoke-owner/{user}', [AdminController::class,'revokeOwner'])->name('revoke.owner');
});

Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/', [AdminController::class,'index'])->name('index'); // admin dashboard + search
    Route::post('/approve-to-owner/{user}', [AdminController::class,'approveToOwner'])->name('approve.owner');

    // Hapus user (akun)
    Route::post('/delete-user/{user}', [AdminController::class,'destroyUser'])->name('delete.user');

    // Hapus kos
    Route::post('/delete-kos/{kos}', [AdminController::class,'destroyKos'])->name('delete.kos');

    // revoke owner
    Route::post('/revoke-owner/{user}', [AdminController::class,'revokeOwner'])->name('revoke.owner');
});