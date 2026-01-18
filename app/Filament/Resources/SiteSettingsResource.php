<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingsResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Site Settings Resource
 * Manage general site settings including social media links
 */
class SiteSettingsResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Site Setting';

    protected static ?string $pluralModelLabel = 'Site Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Social Media Links')
                    ->description('Manage social media links displayed in the footer and other areas of the site')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/darlingfm')
                            ->helperText('Full URL to your Facebook page'),
                        Forms\Components\TextInput::make('twitter_url')
                            ->label('Twitter/X URL')
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('https://twitter.com/darlingfm')
                            ->helperText('Full URL to your Twitter/X profile'),
                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/darlingfm')
                            ->helperText('Full URL to your Instagram profile'),
                        Forms\Components\TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('https://youtube.com/@darlingfm')
                            ->helperText('Full URL to your YouTube channel'),
                        Forms\Components\TextInput::make('tiktok_url')
                            ->label('TikTok URL')
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('https://tiktok.com/@darlingfm')
                            ->helperText('Full URL to your TikTok profile'),
                    ])
                    ->columns(1),
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
            'index' => Pages\ManageSiteSettings::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
