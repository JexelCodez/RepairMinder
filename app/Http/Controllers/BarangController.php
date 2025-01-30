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
