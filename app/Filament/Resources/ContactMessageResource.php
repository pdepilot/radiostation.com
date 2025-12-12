<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Contact Message Resource (Read-Only)
 * View form submissions only - no create/edit
 */
class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    protected static ?string $navigationLabel = 'Contact';
    
    protected static ?string $navigationGroup = 'Contact';
    
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->disabled(),
                    ]),
                Forms\Components\Section::make('Message')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->disabled(),
                        Forms\Components\Select::make('type')
                            ->disabled()
                            ->options([
                                'general' => 'General Inquiry',
                                'advertising' => 'Advertising',
                                'partnership' => 'Partnership',
                                'shoutout' => 'Shout-out Request',
                            ]),
                        Forms\Components\Textarea::make('message')
                            ->disabled()
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'new' => 'New',
                                'read' => 'Read',
                                'replied' => 'Replied',
                                'archived' => 'Archived',
                            ])
                            ->disabled(),
                        Forms\Components\TextInput::make('handled_by')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('handled_at')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'general',
                        'info' => 'advertising',
                        'success' => 'partnership',
                        'warning' => 'shoutout',
                    ]),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'new',
                        'info' => 'read',
                        'success' => 'replied',
                        'gray' => 'archived',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Received'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'read' => 'Read',
                        'replied' => 'Replied',
                        'archived' => 'Archived',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'general' => 'General Inquiry',
                        'advertising' => 'Advertising',
                        'partnership' => 'Partnership',
                        'shoutout' => 'Shout-out Request',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_read')
                        ->label('Mark as Read')
                        ->icon('heroicon-o-check')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'read']);
                            });
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }
}
