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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Transform question_patterns from array of strings to array of objects for Repeater when editing
        if (isset($data['question_patterns']) && is_array($data['question_patterns'])) {
            $patterns = $data['question_patterns'];
            if (empty($patterns)) {
                return $data;
            }
            // Check if already in object format
            if (isset($patterns[0]) && is_array($patterns[0]) && isset($patterns[0]['pattern'])) {
                return $data; // Already in correct format
            }
            // Convert array of strings to array of objects
            $data['question_patterns'] = array_map(function ($pattern) {
                if (is_array($pattern) && isset($pattern['pattern'])) {
                    return $pattern;
                }
                return ['pattern' => is_string($pattern) ? $pattern : (string)$pattern];
            }, $patterns);
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Transform question_patterns from array of objects back to array of strings for database
        if (isset($data['question_patterns']) && is_array($data['question_patterns'])) {
            $data['question_patterns'] = array_values(array_filter(array_map(function ($item) {
                if (is_array($item) && isset($item['pattern'])) {
                    return $item['pattern'];
                }
                return is_string($item) ? $item : null;
            }, $data['question_patterns'])));
        }
        return $data;
    }
}

