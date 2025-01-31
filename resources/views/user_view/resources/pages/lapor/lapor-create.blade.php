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

                        <div class="mb-3">
                            <label for="bukti_laporan" class="form-label">Bukti Laporan (Opsional)</label>
                            
                            <!-- Pilihan untuk menggunakan kamera atau mengunggah file -->
                            <div class="d-flex justify-content-between mb-3">
                                <button type="button" class="btn btn-secondary" id="uploadOption">Upload File</button>
                                <button type="button" class="btn btn-primary" id="captureOption">Capture Kamera</button>
                            </div>
                        
                            <!-- Input file untuk upload file -->
                            <input type="file" id="fileInput" name="bukti_laporan" class="form-control" accept="image/*" style="display:none;">
                        
                            <!-- Elemen video untuk preview kamera -->
                            <video id="cameraPreview" autoplay width="100%" height="auto" style="border: 1px solid #ddd; display:none;"></video>
                        
                            <!-- Canvas untuk capture gambar -->
                            <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>
                        
                            <!-- Tombol untuk capture gambar -->
                            <button type="button" class="btn btn-success mt-2" id="captureBtn" style="display:none;">Capture</button>
                        
                            <!-- Preview hasil capture -->
                            <img id="capturedImage" src="" alt="Hasil Capture" class="img-fluid mt-3" style="display:none; max-width: 100%; border: 1px solid #ddd;">
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

<script>
    const uploadOption = document.getElementById('uploadOption');
const captureOption = document.getElementById('captureOption');
const fileInput = document.getElementById('fileInput');
const video = document.getElementById('cameraPreview');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('captureBtn');
const capturedImage = document.getElementById('capturedImage');

let videoStream = null; // Variabel untuk menyimpan stream video

// Fungsi untuk membuka kamera
async function openCamera() {
    try {
        videoStream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = videoStream;
        video.style.display = 'block';
        captureButton.style.display = 'block';
    } catch (error) {
        console.error('Error membuka kamera:', error);
        alert('Gagal membuka kamera. Pastikan perangkat memiliki kamera dan izinkan akses.');
    }
}

// Fungsi untuk menangkap gambar
function captureImage() {
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    // Tangkap gambar dari video ke canvas
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Konversi gambar menjadi URL
    const dataUrl = canvas.toDataURL('image/png');
    capturedImage.src = dataUrl;
    capturedImage.style.display = 'block';

    // Konversi gambar menjadi file
    const file = dataUrlToFile(dataUrl, 'captured_image.png');
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    fileInput.files = dataTransfer.files;

    // Matikan kamera setelah capture
    stopCamera();

    alert('Gambar berhasil di-capture!');
}

// Fungsi untuk menghentikan kamera
function stopCamera() {
    if (videoStream) {
        const tracks = videoStream.getTracks(); // Ambil semua track dalam stream
        tracks.forEach((track) => track.stop()); // Hentikan setiap track
        videoStream = null;
    }
    video.style.display = 'none';
    captureButton.style.display = 'none';
}


// Fungsi untuk mengonversi Data URL ke File
function dataUrlToFile(dataUrl, filename) {
    const byteString = atob(dataUrl.split(',')[1]);
    const mimeString = dataUrl.split(',')[0].split(':')[1].split(';')[0];
    const arrayBuffer = new ArrayBuffer(byteString.length);
    const uint8Array = new Uint8Array(arrayBuffer);

    for (let i = 0; i < byteString.length; i++) {
        uint8Array[i] = byteString.charCodeAt(i);
    }

    return new File([uint8Array], filename, { type: mimeString });
}

// Event untuk tombol "Upload File"
uploadOption.addEventListener('click', () => {
    fileInput.style.display = 'block';
    video.style.display = 'none';
    captureButton.style.display = 'none';
    capturedImage.style.display = 'none';
});

// Event untuk tombol "Capture Kamera"
captureOption.addEventListener('click', () => {
    fileInput.style.display = 'none';
    capturedImage.style.display = 'none';
    openCamera();
});

// Event untuk tombol "Capture"
captureButton.addEventListener('click', captureImage);

</script>
@endsection
