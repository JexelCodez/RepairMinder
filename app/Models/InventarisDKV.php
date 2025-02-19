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
                'nama_jenis_barang' => 'Peralatan Desain',
                'nama_barang' => 'Drawing Tablet',
                'merek' => 'Wacom Intuos Pro',
                'kode_barang' => 'DKV-001',
                'qrcode_image' => 'https://example.com/qrcode_dkv_001.png',
                'stok_barang' => 10,
                'id_ruangan' => 11,
                'nama_ruangan' => 'Lab Desain',
                'jumlah_barang' => 10,
                'kondisi_barang' => 'lengkap',
                'ket_barang' => 'Digunakan untuk ilustrasi digital',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'id_inventaris' => 2,
                'id_barang' => 202,
                'nama_jenis_barang' => 'Peralatan Fotografi',
                'nama_barang' => 'Kamera DSLR',
                'merek' => 'Canon EOS 90D',
                'kode_barang' => 'DKV-002',
                'qrcode_image' => 'https://example.com/qrcode_dkv_002.png',
                'stok_barang' => 5,
                'id_ruangan' => 10,
                'nama_ruangan' => 'Studio Fotografi',
                'jumlah_barang' => 5,
                'kondisi_barang' => 'lengkap',
                'ket_barang' => 'Digunakan untuk kelas fotografi',
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

    public function updateKondisiBarangToLengkap()
    {
        return $this->updateKondisiBarang('lengkap');
    }
}
