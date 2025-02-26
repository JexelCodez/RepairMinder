<?php

namespace App\Console\Commands;

use App\Filament\DKV\Resources\MaintenanceDKVResource;
use App\Filament\Resources\MaintenanceResource;
use App\Filament\Sarpras\Resources\MaintenanceSarprasResource;
use Illuminate\Console\Command;
use App\Models\PeriodePemeliharaan;
use App\Models\InventarisDKV;
use App\Models\InventarisSarpras;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Carbon\Carbon;

class SendMaintenanceOverdue extends Command
{
    protected $signature = 'maintenance:overdue';
    protected $description = 'Command description';

    public function handle(): int
    {
        \Log::info('Maintenance reminder command triggered at: ' . now()->toDateTimeString());

        $kemarin = Carbon::tomorrow()->toDateString();
        $maintenanceList = PeriodePemeliharaan::whereDate('tanggal_maintenance_selanjutnya', $kemarin)->get();

        \Log::info('Total maintenance list matching criteria: ' . $maintenanceList->count());

        if ($maintenanceList->isEmpty()) {
            \Log::info('No maintenance items match the criteria.');
            return self::SUCCESS;
        }

        foreach ($maintenanceList as $maintenance) {
            // Tetap ambil kode barang default
            $kodeBarang = $maintenance->kode_barang; 
        
            // Karena laporan overdue dikirim ke semua user terkait (termasuk admin, teknisi dari berbagai zone)
            // kita ambil daftar user dahulu sesuai kondisi (tanpa meng-set laporUrl di sini)
            if (InventarisDKV::where('kode_barang', $maintenance->kode_barang)->exists()) {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'dkv'))
                    )
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sarpras'))
                    )
                    ->get();
        
                // Jika maintenance terkait inventarisDKV, ambil data kode_barang dari relasi (jika ada)
                $kodeBarang = $maintenance->inventarisDKV->kode_barang ?? $maintenance->kode_barang;
            } elseif (InventarisSarpras::where('kode_barang', $maintenance->kode_barang)->exists()) {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sarpras'))
                    )
                    ->get();
        
                $kodeBarang = $maintenance->inventarisSarpras->kode_barang ?? $maintenance->kode_barang;
            } else {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sija'))
                    )
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sarpras'))
                    )
                    ->get();
        
                $kodeBarang = $maintenance->inventaris->kode_barang ?? $maintenance->kode_barang;
            }
        
            // Kirim notifikasi ke setiap user dengan URL laporan disesuaikan berdasar zone user
            foreach ($teknisiUsers as $user) {
                $zone = strtolower(optional($user->zoneUser)->zone_name);
                if ($zone === 'dkv') {
                    $laporUrl = MaintenanceDKVResource::getUrl('create', [
                        'kode_barang' => $kodeBarang,
                    ], panel: 'dKV');
                } elseif ($zone === 'sarpras') {
                    $laporUrl = MaintenanceSarprasResource::getUrl('create', [
                        'kode_barang' => $kodeBarang,
                    ], panel: 'sarpras');
                } else {
                    $laporUrl = MaintenanceResource::getUrl('create', [
                        'kode_barang' => $kodeBarang,
                    ], panel: 'admin');
                }
        
                Notification::make()
                    ->title('🔧 Maintenance Overdue')
                    ->color('info')
                    ->body("Barang {$kodeBarang} seharusnya sudah maintenance lebih dari 1 hari yang lalu! Klik tombol Proses untuk mengisi laporan maintenance.")
                    ->actions([
                        Action::make('Proses')
                            ->icon('heroicon-o-cog')
                            ->url($laporUrl),
                    ])
                    ->sendToDatabase($user);
            }
        }

        return self::SUCCESS;
    }
}