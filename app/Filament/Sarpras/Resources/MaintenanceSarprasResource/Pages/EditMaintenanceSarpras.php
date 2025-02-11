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
}
