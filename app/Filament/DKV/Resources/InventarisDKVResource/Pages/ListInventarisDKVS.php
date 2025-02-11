<?php

namespace App\Filament\DKV\Resources\InventarisDKVResource\Pages;

use App\Filament\DKV\Resources\InventarisDKVResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventarisDKVS extends ListRecords
{
    protected static string $resource = InventarisDKVResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
