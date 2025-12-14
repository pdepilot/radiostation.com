<?php

namespace App\Filament\Resources\ChatbotKnowledgeResource\Pages;

use App\Filament\Resources\ChatbotKnowledgeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageChatbotKnowledge extends ManageRecords
{
    protected static string $resource = ChatbotKnowledgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

