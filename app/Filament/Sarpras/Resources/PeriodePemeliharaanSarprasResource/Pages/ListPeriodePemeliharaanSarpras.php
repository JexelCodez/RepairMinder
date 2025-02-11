<?php

namespace App\Filament\Sarpras\Resources\PeriodePemeliharaanSarprasResource\Pages;

use App\Filament\Sarpras\Resources\PeriodePemeliharaanSarprasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeriodePemeliharaanSarpras extends ListRecords
{
    protected static string $resource = PeriodePemeliharaanSarprasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
