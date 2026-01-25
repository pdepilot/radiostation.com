<?php

namespace App\Filament\Resources\SiteAnalyticsResource\Pages;

use App\Filament\Resources\SiteAnalyticsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSiteAnalytics extends ViewRecord
{
    protected static string $resource = SiteAnalyticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Analytics are read-only - created automatically
        ];
    }
}
