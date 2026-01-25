<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\SiteAnalytics;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\DB;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Users';
    
    protected static ?string $navigationGroup = 'User Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('state')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('city')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('avatar_url')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('role')
                    ->required(),
                Forms\Components\TextInput::make('bio')
                    ->maxLength(800)
                    ->default(null),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\Toggle::make('is_verified')
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latest_city')
                    ->label('City')
                    ->getStateUsing(function ($record) {
                        $latest = $record->latestSiteAnalytics;
                        return $latest?->city ?? ($record->city ?? 'Guest');
                    })
                    ->searchable(
                        query: function (Builder $query, string $search): Builder {
                            return $query->whereHas('latestSiteAnalytics', function ($q) use ($search) {
                                $q->where('city', 'like', "%{$search}%");
                            })->orWhere('city', 'like', "%{$search}%");
                        }
                    )
                    ->sortable(
                        query: function (Builder $query, string $direction): Builder {
                            return $query->leftJoin('site_analytics as latest_sa', function ($join) {
                                $join->on('users.id', '=', 'latest_sa.user_id')
                                    ->whereRaw('latest_sa.id = (SELECT id FROM site_analytics WHERE user_id = users.id ORDER BY created_at DESC LIMIT 1)');
                            })
                            ->orderBy('latest_sa.city', $direction)
                            ->select('users.*');
                        }
                    ),
                Tables\Columns\TextColumn::make('latest_state')
                    ->label('State')
                    ->getStateUsing(function ($record) {
                        $latest = $record->latestSiteAnalytics;
                        return $latest?->state ?? ($record->state ?? 'Guest');
                    })
                    ->searchable(
                        query: function (Builder $query, string $search): Builder {
                            return $query->whereHas('latestSiteAnalytics', function ($q) use ($search) {
                                $q->where('state', 'like', "%{$search}%");
                            })->orWhere('state', 'like', "%{$search}%");
                        }
                    )
                    ->sortable(
                        query: function (Builder $query, string $direction): Builder {
                            return $query->leftJoin('site_analytics as latest_sa', function ($join) {
                                $join->on('users.id', '=', 'latest_sa.user_id')
                                    ->whereRaw('latest_sa.id = (SELECT id FROM site_analytics WHERE user_id = users.id ORDER BY created_at DESC LIMIT 1)');
                            })
                            ->orderBy('latest_sa.state', $direction)
                            ->select('users.*');
                        }
                    ),
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->size(50)
                    ->circular()
                    ->defaultImageUrl(asset('assets/images/logo1.jpg')),
                Tables\Columns\TextColumn::make('role'),
                Tables\Columns\TextColumn::make('bio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'user' => 'User',
                        'admin' => 'Admin',
                        'dj' => 'DJ',
                    ]),
                Tables\Filters\Filter::make('latest_state')
                    ->label('State')
                    ->form([
                        Forms\Components\TextInput::make('state')
                            ->label('State')
                            ->placeholder('Filter by latest state'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['state'],
                            fn (Builder $query, $state): Builder => $query->whereHas('latestSiteAnalytics', function ($q) use ($state) {
                                $q->where('state', 'like', "%{$state}%");
                            })->orWhere('state', 'like', "%{$state}%"),
                        );
                    }),
                Tables\Filters\Filter::make('latest_city')
                    ->label('City')
                    ->form([
                        Forms\Components\TextInput::make('city')
                            ->label('City')
                            ->placeholder('Filter by latest city'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['city'],
                            fn (Builder $query, $city): Builder => $query->whereHas('latestSiteAnalytics', function ($q) use ($city) {
                                $q->where('city', 'like', "%{$city}%");
                            })->orWhere('city', 'like', "%{$city}%"),
                        );
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Date Joined From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Date Joined Until'),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
