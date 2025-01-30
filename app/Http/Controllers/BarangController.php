<?php

namespace App\Http\Controllers;

use App\Models\Barang;
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
        // Validate the incoming request
        $request->validate([
            'kode_barang' => 'required|string|max:255',
        ]);

        $kodeBarang = $request->input('kode_barang');

        try {
            // Attempt to retrieve barang data from cache
            $barangData = Cache::remember('barang_data', now()->addMinutes(10), function () {
                $barangModel = new Barang();
                return $barangModel->getRows();
            });

            // Find the barang with the matching 'kode_barang'
            $barang = collect($barangData)->firstWhere('kode_barang', $kodeBarang);

            if ($barang) {
                return response()->json([
                    'success' => true,
                    'data' => $barang,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Barang tidak ditemukan.',
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Scan Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses permintaan.',
            ], 500);
        }
    }
    
}
