<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DetailPeminjaman extends Model
{
    //

    use HasFactory;
    protected $table = 'detail_peminjamans';
    protected $fillable = [
        'id_peminjaman',
        'tgl_kembali',
        'status',
        'ket_ditolak_pengajuan',
        'kondisi_barang_akhir',
        'ket_tidak_lengkap_awal',
        'ket_tidak_lengkap_akhir',
    ];


    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }
}
