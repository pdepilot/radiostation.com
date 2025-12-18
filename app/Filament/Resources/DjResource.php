<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DjResource\Pages;
use App\Models\Dj;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Set;

/**
 * DJ/OAP Resource
 * Manage On-Air Personalities
 */
class DjResource extends Resource
{
    protected static ?string $model = Dj::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationLabel = 'On-Air Personalities';
    
    protected static ?string $navigationGroup = 'Content';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('stage_name')
                            ->required()
                            ->maxLength(255)
                            ->label('Stage Name / DJ Name'),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\Textarea::make('bio')
                            ->maxLength(1000)
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('specialty')
                            ->maxLength(255)
                            ->placeholder('e.g., Hip-Hop, EDM, Talk Show'),
                    ]),
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('booking_link')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com/booking'),
                    ])
                    ->collapsible()
                    ->collapsed(),
                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar_url')
                            ->label('Avatar')
                            ->image()
                            ->directory('djs')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Social Media')
                    ->schema([
                        Forms\Components\TextInput::make('instagram')
                            ->label('Instagram URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/username'),
                        Forms\Components\TextInput::make('twitter')
                            ->label('Twitter/X URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://twitter.com/username'),
                        Forms\Components\TextInput::make('facebook')
                            ->label('Facebook URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/username'),
                        Forms\Components\TextInput::make('mixcloud')
                            ->label('Mixcloud URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://mixcloud.com/username'),
                    ])
                    ->collapsible()
                    ->collapsed(),
                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured on Homepage')
                            ->default(false),
                        Forms\Components\KeyValue::make('availability')
                            ->label('Availability Schedule')
                            ->keyLabel('Day/Time')
                            ->valueLabel('Available')
                            ->helperText('e.g., Monday: 9am-5pm, Tuesday: 10am-6pm')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->size(50)
                    ->circular()
                    ->defaultImageUrl(asset('assets/images/logo1.jpg')),
                Tables\Columns\TextColumn::make('stage_name')
                    ->label('Stage Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('specialty')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shows_count')
                    ->counts('shows')
                    ->label('Shows')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All')
                    ->trueLabel('Featured only')
                    ->falseLabel('Not featured'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('stage_name', 'asc');
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
            'index' => Pages\ListDjs::route('/'),
            'create' => Pages\CreateDj::route('/create'),
            'edit' => Pages\EditDj::route('/{record}/edit'),
        ];
    }
}
