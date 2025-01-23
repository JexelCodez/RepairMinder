<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporController;

// Route untuk autentikasi siswa
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Route untuk mendownload QR Code
Route::get('/barang/{id}/download-qr', [BarangController::class, 'downloadQrCode'])->name('barang.download-qr');
// Route baru untuk bulk download QR Codes sebagai satu PDF
Route::get('/barang/download-bulk-qrs', [BarangController::class, 'downloadBulkQrCodes'])->name('barang.download-bulk-qrs');

Route::post('/scan', [BarangController::class, 'scan'])->name('scan');

Route::get('/lapor', [LaporController::class, 'create'])->name('lapor.create');
Route::post('/lapor', [LaporController::class, 'store'])->name('lapor.store');

Route::get('/', function () {
    return view('user_view.resources.pages.scanner');
})->name('home');

Route::get('/tentang', function () {
    return view('user_view.resources.pages.tentang');
})->name('tentang');

Route::get('/kontak', function () {
    return view('user_view.resources.pages.kontak');
})->name('kontak');

Route::get('/home/guru', function () {
    return view('guru_view.home');
})->name('home.guru')->middleware(['auth', 'guru']);

Route::middleware(['auth'])->group(function () {
    // Protected routes here
});
