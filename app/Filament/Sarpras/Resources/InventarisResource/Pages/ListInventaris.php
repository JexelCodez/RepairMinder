<?php

namespace App\Filament\Sarpras\Resources\InventarisResource\Pages;

use App\Filament\Sarpras\Resources\InventarisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventaris extends ListRecords
{
    protected static string $resource = InventarisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
