<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'jenis_barang';

    // Primary key
    protected $primaryKey = 'id_jenis_barang';

    // Kolom yang dapat diisi
    protected $fillable = [
        'nama_jenis_barang',
    ];

    // Relasi dengan model Barang
    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_jenis_barang', 'id_jenis_barang');
    }
}
