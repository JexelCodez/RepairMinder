@extends('user_view.resources.layouts.app')
@section('title', 'Scanner Page')

@push('custom-css')
    

    {{-- <style>
        #reader__dashboard_section_csr{
            display: none !important;
        }
    </style> --}}
    <style>
        #reader {
            width: 100%;
            max-width: 400px;
            height: auto;
            margin: auto;
            position: relative;
            overflow: hidden;
        }
    
        #reader video {
            /* transform: scaleX(-1); */
            width: 100%;
            height: auto;
            object-fit: cover;
        }
    
        @media (max-width: 768px) {
            #reader {
                max-width: 300px;
            }
        }
    
        @media (max-width: 480px) {
            #reader {
                max-width: 250px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container flex justify-center px-4 py-8">
        @auth
        <div class="w-full max-w-lg mx-auto p-4 bg-white rounded-lg shadow-lg">
            <div class="flex justify-center items-center col-12 md:col-6 lg:col-4 m-auto mb-4">
                <div id="reader"></div>
                <!-- Start/Stop Button -->
                <div class="mt-3 text-center">
                    <button id="toggle-scan-btn" class="btn btn-primary">Start Scan</button>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-lg font-semibold">Scanned Result:</p>
                <span id="result" class="block mt-2 text-xl text-blue-600 font-medium"></span>
                <p id="error" class="text-red-600 mt-2"></p>
            </div>
        </div>
        @else
        <div class="w-full max-w-lg mx-auto p-4 bg-white rounded-lg shadow-lg">
            <div class="flex justify-center items-center col-12 md:col-6 lg:col-4 m-auto mb-4">
                <h5 class="text-center">Login Dulu Gak sih 👉 <a href="{{route('login')}}">Login</a></h5>
            </div>
        </div>
        @endauth

    </div>
@endsection
@push('custom-js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        let html5QrcodeScanner = new Html5Qrcode("reader");
        let isScanning = false;

        function calculateQrboxSize() {
            const readerWidth = document.getElementById('reader').offsetWidth;
            return Math.min(350, readerWidth * 0.8);
        }

        function onScanSuccess(decodedText) {
        // Tampilkan hasil di elemen "result"
        document.getElementById('result').innerText = "Processing QR Code...";

        // Kirim kode_barang ke server untuk mendapatkan data barang
        fetch('/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ kode_barang: decodedText }) // Ubah token_qr menjadi kode_barang
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tampilkan data barang
                const barang = data.data;
                document.getElementById('result').innerHTML = `
                    <strong>Nama Barang:</strong> ${barang.nama_barang}<br>
                    <strong>Merk:</strong> ${barang.merek}<br>
                    <strong>Stok:</strong> ${barang.stok_barang}<br>
                    <strong>Kode Barang:</strong> ${barang.kode_barang}<br>
                    <strong>Jenis Barang:</strong> ${barang.nama_jenis_barang}<br>
                    <strong>Terakhir Diperbarui:</strong> ${barang.updated_at}
                `;
            } else {
                // Jika data tidak ditemukan
                document.getElementById('result').innerText = data.message;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            document.getElementById('result').innerText = "An error occurred while processing the QR code.";
        });
    }



        function onScanFailure(error) {
            // Log error untuk debugging
            console.warn(`Scan error: ${error}`);
        }

        // BUAT START/STOP BUTTON
        
        document.getElementById('toggle-scan-btn').addEventListener('click', function() {
            if (isScanning) {
                html5QrcodeScanner.stop().then(() => {
                    document.getElementById('toggle-scan-btn').textContent = 'Start Scan';
                    isScanning = false;
                }).catch(err => {
                    console.error("Error stopping QR code scanner: ", err);
                });
            } else {
                html5QrcodeScanner.start(
                    { facingMode: "environment" },
                    {
                        fps: 60,
                        qrbox: calculateQrboxSize()
                    },
                    onScanSuccess,
                    onScanFailure
                ).then(() => {
                    document.getElementById('toggle-scan-btn').textContent = 'Stop Scan';
                    isScanning = true;
                }).catch(err => {
                    console.error("Error starting QR code scanner: ", err);
                });
            }
        });

        // html5QrcodeScanner.start(
        //     { facingMode: "environment" },
        //     {
        //         fps: 30,
        //         qrbox: calculateQrboxSize()
        //     },
        //     onScanSuccess,
        //     onScanFailure
        // ).catch(err => {
        //         console.error("Error starting QR code scanner: ", err);
        // });

    </script>
@endpush

