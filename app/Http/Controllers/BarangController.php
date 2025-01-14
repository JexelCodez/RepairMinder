<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF; 

class BarangController extends Controller
{
    /**
     * Mendownload QR Code untuk Barang tertentu sebagai PDF.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadQrCode($id)
    {
        $barang = Barang::findOrFail($id);

        // Mengenerate PDF dari view
        $pdf = PDF::loadView('qr-barang.qr-pdf', compact('barang'));

        // Mengatur nama file PDF
        $fileName = 'qr-barang-' . $barang->nama. '-' . now() . '.pdf';

        // Mengembalikan response download PDF
        return $pdf->download($fileName);
    }

    /**
     * Mendownload QR Codes untuk beberapa Barang sebagai satu file PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function downloadBulkQrCodes(Request $request)
    {
        // Mengambil parameter 'ids' dari query string dan mengubahnya menjadi array
        $ids = $request->input('ids', '');
        $idsArray = array_filter(explode(',', $ids), fn($id) => is_numeric($id));

        if (empty($idsArray)) {
            return redirect()->back()->with('error', 'Tidak ada Barang yang dipilih untuk didownload.');
        }

        // Mengambil semua Barang yang dipilih
        $barangs = Barang::whereIn('id', $idsArray)->get();

        if ($barangs->isEmpty()) {
            return redirect()->back()->with('error', 'Barang yang dipilih tidak ditemukan.');
        }

        // Mengenerate PDF dari view khusus untuk bulk
        $pdf = PDF::loadView('qr-barang.qr-pdf-bulk', compact('barangs'))
                  ->setPaper('A4', 'portrait'); // Atur ukuran dan orientasi kertas sesuai kebutuhan

        // Mengatur nama file PDF
        $fileName = 'qr-codes-bulk-' . now()->format('Y-m-d_H-i-s') . '.pdf';

        // Mengembalikan response download PDF
        return $pdf->download($fileName);
    }


    public function scan(Request $request)
{
    $request->validate([
        'kode_barang' => 'required|string',
    ]);

    $barangModel = new Barang();
    
    $products = $barangModel->getRows();

    $barang = collect($products)->firstWhere('kode_barang', $request->kode_barang);

    if ($barang) {
        return response()->json([
            'success' => true,
            'data' => $barang,
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Barang tidak ditemukan.',
        ]);
    }
}
    
}
