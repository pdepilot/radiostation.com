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

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Create New Chatbot Knowledge Entry')
                    ->description('Add a new knowledge entry to help AskDarling respond to user questions')
                    ->schema([
                        Forms\Components\TextInput::make('keyword')
                            ->label('Keyword')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('e.g. ad rates')
                            ->helperText('The main keyword that triggers this response. Must be unique.'),
                        Forms\Components\Textarea::make('response')
                            ->label('Response')
                            ->required()
                            ->rows(5)
                            ->placeholder('The full answer the bot should give...')
                            ->helperText('The complete response message AskDarling will provide when this keyword is matched.'),
                        Forms\Components\Repeater::make('question_patterns')
                            ->label('Additional Matching Patterns')
                            ->helperText('Add alternative phrases or variations that should also trigger this response (e.g., "advert cost", "sponsorship price")')
                            ->schema([
                                Forms\Components\TextInput::make('pattern')
                                    ->label('Pattern')
                                    ->placeholder('e.g. advert cost')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['pattern'] ?? null)
                            ->defaultItems(0)
                            ->addActionLabel('Add Pattern')
                            ->collapsible()
                            ->collapsed()
                            ->default([]),
                        Forms\Components\Select::make('category')
                            ->label('Category')
                            ->options([
                                'advertising' => 'Advertising',
                                'contact' => 'Contact',
                                'shows' => 'Shows',
                                'events' => 'Events',
                                'contests' => 'Contests',
                                'technical' => 'Technical',
                                'general' => 'General',
                            ])
                            ->placeholder('Select a category (optional)')
                            ->helperText('Categorize this entry for better organization')
                            ->nullable()
                            ->searchable(),
                        Forms\Components\TextInput::make('priority')
                            ->label('Priority')
                            ->numeric()
                            ->default(0)
                            ->helperText('Higher = checked first. Use higher numbers for more important responses.')
                            ->minValue(0)
                            ->maxValue(100),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Only active entries will be used by AskDarling')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2)
                    ->collapsible(),
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
            'index' => Pages\ManageChatbotKnowledge::route('/'),
        ];
    }
}

