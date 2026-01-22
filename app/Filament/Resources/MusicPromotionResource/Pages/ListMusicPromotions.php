<?php

namespace App\Filament\Resources\MusicPromotionResource\Pages;

use App\Filament\Resources\MusicPromotionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMusicPromotions extends ListRecords
{
    protected static string $resource = MusicPromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
