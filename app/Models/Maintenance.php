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


    protected static function boot()
    {
        parent::boot();

        // Saat maintenance baru dibuat, set tanggal maintenance selanjutnya
        static::created(function ($maintenance) {
            $maintenance->updateNextMaintenanceDate();
        });

        // Saat maintenance diupdate, update tanggal maintenance selanjutnya jika tanggal_pelaksanaan berubah
        static::updated(function ($maintenance) {
            if ($maintenance->isDirty('tanggal_pelaksanaan') || $maintenance->isDirty('status')) {
                $maintenance->updateNextMaintenanceDate();
            }
        });
    }

    /**
     * Fungsi untuk memperbarui tanggal maintenance selanjutnya.
     */
    public function updateNextMaintenanceDate()
    {
        $periode = $this->periode; // Menggunakan relasi langsung

        if ($periode) {
            // Pastikan ada tanggal pelaksanaan yang valid
            if ($this->tanggal_pelaksanaan) {
                $periode->update([
                    'tanggal_maintenance_selanjutnya' => \Carbon\Carbon::parse($this->tanggal_pelaksanaan)->addDays($periode->periode),
                ]);
            }
        }
    }

}
