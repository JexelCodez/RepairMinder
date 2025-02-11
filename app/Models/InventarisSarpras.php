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
        'kode_barang',
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
                'kode_barang' => 'SAR-001',
                'nama_barang' => 'Proyektor',
                'merek' => 'Epson EB-X06',
                'qrcode_image' => 'qrcode_sarpras_001.png',
                'id_ruangan' => 20,
                'nama_ruangan' => 'Ruang Meeting 1',
                'jumlah_barang' => 2,
                'kondisi_barang' => 'lengkap',
                'ket_barang' => 'Digunakan untuk presentasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_inventaris' => 2,
                'id_barang' => 202,
                'kode_barang' => 'SAR-002',
                'nama_barang' => 'Meja Kerja',
                'merek' => 'Olympic',
                'qrcode_image' => 'qrcode_sarpras_002.png',
                'id_ruangan' => 21,
                'nama_ruangan' => 'Kantor Staff',
                'jumlah_barang' => 15,
                'kondisi_barang' => 'lengkap',
                'ket_barang' => 'Digunakan oleh staff administrasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }
}
