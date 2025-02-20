<?php

namespace App\Filament\Sarpras\Resources\MaintenanceSarprasResource\Pages;

use App\Filament\Sarpras\Resources\MaintenanceSarprasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaintenanceSarpras extends EditRecord
{
    protected static string $resource = MaintenanceSarprasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['sumber_data'])) {
            // Pastikan data sumber_data tetap ada saat edit
            $data['sumber_data'] = $data['sumber_data'];
        }

        return $data;
    }

}
