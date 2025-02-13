<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Inventaris;
use App\Models\InventarisDKV;
use App\Models\InventarisSarpras;
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
    
        $inventarisSija = (new Inventaris())->getRows();
        $inventarisDkv = (new InventarisDKV())->getRows();
        $inventarisSarpras = (new InventarisSarpras())->getRows();
    
        $allInventaris = collect($inventarisSija)
            ->merge($inventarisDkv)
            ->merge($inventarisSarpras);
    
        $barang = $allInventaris->firstWhere('kode_barang', $request->kode_barang);
    
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
