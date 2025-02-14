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
        'deskripsi_tugas',	
        'status',
        'tanggal_pelaksanaan',
    ];

    // public function setKodeBarangAttribute($value)
    // {
    //     $this->attributes['kode_barang'] = $value;
    //     $this->attributes['kode_barang_kecil'] = strtolower($value);
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodePemeliharaan::class, 'id_periode_pemeliharaan');
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

    protected static function boot()
    {
        parent::boot();

        // Saat maintenance baru dibuat, set tanggal maintenance selanjutnya
        static::created(function ($maintenance) {
            $periode = $maintenance->periode; // Menggunakan relasi langsung

            if ($periode) {
                $periode->update([
                    'tanggal_maintenance_selanjutnya' => now()->addDays($periode->periode),
                ]);
            }
        });

        // Saat maintenance diupdate (misalnya status diubah jadi "selesai"), update tanggal maintenance selanjutnya
        static::updated(function ($maintenance) {
            if ($maintenance->isDirty('status') && $maintenance->status === 'selesai') {
                $periode = $maintenance->periode;

                if ($periode) {
                    $periode->update([
                        'tanggal_maintenance_selanjutnya' => now()->addDays($periode->periode),
                    ]);
                }
            }
        });
    }
}
