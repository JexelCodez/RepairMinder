<?php

namespace App\Filament\Resources\RiwayatPerbaikanResource\Pages;

use App\Filament\Resources\RiwayatPerbaikanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiwayatPerbaikan extends EditRecord
{
    protected static string $resource = RiwayatPerbaikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
