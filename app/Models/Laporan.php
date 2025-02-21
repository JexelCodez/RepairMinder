<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $fillable = [
        'id_user',
        'id_teknisi',
        'nama_barang',
        'merk_barang',
        'kode_barang',
        'deskripsi_laporan',
        'bukti_laporan',
        'lokasi_barang',
        'status',
        'tanggal_laporan',
        'hasil_laporan',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi');
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'kode_barang', 'kode_barang');
    }

    public function inventarisDKV()
    {
        return $this->belongsTo(InventarisDKV::class, 'kode_barang', 'kode_barang');
    }

    public function inventarisSarpras()
    {
        return $this->belongsTo(InventarisSarpras::class, 'kode_barang', 'kode_barang');
    }

}
