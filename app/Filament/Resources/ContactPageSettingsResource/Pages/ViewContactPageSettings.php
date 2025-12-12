<?php

namespace App\Filament\Resources\ContactPageSettingsResource\Pages;

use App\Filament\Resources\ContactPageSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContactPageSettings extends ViewRecord
{
    protected static string $resource = ContactPageSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
