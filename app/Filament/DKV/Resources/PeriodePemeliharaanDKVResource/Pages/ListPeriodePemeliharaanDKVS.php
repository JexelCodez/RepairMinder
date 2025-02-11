<?php

namespace App\Filament\DKV\Resources\PeriodePemeliharaanDKVResource\Pages;

use App\Filament\DKV\Resources\PeriodePemeliharaanDKVResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeriodePemeliharaanDKVS extends ListRecords
{
    protected static string $resource = PeriodePemeliharaanDKVResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
