<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $fillable = [
        'id_user',
        'nama_barang',
        'merk_barang',
        'kode_barang',
        'deskripsi_laporan',
        'bukti_laporan',
        'lokasi_barang',
        'status',
        'tanggal_laporan',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

}
