<?php

namespace App\Filament\Resources\PeriodePemeliharaanResource\Pages;

use App\Filament\Resources\PeriodePemeliharaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeriodePemeliharaans extends ListRecords
{
    protected static string $resource = PeriodePemeliharaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
