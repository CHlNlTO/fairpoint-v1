<?php
// app/Filament/Admin/Resources/BusinessUserResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BusinessUserResource\Pages;
use App\Models\BusinessUser;
use App\Models\Business;
use App\Models\User;
use App\Models\BusinessUserStatus;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class BusinessUserResource extends Resource
{
    protected static ?string $model = BusinessUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Business Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Business User Assignment')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('business_id')
                                    ->label('Business')
                                    ->options(fn() => Business::with(['businessType', 'industry'])
                                        ->get()
                                        ->mapWithKeys(fn($business) => [
                                            $business->id => "{$business->name} ({$business->businessType?->name} - {$business->industry?->name})"
                                        ]))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select business')
                                    ->inlineLabel(),

                                Select::make('user_id')
                                    ->label('User')
                                    ->options(fn() => User::pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select user')
                                    ->inlineLabel(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('status_id')
                                    ->label('Status')
                                    ->options(fn() => BusinessUserStatus::where('is_active', true)->ordered()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select status')
                                    ->default(fn() => BusinessUserStatus::where('code', 'ACTIVE')->first()?->id)
                                    ->inlineLabel(),

                                DateTimePicker::make('joined_at')
                                    ->label('Joined Date')
                                    ->default(now())
                                    ->format('M d, Y H:i')
                                    ->displayFormat('M d, Y H:i')
                                    ->placeholder('Select joined date')
                                    ->inlineLabel(),
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

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status.name')
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn($record) => $record->status?->color?->class_name ?? 'gray'),

                TextColumn::make('joined_at')
                    ->label('Joined Date')
                    ->dateTime('M d, Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('business_id')
                    ->label('Business')
                    ->options(fn() => Business::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status_id')
                    ->label('Status')
                    ->options(fn() => BusinessUserStatus::where('is_active', true)->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('joined_at', 'desc');
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
            'index' => Pages\ListBusinessUsers::route('/'),
            'create' => Pages\CreateBusinessUser::route('/create'),
            'edit' => Pages\EditBusinessUser::route('/{record}/edit'),
        ];
    }
}
