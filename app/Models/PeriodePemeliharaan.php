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
        'deskripsi',
    ];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'id_barang', 'id_barang');
    }
}
