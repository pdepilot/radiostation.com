<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiveStreamResource\Pages;
use App\Models\LiveStream;
use App\Models\Show;
use App\Models\Dj;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Live Stream Resource
 * Manage live streaming sessions
 */
class LiveStreamResource extends Resource
{
    protected static ?string $model = LiveStream::class;

    protected static ?string $navigationIcon = 'heroicon-o-signal';
    
    protected static ?string $navigationLabel = 'Live Streams';
    
    protected static ?string $navigationGroup = 'Broadcasting';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Stream Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('show_id')
                            ->label('Show')
                            ->relationship('show', 'title')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('dj_id')
                            ->label('DJ/Presenter')
                            ->relationship('dj', 'stage_name')
                            ->searchable()
                            ->preload(),
                    ]),
                Forms\Components\Section::make('Stream Settings')
                    ->schema([
                        Forms\Components\TextInput::make('stream_url')
                            ->url()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('https://...'),
                        Forms\Components\TextInput::make('server_host')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('bitrate')
                            ->numeric()
                            ->suffix('kbps'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'scheduled' => 'Scheduled',
                                'live' => 'Live',
                                'ended' => 'Ended',
                            ])
                            ->default('scheduled')
                            ->required(),
                        Forms\Components\Toggle::make('chat_enabled')
                            ->label('Enable Chat')
                            ->default(true),
                    ]),
                Forms\Components\Section::make('Schedule')
                    ->schema([
                        Forms\Components\DateTimePicker::make('scheduled_for'),
                        Forms\Components\DateTimePicker::make('started_at'),
                        Forms\Components\DateTimePicker::make('ended_at'),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Statistics')
                    ->schema([
                        Forms\Components\TextInput::make('listener_count')
                            ->label('Current Listeners')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->helperText('Current active listeners (auto-updated during live stream)'),
                        Forms\Components\Placeholder::make('total_listeners')
                            ->label('Total Listeners (All Time)')
                            ->content(fn ($record) => $record ? number_format($record->listenerSessions()->count()) : '0')
                            ->helperText('Total count of all listeners who have tuned into this stream'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('show.title')
                    ->label('Show')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dj.stage_name')
                    ->label('DJ')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'live' => 'danger',
                        'scheduled' => 'warning',
                        'ended' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('listener_count')
                    ->label('Current Listeners')
                    ->numeric()
                    ->sortable()
                    ->description(fn ($record) => 'Active now'),
                Tables\Columns\TextColumn::make('listener_sessions_count')
                    ->label('Total Listeners')
                    ->numeric()
                    ->sortable()
                    ->description(fn ($record) => 'All listeners who tuned in')
                    ->counts('listenerSessions')
                    ->weight('bold')
                    ->color('success'),
                Tables\Columns\TextColumn::make('scheduled_for')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'live' => 'Live',
                        'ended' => 'Ended',
                    ]),
                Tables\Filters\SelectFilter::make('show_id')
                    ->label('Show')
                    ->relationship('show', 'title'),
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
            ->defaultSort('scheduled_for', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLiveStreams::route('/'),
            'create' => Pages\CreateLiveStream::route('/create'),
            'edit' => Pages\EditLiveStream::route('/{record}/edit'),
        ];
    }
}
