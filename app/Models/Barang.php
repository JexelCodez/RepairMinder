<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Sushi\Sushi;
use Illuminate\Support\Facades\Log;

class Barang extends Model
{
    use HasFactory, Sushi;

    /**
     * Model Rows
     *
     * @return array
     */
    public function getRows()
    {
        //API
        $response = Http::get('https://zaikotrack.test/api/barang');

        if ($response->successful()) {
            $products = $response->json();
        } else {
            Log::error('Failed to retrieve data from API: ' . $response->status());
            return []; // Return empty array or default data
        }

        //filtering some attributes
        $products = Arr::map($products['data'], function ($item) {
            $filtered = Arr::only($item, [
                'id_barang',
                'nama_barang',
                'merek',
                'stok_barang',
                'kode_barang',
                'qrcode_image',
                'created_at',
                'updated_at'
            ]);
            $filtered['nama_jenis_barang'] = $item['jenis_barang']['nama_jenis_barang'] ?? null;
            $filtered['stok_barang'] = $item['stok_barang'] ?? 'N/A'; // Set to 'N/A' if null
            return $filtered;
        });

        return $products;
    }

}