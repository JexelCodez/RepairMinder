<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'maintenances';

    protected $fillable = [
        'id_periode_pemeliharaan',
        'id_user',
        'id_teknisi',
        'deskripsi_tugas',
        'hasil_maintenance',	
        'status',
        'tanggal_pelaksanaan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodePemeliharaan::class, 'id_periode_pemeliharaan');
    }

    public function inventaris()
    {
        return $this->hasOneThrough(Inventaris::class, PeriodePemeliharaan::class, 'id', 'kode_barang', 'id_periode_pemeliharaan', 'kode_barang');
    }

    public function inventarisDKV()
    {
        return $this->hasOneThrough(InventarisDKV::class, PeriodePemeliharaan::class, 'id', 'kode_barang', 'id_periode_pemeliharaan', 'kode_barang');
    }

    public function inventarisSarpras()
    {
        return $this->hasOneThrough(InventarisSarpras::class, PeriodePemeliharaan::class, 'id', 'kode_barang', 'id_periode_pemeliharaan', 'kode_barang');
    }

}
