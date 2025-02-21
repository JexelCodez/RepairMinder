<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    use HasFactory;

    protected $table = 'teknisis';

    protected $fillable = [
        'nama',
        'informasi',
    ];

    public function maintenance()
    {
        return $this->hasMany(Maintenance::class, 'id_teknisi');
    }
    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'id_teknisi');
    }
}
