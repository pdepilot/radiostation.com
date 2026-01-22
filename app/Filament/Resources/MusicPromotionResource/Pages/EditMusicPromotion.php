<?php

namespace App\Filament\Resources\MusicPromotionResource\Pages;

use App\Filament\Resources\MusicPromotionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMusicPromotion extends EditRecord
{
    protected static string $resource = MusicPromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
