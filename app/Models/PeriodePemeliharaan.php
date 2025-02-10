<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodePemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'periode_pemeliharaans';

    protected $fillable = [
        'periode',
        'kode_barang',
        'kode_barang_kecil',
        'deskripsi',
        'tanggal_maintenance_selanjutnya',
    ];

    public function setKodeBarangAttribute($value)
    {
        $this->attributes['kode_barang'] = $value;
        $this->attributes['kode_barang_kecil'] = strtolower($value);
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'kode_barang', 'kode_barang');
    }
}
