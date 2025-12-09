<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/kos/{id}', [KosController::class, 'show'])->name('kos.show');
Route::get('/kos/{id}/rooms', [KosController::class, 'rooms'])->name('kos.rooms');


// Authentication Routes
Auth::routes();

// Protected routes for users
//Route::middleware(['auth'])->group(function () {
    //Route::get('/kos/{id}/book', [KosController::class, 'book'])->name('kos.book');
    //Route::post('/kos/{id}/book', [KosController::class, 'processBooking'])->name('kos.process-booking');
    //Route::post('/kos/{id}/book', [KosController::class, 'book'])->name('kos.book');
    //Route::post('/kos/{id}/quick-book', [KosController::class, 'quickBook'])->name('kos.quickBook');
    //Route::post('/owner/booking/{id}/confirm', [OwnerController::class, 'confirmBooking'])->name('owner.booking.confirm');
    //Route::get('/owner/bookings', [OwnerController::class, 'listBookings'])->name('owner.bookings');
    //Route::post('/owner/booking/{id}/confirm', [OwnerController::class, 'confirmBooking'])->name('owner.booking.confirm');
//});

// Tambahkan routes untuk user bookings
//Route::middleware(['auth'])->group(function () {
    //Route::get('/my-bookings', [UserController::class, 'myBookings'])->name('user.bookings');
    //Route::patch('/booking/{id}/cancel', [UserController::class, 'cancelBooking'])->name('user.booking.cancel');
//});

// Owner routes
Route::middleware(['auth'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/create-kos', [OwnerController::class, 'createKos'])->name('create-kos');
    Route::post('/create-kos', [OwnerController::class, 'storeKos'])->name('store-kos');
    Route::get('/kos/{id}/rooms', [OwnerController::class, 'manageRooms'])->name('manage-rooms');
    Route::patch('/room/{id}/toggle-status', [OwnerController::class, 'updateRoomStatus'])->name('update-room-status');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
