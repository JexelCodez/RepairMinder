<?php

namespace App\Filament\DKV\Resources\InventarisDKVResource\Pages;

use App\Filament\DKV\Resources\InventarisDKVResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventarisDKV extends EditRecord
{
    protected static string $resource = InventarisDKVResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
