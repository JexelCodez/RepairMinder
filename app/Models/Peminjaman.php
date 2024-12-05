<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Peminjaman extends Model
{
    //

    use HasFactory;
    protected $table = 'peminjamans';
    protected $fillable = [
        'id_users',
        'id_siswas',
        'keterangan_peminjaman',
        'tgl_pinjam',
        'tgl_kembali',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_peminjaman');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    // Relasi tambahan jika diperlukan
    public function siswas()
    {
        return $this->belongsTo(Siswa::class, 'id_siswas');
    }
}

