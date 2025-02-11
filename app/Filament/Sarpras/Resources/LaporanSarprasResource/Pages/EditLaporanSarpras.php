<?php

namespace App\Filament\Sarpras\Resources\LaporanSarprasResource\Pages;

use App\Filament\Sarpras\Resources\LaporanSarprasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanSarpras extends EditRecord
{
    protected static string $resource = LaporanSarprasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
