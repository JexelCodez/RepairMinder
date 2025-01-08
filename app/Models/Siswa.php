<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    //
    use HasFactory, Notifiable;

    protected $table = 'siswas';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'kelas',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
