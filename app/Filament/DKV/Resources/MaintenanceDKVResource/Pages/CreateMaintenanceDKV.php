<?php

namespace App\Filament\DKV\Resources\MaintenanceDKVResource\Pages;

use App\Filament\DKV\Resources\MaintenanceDKVResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;
use App\Models\PeriodePemeliharaan;

class CreateMaintenanceDKV extends CreateRecord
{
    protected static string $resource = MaintenanceDKVResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        parent::mount();
        // Periksa apakah query parameter kode_barang ada
        if ($kodeBarang = request()->query('kode_barang')) {
            // Cari record PeriodePemeliharaan dengan kode_barang tersebut
            $periode = PeriodePemeliharaan::where('kode_barang', $kodeBarang)->first();
            if ($periode) {
                // Isi field select dengan record id dari periode
                $this->form->fill([
                    'id_periode_pemeliharaan' => $periode->kode_barang,
                ]);
            }
        }
    }
}