<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MusicPromotionResource\Pages;
use App\Filament\Resources\MusicPromotionResource\RelationManagers;
use App\Models\MusicPromotion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MusicPromotionResource extends Resource
{
    protected static ?string $model = MusicPromotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    protected static ?string $navigationLabel = 'Music Promotions';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Promotion Details')
                    ->schema([
                        Forms\Components\TextInput::make('artist_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('track_title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->rows(3),
                        Forms\Components\TextInput::make('audio_embed_url')
                            ->url()
                            ->maxLength(500)
                            ->label('Audio Embed URL'),
                        Forms\Components\FileUpload::make('cover_image')
                            ->image()
                            ->directory('promotions')
                            ->label('Cover Image'),
                        Forms\Components\TextInput::make('cta_url')
                            ->url()
                            ->maxLength(500)
                            ->label('Call-to-Action URL'),
                    ])->columns(2),
                Forms\Components\Section::make('Promotion Settings')
                    ->schema([
                        Forms\Components\Select::make('duration_days')
                            ->options([
                                7 => '7 Days',
                                14 => '14 Days',
                            ])
                            ->required()
                            ->disabled(fn ($record) => $record && $record->status !== 'pending'),
                        Forms\Components\TextInput::make('price_paid')
                            ->numeric()
                            ->prefix('₦')
                            ->required()
                            ->disabled(fn ($record) => $record && $record->status !== 'pending'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'active' => 'Active',
                                'expired' => 'Expired',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Starts At'),
                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('Ends At'),
                    ])->columns(2),
                Forms\Components\Section::make('Analytics')
                    ->schema([
                        Forms\Components\TextInput::make('impressions')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                        Forms\Components\TextInput::make('clicks')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                    ])->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->circular()
                    ->size(50),
                Tables\Columns\TextColumn::make('track_title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('artist_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Duration')
                    ->suffix(' days')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_paid')
                    ->money('NGN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'expired' => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('impressions')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('clicks')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'expired' => 'Expired',
                    ]),
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMusicPromotions::route('/'),
            'create' => Pages\CreateMusicPromotion::route('/create'),
            'edit' => Pages\EditMusicPromotion::route('/{record}/edit'),
        ];
    }
}
