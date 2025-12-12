<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Set;

/**
 * Event Resource
 * Full CRUD with banner, registration, SEO fields
 */
class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    
    protected static ?string $navigationLabel = 'Events';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Event Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'blockquote',
                                'h2',
                                'h3',
                                'bulletList',
                                'orderedList',
                                'link',
                            ]),
                    ]),
                Forms\Components\Section::make('Event Details')
                    ->schema([
                        Forms\Components\TextInput::make('venue')
                            ->maxLength(255)
                            ->placeholder('Venue name'),
                        Forms\Components\TextInput::make('location')
                            ->maxLength(255)
                            ->placeholder('Full address'),
                        Forms\Components\DateTimePicker::make('event_date')
                            ->required()
                            ->native(false),
                        Forms\Components\DateTimePicker::make('event_end_date')
                            ->native(false),
                    ]),
                Forms\Components\Section::make('Banner & Media')
                    ->schema([
                        Forms\Components\FileUpload::make('hero_image')
                            ->label('Event Banner')
                            ->image()
                            ->directory('events')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Registration')
                    ->schema([
                        Forms\Components\TextInput::make('ticket_url')
                            ->label('Ticket/Registration URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://...'),
                        Forms\Components\Toggle::make('registration_enabled')
                            ->label('Enable Registration')
                            ->default(true)
                            ->helperText('Show registration button on frontend'),
                    ]),
                Forms\Components\Section::make('Publishing')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Event')
                            ->default(false),
                        Forms\Components\Select::make('status')
                            ->options([
                                'upcoming' => 'Upcoming',
                                'ongoing' => 'Ongoing',
                                'past' => 'Past',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('upcoming'),
                    ]),
                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('meta_description')
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('hero_image')
                    ->label('Banner')
                    ->size(50)
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('venue')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event_date')
                    ->dateTime()
                    ->sortable()
                    ->label('Date'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'info' => 'upcoming',
                        'success' => 'ongoing',
                        'gray' => 'past',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('view_count')
                    ->numeric()
                    ->sortable()
                    ->label('Views'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'upcoming' => 'Upcoming',
                        'ongoing' => 'Ongoing',
                        'past' => 'Past',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('event_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
