<?php

namespace App\Filament\Sarpras\Resources\InventarisDKVResource\Pages;

use App\Filament\Sarpras\Resources\InventarisDKVResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInventarisDKV extends ViewRecord
{
    protected static string $resource = InventarisDKVResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
