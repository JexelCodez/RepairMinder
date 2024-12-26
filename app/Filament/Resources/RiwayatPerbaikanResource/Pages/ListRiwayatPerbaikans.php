<?php

namespace App\Filament\Resources\RiwayatPerbaikanResource\Pages;

use App\Filament\Resources\RiwayatPerbaikanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiwayatPerbaikans extends ListRecords
{
    protected static string $resource = RiwayatPerbaikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
