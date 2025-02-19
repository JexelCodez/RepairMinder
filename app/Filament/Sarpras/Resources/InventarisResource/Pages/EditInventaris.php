<?php

namespace App\Filament\Sarpras\Resources\InventarisResource\Pages;

use App\Filament\Sarpras\Resources\InventarisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventaris extends EditRecord
{
    protected static string $resource = InventarisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
