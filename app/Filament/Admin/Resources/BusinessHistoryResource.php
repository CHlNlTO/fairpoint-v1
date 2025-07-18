<?php
// app/Filament/Admin/Resources/BusinessHistoryResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BusinessHistoryResource\Pages;
use App\Models\BusinessHistory;
use App\Models\Business;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class BusinessHistoryResource extends Resource
{
    protected static ?string $model = BusinessHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Business Management';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'field_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('History Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('business_id')
                                    ->label('Business')
                                    ->options(fn() => Business::pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select business')
                                    ->inlineLabel()
                                    ->disabled(),

                                Select::make('changed_by')
                                    ->label('Changed By')
                                    ->options(fn() => User::pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select user')
                                    ->inlineLabel()
                                    ->disabled(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('model_type')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Model class name')
                                    ->inlineLabel()
                                    ->disabled(),

                                TextInput::make('model_id')
                                    ->required()
                                    ->numeric()
                                    ->placeholder('Model ID')
                                    ->inlineLabel()
                                    ->disabled(),

                                TextInput::make('field_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Field name')
                                    ->inlineLabel()
                                    ->disabled(),
                            ]),
                    ])
                    ->compact(),

                Section::make('Change Details')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Textarea::make('old_value')
                                    ->label('Old Value')
                                    ->placeholder('Previous value')
                                    ->rows(3)
                                    ->inlineLabel()
                                    ->disabled(),

                                Textarea::make('new_value')
                                    ->label('New Value')
                                    ->placeholder('New value')
                                    ->rows(3)
                                    ->inlineLabel()
                                    ->disabled(),
                            ]),
                    ])
                    ->compact(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('business.name')
                    ->label('Business')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('model_type')
                    ->label('Model')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => class_basename($state))
                    ->badge()
                    ->color('primary'),

                TextColumn::make('field_name')
                    ->label('Field')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('old_value')
                    ->label('Old Value')
                    ->limit(30)
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('new_value')
                    ->label('New Value')
                    ->limit(30)
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('changedBy.name')
                    ->label('Changed By')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Changed At')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('business_id')
                    ->label('Business')
                    ->options(fn() => Business::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('model_type')
                    ->label('Model Type')
                    ->options(function () {
                        return BusinessHistory::query()
                            ->distinct()
                            ->pluck('model_type')
                            ->mapWithKeys(fn($type) => [$type => class_basename($type)])
                            ->toArray();
                    }),

                SelectFilter::make('changed_by')
                    ->label('Changed By')
                    ->options(fn() => User::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')
                            ->label('From Date')
                            ->placeholder('From date'),
                        DatePicker::make('until')
                            ->label('Until Date')
                            ->placeholder('Until date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
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
            'index' => Pages\ListBusinessHistories::route('/'),
            'create' => Pages\CreateBusinessHistory::route('/create'),
            'edit' => Pages\EditBusinessHistory::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // History records should not be manually created
    }
}
