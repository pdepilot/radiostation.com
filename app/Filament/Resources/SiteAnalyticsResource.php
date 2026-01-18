<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteAnalyticsResource\Pages;
use App\Models\SiteAnalytics;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SiteAnalyticsResource extends Resource
{
    protected static ?string $model = SiteAnalytics::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Site Analytics';

    protected static ?string $navigationGroup = 'Analytics';

    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Hide from navigation
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('ip')
                    ->maxLength(45)
                    ->disabled(),
                Forms\Components\TextInput::make('city')
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->maxLength(255),
                Forms\Components\TextInput::make('country')
                    ->maxLength(255),
                Forms\Components\TextInput::make('page')
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\Textarea::make('user_agent')
                    ->rows(3)
                    ->disabled(),
                Forms\Components\DateTimePicker::make('created_at')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->default('Guest')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('page')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('city')
                    ->form([
                        Forms\Components\TextInput::make('city')
                            ->placeholder('Filter by city'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['city'],
                            fn (Builder $query, $city): Builder => $query->where('city', 'like', "%{$city}%"),
                        );
                    }),
                Tables\Filters\Filter::make('country')
                    ->form([
                        Forms\Components\TextInput::make('country')
                            ->placeholder('Filter by country'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['country'],
                            fn (Builder $query, $country): Builder => $query->where('country', 'like', "%{$country}%"),
                        );
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Date From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Date Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s'); // Auto-refresh every 30 seconds
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
            'index' => Pages\ListSiteAnalytics::route('/'),
            'view' => Pages\ViewSiteAnalytics::route('/{record}'),
        ];
    }
}
