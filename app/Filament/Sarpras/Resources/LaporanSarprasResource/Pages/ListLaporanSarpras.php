<?php

namespace App\Filament\Sarpras\Resources\LaporanSarprasResource\Pages;

use App\Filament\Sarpras\Resources\LaporanSarprasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanSarpras extends ListRecords
{
    protected static string $resource = LaporanSarprasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
