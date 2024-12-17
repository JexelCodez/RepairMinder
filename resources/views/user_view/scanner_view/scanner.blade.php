@extends('user_view.resources.template')

@section('navbar')
    @include('user_view.resources.navbar')
@endsection

@section('title_page', 'QR Code Scanner')

@section('header')
    @include('user_view.resources.header')
@endsection

@section('content')
    <style>
        #reader__dashboard_section_csr{
            display: none !important;
        }
    </style>

    <div class="container flex justify-center px-4 py-8">
        <div class="w-full max-w-lg mx-auto p-4 bg-white rounded-lg shadow-lg">
            <div class="flex justify-center items-center col-12 md:col-6 lg:col-4 m-auto mb-4">
                <div id="reader" class="w-full h-64 border col-12 md:col-6 lg:col-4 m-auto border-gray-300 rounded-lg"></div>
            </div>

            <div class="text-center mt-4">
                <p class="text-lg font-semibold">Scanned Result:</p>
                <span id="result" class="block mt-2 text-xl text-blue-600 font-medium"></span>
                <p id="error" class="text-red-600 mt-2"></p>
            </div>

            <!-- Stop Button -->
            {{-- <button id="stop-scanner" class="stop-scanner-btn mt-4 bg-red-600 text-white px-6 py-2 rounded-lg">
                Stop Scanner
            </button> --}}
        </div>
    </div>
@endsection

@section('footer')
    @include('user_view.resources.footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        const resultElement = document.getElementById('result');
        const errorElement = document.getElementById('error');

        // Function to handle scan success
        function onScanSuccess(decodedText) {
            resultElement.innerText = decodedText;
            console.log(`Scanned result: ${decodedText}`);
        }

        // Function to handle scan failure
        function onScanFailure(error) {
            errorElement.innerText = `Scan error: ${error}`;
            console.warn(`Scan error: ${error}`);
        }

        // Initialize the QR code scanner
        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 }, false
        );

        // Start scanning
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

    </script>
@endsection
