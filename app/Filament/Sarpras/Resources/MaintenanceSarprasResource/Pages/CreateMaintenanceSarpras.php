<?php

namespace App\Filament\Sarpras\Resources\MaintenanceSarprasResource\Pages;

use App\Filament\Sarpras\Resources\MaintenanceSarprasResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\PeriodePemeliharaan;
use Filament\Actions;

class CreateMaintenanceSarpras extends CreateRecord
{
    protected static string $resource = MaintenanceSarprasResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        parent::mount();
        
        // Mulai dengan data default id_user
        $data = [
            'id_user' => auth()->id(),
        ];

        // Jika terdapat query parameter kode_barang, cari data periode dan isi sumber_data & id_periode_pemeliharaan
        if ($kodeBarang = request()->query('kode_barang')) {
            $periode = PeriodePemeliharaan::where('kode_barang', $kodeBarang)->first();
            if ($periode) {
                if ($periode->inventarisSarpras) {
                    $data['sumber_data'] = 'inventaris_sarpras';
                } elseif ($periode->inventarisDKV) {
                    $data['sumber_data'] = 'inventaris_dkv';
                } elseif ($periode->inventaris) {
                    $data['sumber_data'] = 'inventaris';
                }
                // Isi id_periode_pemeliharaan jika sumber_data telah ditentukan
                if (isset($data['sumber_data'])) {
                    $data['id_periode_pemeliharaan'] = $periode->id;
                }
            }
        }

        // Isi form dengan data gabungan; id_user akan tetap terisi default bila tidak ada nilai lain yang menimpanya
        $this->form->fill($data);
    }
}