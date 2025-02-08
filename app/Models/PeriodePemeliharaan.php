<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodePemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'periode_pemeliharaans';

    protected $fillable = [
        'periode',
        'kode_barang',
        'deskripsi',
        'tanggal_maintenance_selanjutnya',
    ];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'kode_barang', 'kode_barang');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($maintenance) {
            $periode = PeriodePemeliharaan::where('kode_barang', $maintenance->kode_barang)->first();

            if ($periode) {
                $periode->update([
                    'tanggal_maintenance_selanjutnya' => now()->addDays($periode->periode),
                ]);
            }
        });
    }
}
