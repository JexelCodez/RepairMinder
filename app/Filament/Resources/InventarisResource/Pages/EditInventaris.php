<?php

namespace App\Filament\Resources\InventarisResource\Pages;

use App\Filament\Resources\InventarisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;

class EditInventaris extends EditRecord
{
    protected static string $resource = InventarisResource::class;

    // Override handleRecordUpdate untuk menangani update melalui API
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Panggil method updateKondisiBarang yang ada di model
        $success = $record->updateKondisiBarang($data['kondisi_barang']);

        // Mengecek apakah API berhasil merespon
        if ($success) {
            // Jika sukses, perbarui record di database lokal
            $record->update([
                'kondisi_barang' => $data['kondisi_barang'],
            ]);
            
            // Kembalikan record yang sudah diperbarui
            return $record;
        } else {
            // Jika API gagal, lempar exception atau beri pesan error
            throw new \Exception("Gagal memperbarui kondisi barang di API.");
        }
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
