<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChatbotKnowledgeResource\Pages;
use App\Models\ChatbotKnowledge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ChatbotKnowledgeResource extends Resource
{
    protected static ?string $model = ChatbotKnowledge::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationLabel = 'Chatbot Knowledge';
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Knowledge Entry')
                    ->schema([
                        Forms\Components\TextInput::make('keyword')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g., ad rates, contact, shows')
                            ->helperText('Primary keyword that triggers this response'),
                        Forms\Components\Textarea::make('response')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('The response message for this keyword'),
                        Forms\Components\Textarea::make('question_patterns')
                            ->label('Question Patterns (JSON array)')
                            ->rows(3)
                            ->placeholder('["/ad rate/i", "/ads/i", "/advertising/i"]')
                            ->helperText('JSON array of regex patterns that match this topic'),
                        Forms\Components\Select::make('category')
                            ->options([
                                'advertising' => 'Advertising',
                                'contact' => 'Contact',
                                'shows' => 'Shows',
                                'events' => 'Events',
                                'technical' => 'Technical',
                                'general' => 'General',
                            ])
                            ->nullable(),
                        Forms\Components\TextInput::make('priority')
                            ->numeric()
                            ->default(0)
                            ->helperText('Higher priority entries are checked first'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('keyword')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('response')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'advertising' => 'warning',
                        'contact' => 'info',
                        'shows' => 'success',
                        'events' => 'primary',
                        'technical' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('priority')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Usage')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChatbotKnowledge::class,
            'create' => Pages\CreateChatbotKnowledge::class,
            'edit' => Pages\EditChatbotKnowledge::class,
        ];
    }
}

