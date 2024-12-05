<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';

    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Relasi dengan Model Barang
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_kategori');
    }
}