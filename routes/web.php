<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cari', [SearchController::class, 'cari'])->name('cari');
// Alihkan URL lama /cari/{asal}/{tujuan} ke format query yang benar.
Route::get('/cari/{origin}/{dest}/{tanggal?}', function (string $origin, string $dest, ?string $tanggal = null) {
    return redirect()->to('/cari?' . http_build_query([
        'origin' => $origin,
        'dest' => $dest,
        'tanggal' => $tanggal ?? date('Y-m-d'),
    ]));
})->where(['origin' => '[0-9]+', 'dest' => '[0-9]+', 'tanggal' => '[0-9-]+']);
Route::get('/jadwal/{schedule}', [ScheduleController::class, 'show'])->name('jadwal.show');

Route::post('/pesan', [BookingController::class, 'pesanRute'])->name('pesan.rute');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/sukses/{kode}', [BookingController::class, 'sukses'])->name('booking.sukses');
Route::get('/tiket/{kode}', [BookingController::class, 'tiket'])->name('tiket');
Route::get('/riwayat', [BookingController::class, 'riwayat'])->name('riwayat');

Route::post('/tiket/{kode}/bayar', [PaymentController::class, 'upload'])->name('payment.upload');

// Login khusus admin — URL "rahasia", tidak ditautkan di halaman publik.
Route::middleware('guest')->group(function () {
    Route::get('/admin-login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/admin-login', [LoginController::class, 'login']);
    // Alias: /login diarahkan ke /admin-login biar tidak 404 saat salah ketik.
    Route::get('/login', fn () => redirect('/admin-login'));
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

require __DIR__ . '/admin.php';
