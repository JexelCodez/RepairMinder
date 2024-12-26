<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pemeliharaans';

    protected $fillable = [
        'id_barang',
        'id_user',
        'jadwal_pemeliharaan',
        'frekuensi_pemeliharaan',
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
