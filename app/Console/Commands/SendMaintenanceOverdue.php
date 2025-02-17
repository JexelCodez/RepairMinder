<?php

namespace App\Console\Commands;

use App\Filament\DKV\Resources\LaporanDKVResource;
use App\Filament\Resources\LaporanResource;
use App\Filament\Sarpras\Resources\LaporanSarprasResource;
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
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
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
        
            // Cek barang ada di model inventaris mana
            if (InventarisDKV::where('kode_barang', $maintenance->kode_barang)->exists()) {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) => 
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'dkv'))
                    )->get();
        
                // Menggunakan LaporanDKVResource untuk route laporan DKV
                $laporUrl = LaporanDKVResource::getUrl('view', ['record' => $maintenance], panel: 'dKV');
            } elseif (InventarisSarpras::where('kode_barang', $maintenance->kode_barang)->exists()) {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) => 
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sarpras'))
                    )->get();
        
                $laporUrl = LaporanSarprasResource::getUrl('view', ['record' => $maintenance], panel: 'sarpras');
            } else {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sija'))
                    )->get();
        
                $laporUrl = LaporanResource::getUrl('view', ['record' => $maintenance], panel: 'admin');
            }
        
            foreach ($teknisiUsers as $user) {
                Notification::make()
                    ->title('🔧 Maintenance Reminder')
                    ->color('info')
                    ->body("Barang {$maintenance->kode_barang} seharusnya sudah maintenance lebih dari 1 hari yang lalu! Klik untuk melihat detail.")
                    ->actions([
                        Action::make('Lihat')->icon('heroicon-o-eye')->url($laporUrl),
                    ])
                    ->sendToDatabase($user);
            }
        }
        

        return self::SUCCESS;
    }
}
