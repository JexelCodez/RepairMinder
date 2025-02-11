<?php

namespace App\Filament\DKV\Resources\LaporanDKVResource\Pages;

use App\Filament\DKV\Resources\LaporanDKVResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanDKV extends EditRecord
{
    protected static string $resource = LaporanDKVResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
