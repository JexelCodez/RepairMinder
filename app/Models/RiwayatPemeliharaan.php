<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pemeliharaans';

    protected $fillable = [
        'id_barang',
        'id_user',
        'tanggal_pemeliharaan',
        'kegiatan_pemeliharaan',
        'status',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
