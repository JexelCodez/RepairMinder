<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource;
use Awcodes\Overlook\Contracts\CustomizeOverlookWidget;
use Awcodes\Overlook\Concerns\HandlesOverlookWidgetCustomization;
use Illuminate\Database\Eloquent\Builder;

class LaporanProcessedResource extends LaporanResource implements CustomizeOverlookWidget
{
    use HandlesOverlookWidgetCustomization;

    public static function getOverlookWidgetQuery(Builder $query): Builder
    {
        return $query->where('status', 'processed');
    }

    public static function getOverlookWidgetTitle(): string
    {
        return 'Laporan Processed';
    }
}