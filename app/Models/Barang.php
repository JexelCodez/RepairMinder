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
     * Eager load hubungan kategori dan lokasi secara default
     */
    protected $with = ['kategori', 'lokasi'];

    /**
     * Hubungan dengan Model Kategori dengan Eager Loading
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    /**
     * Hubungan dengan Model Lokasi dengan Eager Loading
     */
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi');
    }
}