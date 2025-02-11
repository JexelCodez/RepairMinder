<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class InventarisDKV extends Model
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
                'id_barang' => 101,
                'kode_barang' => 'DKV-001',
                'nama_barang' => 'Kamera DSLR',
                'merek' => 'Canon EOS 90D',
                'qrcode_image' => 'qrcode_dkv_001.png',
                'id_ruangan' => 10,
                'nama_ruangan' => 'Studio Fotografi',
                'jumlah_barang' => 5,
                'kondisi_barang' => 'lengkap',
                'ket_barang' => 'Digunakan untuk kelas fotografi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_inventaris' => 2,
                'id_barang' => 102,
                'kode_barang' => 'DKV-002',
                'nama_barang' => 'Drawing Tablet',
                'merek' => 'Wacom Intuos Pro',
                'qrcode_image' => 'qrcode_dkv_002.png',
                'id_ruangan' => 11,
                'nama_ruangan' => 'Lab Desain',
                'jumlah_barang' => 10,
                'kondisi_barang' => 'lengkap',
                'ket_barang' => 'Digunakan untuk ilustrasi digital',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }
}
