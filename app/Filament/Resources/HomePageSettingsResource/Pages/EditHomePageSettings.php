<?php

namespace App\Filament\Resources\HomePageSettingsResource\Pages;

use App\Filament\Resources\HomePageSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHomePageSettings extends EditRecord
{
    protected static string $resource = HomePageSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
