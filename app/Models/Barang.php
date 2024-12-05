<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = [
        'nama',
        'id_kategori',
        'stok_barang',
        'mac_address',
        'token_qr',
        'status',
        'id_lokasi',
    ];

    protected $casts = [
        'mac_address' => 'array',
    ];

    /**
     * Hubungan dengan Model Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    /**
     * Hubungan dengan Model Lokasi
     */
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi');
    }
}