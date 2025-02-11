<?php

namespace App\Filament\Sarpras\Resources\InventarisSarprasResource\Pages;

use App\Filament\Sarpras\Resources\InventarisSarprasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventarisSarpras extends ListRecords
{
    protected static string $resource = InventarisSarprasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
