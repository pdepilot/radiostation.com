<?php

namespace App\Filament\Resources\DjResource\Pages;

use App\Filament\Resources\DjResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDj extends EditRecord
{
    protected static string $resource = DjResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
