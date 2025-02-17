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

        $kemarin = Carbon::yesterday()->toDateString();
        $maintenanceList = PeriodePemeliharaan::whereDate('tanggal_maintenance_selanjutnya', $kemarin)->get();

        \Log::info('Total maintenance list matching criteria: ' . $maintenanceList->count());

        if ($maintenanceList->isEmpty()) {
            \Log::info('No maintenance items match the criteria.');
            return self::SUCCESS;
        }

        foreach ($maintenanceList as $maintenance) {
            $teknisiUsers = collect();
            $kodeBarang = $maintenance->kode_barang; // default

            // Cek barang ada di model inventaris mana dan ambil data kode barang dari relasinya
            if (InventarisDKV::where('kode_barang', $maintenance->kode_barang)->exists()) {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'dkv'))
                    )->get();

                // Mengambil data dari relasi inventarisDKV
                $kodeBarang = $maintenance->inventarisDKV->kode_barang ?? $maintenance->kode_barang;
                $laporUrl = MaintenanceDKVResource::getUrl('create', [
                    'kode_barang' => $kodeBarang,
                ], panel: 'dKV');
            } elseif (InventarisSarpras::where('kode_barang', $maintenance->kode_barang)->exists()) {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sarpras'))
                    )->get();

                // Mengambil data dari relasi inventarisSarpras
                $kodeBarang = $maintenance->inventarisSarpras->kode_barang ?? $maintenance->kode_barang;
                $laporUrl = MaintenanceSarprasResource::getUrl('create', [
                    'kode_barang' => $kodeBarang,
                ], panel: 'sarpras');
            } else {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sija'))
                    )->get();

                // Mengambil data dari relasi inventaris (default)
                $kodeBarang = $maintenance->inventaris->kode_barang ?? $maintenance->kode_barang;
                $laporUrl = MaintenanceResource::getUrl('create', [
                    'kode_barang' => $kodeBarang,
                ], panel: 'admin');
            }

            foreach ($teknisiUsers as $user) {
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