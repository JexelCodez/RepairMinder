<!DOCTYPE html>
<html>
<head>
    <title>QR Codes Bulk Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px; /* Margin halaman */
        }
        .bulk-table {
            width: 100%;
            border-collapse: collapse;
        }
        .label-cell {
            width: 33.33%; /* 3 label per baris */
            padding: 2px;
            box-sizing: border-box;
            vertical-align: top;
        }
        .label {
            border: 0.8px solid #000; /* Border pada setiap label */
            padding: 2px; /* Margin antar border dan QR Code */
            margin-bottom: 10px;
        }
        .label-table {
            width: 100%;
            border-collapse: collapse;
        }
        .label-table td {
            vertical-align: middle; /* Vertikal tengah */
            padding: 0; /* Hilangkan padding */
            margin: 0; /* Hilangkan margin */
        }
        .qr {
            width: 100px; /* Lebar QR Code */
            padding-right: 0;
            margin-right: 0; /* Hilangkan margin */
        }
        .qr img {
            margin-right: 0.1px !important; /* Margin kanan QR Code */
            display: block; /* Menghilangkan spasi ekstra di bawah gambar */
        }
        .info {
           text-align: left;
           margin: 0; /* Hilangkan margin */
        }
        .info p {
            font-size: 12px; /* Ukuran font sedikit lebih kecil */
            margin: 2px 0; /* Mengurangi jarak antar paragraf */
            padding: 0; /* Hilangkan padding */
        }
    </style>
</head>
<body>
    <table class="bulk-table">
        @foreach($barangs->chunk(3) as $chunk)
            <tr>
                @foreach($chunk as $barang)
                    <td class="label-cell">
                        <div class="label">
                            <table class="label-table">
                                <tr>
                                    <!-- Kolom QR Code -->
                                    <td class="qr">
                                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(62.5)->generate($barang->token_qr)) !!}" alt="QR Code Barang">
                                    </td>
                                    
                                    <!-- Kolom Informasi -->
                                    <td class="info">
                                        <p>{{ $barang->nama }}</p>
                                        <p>{{ $barang->kategori->nama_kategori }}</p>
                                        <!-- Tambahkan informasi tambahan jika diperlukan -->
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                @endforeach
                <!-- Tambahkan cell kosong jika kurang dari 3 label -->
                @for($i = 0; $i < 3 - $chunk->count(); $i++)
                    <td class="label-cell"></td>
                @endfor
            </tr>
        @endforeach
    </table>
</body>
</html>