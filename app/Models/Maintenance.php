<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'maintenances';

    protected $fillable = [
        'kode_barang',
        'kode_barang_kecil',
        'id_user',
        'deskripsi_tugas',	
        'status',
        'tanggal_pelaksanaan',
    ];

    public function setKodeBarangAttribute($value)
    {
        $this->attributes['kode_barang'] = $value;
        $this->attributes['kode_barang_kecil'] = strtolower($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'kode_barang', 'kode_barang');
    }

    protected static function boot()
    {
        parent::boot();

        // Saat maintenance baru dibuat, set tanggal maintenance selanjutnya
        static::created(function ($maintenance) {
            $periode = PeriodePemeliharaan::where('kode_barang', $maintenance->kode_barang)->first();

            if ($periode) {
                $periode->update([
                    'tanggal_maintenance_selanjutnya' => now()->addDays($periode->periode),
                ]);
            }
        });

        // Saat maintenance diupdate (misalnya status diubah jadi "selesai"), update tanggal maintenance selanjutnya
        static::updated(function ($maintenance) {
            if ($maintenance->isDirty('status') && $maintenance->status === 'selesai') {
                $periode = PeriodePemeliharaan::where('kode_barang', $maintenance->kode_barang)->first();

                if ($periode) {
                    $periode->update([
                        'tanggal_maintenance_selanjutnya' => now()->addDays($periode->periode),
                    ]);
                }
            }
        });
    }
}
