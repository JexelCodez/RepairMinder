<?php

namespace App\Models;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodePemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'periode_pemeliharaans';

    protected $fillable = [
        'periode',
        'kode_barang',
        'kode_barang_kecil',
        'deskripsi',
        'tanggal_maintenance_selanjutnya',
    ];

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($periode) {
            $periode->checkMaintenanceDue();
        });

        static::updated(function ($periode) {
            $periode->checkMaintenanceDue();
        });
    }

    public function checkMaintenanceDue()
    {
        if ($this->tanggal_maintenance_selanjutnya && now()->greaterThanOrEqualTo($this->tanggal_maintenance_selanjutnya)) {
            $user = auth()->user();

            Notification::make()
                ->title('⚠️ Maintenance Due!')
                ->color('warning')
                ->body("🛠️ Maintenance untuk {$this->kode_barang} sudah jatuh tempo. Segera lakukan tindakan.")
                ->actions([
                    Action::make('Proses')
                        ->icon('heroicon-o-eye')
                        // ->url(),
                ])
                ->sendToDatabase($user);
        }
    }

    public function setKodeBarangAttribute($value)
    {
        $this->attributes['kode_barang'] = $value;
        $this->attributes['kode_barang_kecil'] = strtolower($value);
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
}
