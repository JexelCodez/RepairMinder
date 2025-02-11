<?php

namespace App\Filament\DKV\Resources\MaintenanceDKVResource\Pages;

use App\Filament\DKV\Resources\MaintenanceDKVResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaintenanceDKVS extends ListRecords
{
    protected static string $resource = MaintenanceDKVResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
