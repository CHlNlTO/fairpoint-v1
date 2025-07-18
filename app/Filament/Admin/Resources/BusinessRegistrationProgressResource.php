<?php
// app/Filament/Admin/Resources/BusinessRegistrationProgressResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BusinessRegistrationProgressResource\Pages;
use App\Models\BusinessRegistrationProgress;
use App\Models\Business;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class BusinessRegistrationProgressResource extends Resource
{
    protected static ?string $model = BusinessRegistrationProgress::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Business Management';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'step_code';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Progress Information')
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
                                    ->disabled(fn(?BusinessRegistrationProgress $record) => $record !== null),

                                Select::make('step_code')
                                    ->label('Registration Step')
                                    ->options([
                                        'basic_info' => 'Basic Business Information',
                                        'address' => 'Business Address',
                                        'tax_info' => 'Tax Information',
                                        'registrations' => 'Government Registrations',
                                        'fiscal_year' => 'Fiscal Year Setup',
                                        'chart_of_accounts' => 'Chart of Accounts',
                                    ])
                                    ->required()
                                    ->placeholder('Select registration step')
                                    ->inlineLabel()
                                    ->disabled(fn(?BusinessRegistrationProgress $record) => $record !== null),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_completed')
                                    ->label('Completed')
                                    ->inlineLabel(),

                                DateTimePicker::make('completed_at')
                                    ->label('Completed At')
                                    ->placeholder('Select completion date')
                                    ->inlineLabel()
                                    ->disabled(),
                            ]),
                    ])
                    ->compact(),

                Section::make('Step Data')
                    ->schema([
                        KeyValue::make('data')
                            ->label('Progress Data')
                            ->addButtonLabel('Add Data')
                            ->keyLabel('Field')
                            ->valueLabel('Value')
                            ->inlineLabel(),
                    ])
                    ->compact()
                    ->collapsible(),
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

                TextColumn::make('step_code')
                    ->label('Step')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'basic_info' => 'Basic Info',
                        'address' => 'Address',
                        'tax_info' => 'Tax Info',
                        'registrations' => 'Registrations',
                        'fiscal_year' => 'Fiscal Year',
                        'chart_of_accounts' => 'Chart of Accounts',
                        default => $state,
                    }),

                IconColumn::make('is_completed')
                    ->label('Completed')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->placeholder('Not completed'),

                TextColumn::make('data')
                    ->label('Data Fields')
                    ->formatStateUsing(fn($state) => is_array($state) ? count($state) . ' fields' : '0 fields')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

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

                SelectFilter::make('step_code')
                    ->label('Step')
                    ->options([
                        'basic_info' => 'Basic Business Information',
                        'address' => 'Business Address',
                        'tax_info' => 'Tax Information',
                        'registrations' => 'Government Registrations',
                        'fiscal_year' => 'Fiscal Year Setup',
                        'chart_of_accounts' => 'Chart of Accounts',
                    ]),

                TernaryFilter::make('is_completed')
                    ->label('Completion Status')
                    ->placeholder('All Steps')
                    ->trueLabel('Completed Steps')
                    ->falseLabel('Incomplete Steps'),
            ])
            ->defaultSort('business_id', 'asc');
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
            'index' => Pages\ListBusinessRegistrationProgress::route('/'),
            'create' => Pages\CreateBusinessRegistrationProgress::route('/create'),
            'edit' => Pages\EditBusinessRegistrationProgress::route('/{record}/edit'),
        ];
    }
}
