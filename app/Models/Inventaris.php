<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Sushi\Sushi;

class Inventaris extends Model
{
    use HasFactory, Sushi;

    protected $fillable = [
        'id_inventaris',
        'id_barang',
        'nama_barang',
        'merek',
        'qrcode_image',
        'id_ruangan',
        'nama_ruangan',
        'jumlah_barang',
        'kondisi_barang',
        'ket_barang',
        'created_at',
        'updated_at',
    ];

    /**
     * Model Rows
     *
     * @return array
     */
    public function getRows()
    {
        // Panggil API untuk mendapatkan data inventaris
        $response = Http::get('https://zaikotrack-main.test/api/inventaris');

        if ($response->successful()) {
            $inventaris = $response->json();
        } else {
            Log::error('Failed to retrieve data from API: ' . $response->status());
            return []; // Return empty array jika gagal
        }

        // Filter atribut yang diperlukan
        $inventaris = Arr::map($inventaris['data'], function ($item) {
            return [
                'id_inventaris'   => $item['id_inventaris'],
                'id_barang'       => $item['barang']['id_barang'] ?? null,
                'nama_barang'     => $item['barang']['nama_barang'] ?? 'Tidak Ada',
                'merek'           => $item['barang']['merek'] ?? 'Tidak Ada',
                'qrcode_image'           => $item['barang']['qrcode_image'] ?? 'Tidak Ada',
                'id_ruangan'      => $item['ruangan']['id_ruangan'] ?? null,
                'nama_ruangan'    => $item['ruangan']['nama_ruangan'] ?? 'Tidak Ada',
                'jumlah_barang'   => $item['jumlah_barang'] ?? 'N/A',
                'kondisi_barang'  => $item['kondisi_barang'] ?? 'N/A',
                'ket_barang'      => $item['ket_barang'] ?? '-',
                'created_at'      => $item['created_at'],
                'updated_at'      => $item['updated_at'],
            ];
        });
        

        return $inventaris;
    }
}
