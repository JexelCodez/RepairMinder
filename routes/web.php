<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BarangController;

// Route untuk mendownload QR Code
Route::get('/barang/{id}/download-qr', [BarangController::class, 'downloadQrCode'])->name('barang.download-qr');
// Route baru untuk bulk download QR Codes sebagai satu PDF
Route::get('/barang/download-bulk-qrs', [BarangController::class, 'downloadBulkQrCodes'])->name('barang.download-bulk-qrs');

Route::get('/', function () {
    return view('user_view.resources.pages.scanner');
});
