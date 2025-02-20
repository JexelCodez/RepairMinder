<?php

namespace App\Observers;

use App\Models\Maintenance;
use Carbon\Carbon;

class MaintenanceObserver
{
    public function created(Maintenance $maintenance)
    {
        $this->updateNextMaintenanceDate($maintenance);
    }

    public function updated(Maintenance $maintenance)
    {
        if ($maintenance->isDirty('tanggal_pelaksanaan') || $maintenance->isDirty('status')) {
            $this->updateNextMaintenanceDate($maintenance);
        }
    }

    /**
     * Fungsi untuk memperbarui tanggal maintenance selanjutnya.
     */
    private function updateNextMaintenanceDate(Maintenance $maintenance)
    {
        $periode = $maintenance->periode; // Menggunakan relasi langsung

        if ($periode && $maintenance->tanggal_pelaksanaan) {
            $periode->update([
                'tanggal_maintenance_selanjutnya' => Carbon::parse($maintenance->tanggal_pelaksanaan)->addDays($periode->periode),
            ]);
        }
    }
}
