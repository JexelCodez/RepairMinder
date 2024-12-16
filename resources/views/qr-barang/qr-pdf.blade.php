<!DOCTYPE html>
<html>
<head>
    <title>QR Code Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .label {
            width: 33.33%;
            padding: 2px;
            border: 0.8px solid #000;
            margin-bottom: 10px;
            box-sizing: border-box;
            vertical-align: top;
        }
        .label-table {
            width: 100%;
            border-collapse: collapse;
        }
        .label-table td {
            vertical-align: middle;
            padding: 0;
            margin: 0;
        }
        .qr {
            width: 100px;
            padding-right: 0;
            margin-right: 0;
        }
        .qr img {
            display: block;
            margin-right: 0.1px !important;
        }
        .info {
            text-align: left;
            margin: 0;
        }
        .info p {
            font-size: 12px;
            margin: 2px 0;
            padding: 0;
        }
        .info strong {
            display: inline-block;
            width: 120px;
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
