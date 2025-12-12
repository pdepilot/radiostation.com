<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomePageSettingsResource\Pages;
use App\Models\HomePageSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Home Page Settings Resource
 * Manage hero slider, featured sections, live player settings
 */
class HomePageSettingsResource extends Resource
{
    protected static ?string $model = HomePageSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?string $navigationLabel = 'Home';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $modelLabel = 'Home Page Setting';
    
    protected static ?string $pluralModelLabel = 'Home Page Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Hero Section')
                    ->schema([
                        Forms\Components\TextInput::make('hero_title')
                            ->label('Hero Title')
                            ->default('DARLING FM')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('hero_subtitle')
                            ->label('Hero Subtitle')
                            ->default('OWERRI')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('hero_background')
                            ->label('Hero Background Image')
                            ->image()
                            ->directory('homepage')
                            ->imageEditor(),
                    ]),
                Forms\Components\Section::make('Live Stream Player')
                    ->schema([
                        Forms\Components\TextInput::make('live_stream_title')
                            ->label('Stream Title')
                            ->default('107.3 FM')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('live_stream_url')
                            ->label('Stream URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://...'),
                        Forms\Components\TextInput::make('live_stream_backup_url')
                            ->label('Backup Stream URL')
                            ->url()
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Featured Sections')
                    ->schema([
                        Forms\Components\Toggle::make('featured_news_enabled')
                            ->label('Show Featured News')
                            ->default(true),
                        Forms\Components\Toggle::make('featured_shows_enabled')
                            ->label('Show Featured Shows')
                            ->default(true),
                        Forms\Components\Toggle::make('featured_djs_enabled')
                            ->label('Show Featured DJs')
                            ->default(true),
                        Forms\Components\TextInput::make('featured_items_count')
                            ->label('Number of Featured Items')
                            ->numeric()
                            ->default(3)
                            ->minValue(1)
                            ->maxValue(10),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->limit(50),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomePageSettings::route('/'),
            'edit' => Pages\EditHomePageSettings::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }
}
