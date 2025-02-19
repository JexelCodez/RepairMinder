<?php

namespace App\Filament\Sarpras\Resources\InventarisDKVResource\Pages;

use App\Filament\Sarpras\Resources\InventarisDKVResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventarisDKV extends EditRecord
{
    protected static string $resource = InventarisDKVResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
