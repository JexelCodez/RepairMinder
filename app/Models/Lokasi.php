<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasis';

    protected $fillable = [
        'nama_lokasi',
    ];

    /**
     * Relasi dengan Model Barang
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_lokasi');
    }
}