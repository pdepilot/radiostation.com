<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactPageSettingsResource\Pages;
use App\Models\ContactPageSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Contact Page Settings Resource
 * Manage contact page content
 */
class ContactPageSettingsResource extends Resource
{
    protected static ?string $model = ContactPageSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    protected static ?string $navigationLabel = 'Contact Page';
    
    protected static ?string $navigationGroup = 'Contact';
    
    protected static ?int $navigationSort = 6;
    
    protected static ?string $modelLabel = 'Contact Page Setting';
    
    protected static ?string $pluralModelLabel = 'Contact Page Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Page Content')
                    ->schema([
                        Forms\Components\TextInput::make('page_title')
                            ->label('Page Title')
                            ->default('Contact Us')
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('page_description')
                            ->label('Page Description')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'bulletList',
                            ])
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\Textarea::make('office_address')
                            ->label('Office Address')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('whatsapp')
                            ->label('WhatsApp Number')
                            ->tel()
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Map & Location')
                    ->schema([
                        Forms\Components\TextInput::make('map_url')
                            ->label('Google Maps URL')
                            ->url()
                            ->maxLength(500)
                            ->default('https://maps.app.goo.gl/qPWKXDAngcD8thcc9'),
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
            'index' => Pages\ManageContactPageSettings::route('/'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }
}
