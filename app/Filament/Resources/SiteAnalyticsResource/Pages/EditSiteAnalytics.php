<?php

namespace App\Filament\Resources\SiteAnalyticsResource\Pages;

use App\Filament\Resources\SiteAnalyticsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiteAnalytics extends EditRecord
{
    protected static string $resource = SiteAnalyticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
