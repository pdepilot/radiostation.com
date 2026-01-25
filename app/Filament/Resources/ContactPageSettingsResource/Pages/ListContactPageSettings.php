<?php

namespace App\Filament\Resources\ContactPageSettingsResource\Pages;

use App\Filament\Resources\ContactPageSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactPageSettings extends ListRecords
{
    protected static string $resource = ContactPageSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
