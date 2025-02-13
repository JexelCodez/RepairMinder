<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class InventarisSarpras extends Model
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

    public function getRows()
    {
        return [
            [
                'id_inventaris' => 1,
                'id_barang' => 201,
                'nama_jenis_barang' => 'Proyektor',
                'nama_barang' => 'HP Proyektor-01',
                'merek' => 'HP',
                'kode_barang' => 'SARPRAS-001',
                'qrcode_image' => 'https://example.com/qrcode_dkv_001.png',
                'stok_barang' => 10,
                'id_ruangan' => 11,
                'nama_ruangan' => 'Ruang sarpras 1',
                'jumlah_barang' => 10,
                'kondisi_barang' => 'lengkap',
                'ket_barang' => 'Digunakan untuk presentasi',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'id_inventaris' => 2,
                'id_barang' => 202,
                'nama_jenis_barang' => 'Proyektor',
                'nama_barang' => 'HP Proyektor-02',
                'merek' => 'HP',
                'kode_barang' => 'SARPRAS-002',
                'qrcode_image' => 'https://example.com/qrcode_dkv_001.png',
                'stok_barang' => 10,
                'id_ruangan' => 11,
                'nama_ruangan' => 'Ruang sarpras 2',
                'jumlah_barang' => 10,
                'kondisi_barang' => 'lengkap',
                'ket_barang' => 'Digunakan untuk presentasi',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ],
        ];
    }

    public function periodePemeliharaan()
    {
        return $this->hasMany(PeriodePemeliharaan::class, 'id_barang', 'id_barang');
    }

    public function updateKondisiBarang($kondisi_barang)
    {
        $this->kondisi_barang = $kondisi_barang;
        
        return $this->save();
    }
}
