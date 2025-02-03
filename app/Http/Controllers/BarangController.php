<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF; 

class BarangController extends Controller
{

    /**
     * Handle the scan request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scan(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_barang' => 'required|string|max:255',
        ]);

        // Ambil data inventaris dari API melalui model
        $inventarisModel = new Inventaris();
        $inventaris = $inventarisModel->getRows(); // Pastikan method getRows() mengambil data dari API

        // Cari barang berdasarkan kode_barang
        $barang = collect($inventaris)->firstWhere('kode_barang', $request->kode_barang);

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
