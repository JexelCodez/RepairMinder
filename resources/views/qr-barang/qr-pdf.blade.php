<!DOCTYPE html>
<html>
<head>
    <title>QR Code Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .label {
            width: 100%;
            padding: 10px;
        }
        .label-table {
            width: 100%;
            border-collapse: collapse;
        }
        .label-table td {
            vertical-align: middle;
            padding: 10px;
        }
        .qr {
            width: 150px; /* Atur lebar QR Code sesuai kebutuhan */
        }
        .info {
            /* Sesuaikan lebar kolom informasi jika diperlukan */
        }
        .info p {
            margin: 2px 0;
            font-size: 14px;
        }
        .info strong {
            display: inline-block;
            width: 120px; /* Atur lebar label */
        }
    </style>
</head>
<body>
    <div class="label">
        <table class="label-table">
            <tr>
                <!-- Kolom QR Code -->
                <td class="qr">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(75)->generate($barang->token_qr)) !!}" alt="QR Code Barang">
                </td>
                
                <!-- Kolom Informasi -->
                <td class="info">
                    <p> {{ $barang->nama }}</p>
                    <p> {{ $barang->kategori->nama_kategori }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>