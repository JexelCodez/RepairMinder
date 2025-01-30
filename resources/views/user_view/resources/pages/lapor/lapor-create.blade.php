@extends('user_view.resources.layouts.app')
@section('title', 'Buat Laporan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Buat Laporan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('lapor.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Nama Barang -->
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" id="nama_barang" name="nama_barang" 
                                   value="{{ old('nama_barang', $nama_barang ?? '') }}" 
                                   class="form-control" readonly>
                        </div>

                        <!-- Merk Barang -->
                        <div class="mb-3">
                            <label for="merk_barang" class="form-label">Merk Barang</label>
                            <input type="text" id="merk_barang" name="merk_barang" 
                                   value="{{ old('merk_barang', $merk_barang ?? '') }}" 
                                   class="form-control" readonly>
                        </div>

                        <!-- Kode Barang -->
                        <div class="mb-3">
                            <label for="kode_barang" class="form-label">Kode Barang</label>
                            <input type="text" id="kode_barang" name="kode_barang" 
                                   value="{{ old('kode_barang', $kode_barang ?? '') }}" 
                                   class="form-control" readonly>
                        </div>

                        <!-- Deskripsi Laporan -->
                        <div class="mb-3">
                            <label for="deskripsi_laporan" class="form-label">Deskripsi Laporan</label>
                            <textarea id="deskripsi_laporan" name="deskripsi_laporan" 
                                      class="form-control" rows="4" 
                                      placeholder="Jelaskan masalah yang ingin dilaporkan..." required>{{ old('deskripsi_laporan') }}</textarea>
                        </div>

                        <!-- Lokasi Laporan -->
                        <div class="mb-3">
                            <label for="lokasi_barang" class="form-label">Lokasi Laporan</label>
                            <input type="text" id="lokasi_barang" name="lokasi_barang" 
                                   value="{{ old('lokasi_barang') }}" 
                                   class="form-control" placeholder="Masukkan lokasi barang..." required>
                        </div>

                        <!-- Bukti Laporan -->
                        <div class="mb-3">
                            <label for="bukti_laporan" class="form-label">Bukti Laporan (Opsional)</label>
                            <input type="file" id="bukti_laporan" name="bukti_laporan" 
                                   class="form-control" accept="image/*">
                            <small class="text-muted">Unggah file gambar dengan format JPG, JPEG, atau PNG (maks. 2 MB).</small>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
