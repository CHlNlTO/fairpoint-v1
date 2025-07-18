<?php
// app/Filament/Admin/Resources/BusinessRegistrationResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BusinessRegistrationResource\Pages;
use App\Models\BusinessRegistration;
use App\Models\Business;
use App\Models\RegistrationType;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class BusinessRegistrationResource extends Resource
{
    protected static ?string $model = BusinessRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationGroup = 'Business Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Registration Information')
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
                                    ->inlineLabel(),

                                Select::make('registration_type_id')
                                    ->label('Registration Type')
                                    ->options(fn() => RegistrationType::where('is_active', true)->ordered()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select registration type')
                                    ->reactive()
                                    ->inlineLabel(),
                            ]),

                        Toggle::make('is_registered')
                            ->label('Is Registered')
                            ->helperText('Check if the business is registered with this agency')
                            ->inlineLabel(),

                        TextInput::make('registration_number')
                            ->label('Registration Number')
                            ->maxLength(255)
                            ->placeholder('Enter registration number')
                            ->inlineLabel(),

                        Grid::make(2)
                            ->schema([
                                DatePicker::make('registration_date')
                                    ->label('Registration Date')
                                    ->format('M d, Y')
                                    ->displayFormat('M d, Y')
                                    ->placeholder('Select registration date')
                                    ->inlineLabel(),

                                DatePicker::make('expiry_date')
                                    ->label('Expiry Date')
                                    ->format('M d, Y')
                                    ->displayFormat('M d, Y')
                                    ->placeholder('Select expiry date')
                                    ->visible(fn($get) => $get('registration_type_id') &&
                                        RegistrationType::find($get('registration_type_id'))?->requires_expiry)
                                    ->inlineLabel(),
                            ]),
                    ])
                    ->compact(),

                Section::make('Document Upload')
                    ->schema([
                        FileUpload::make('document_path')
                            ->label('Registration Document')
                            ->directory('business-registrations')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(10240)
                            ->downloadable()
                            ->openable()
                            ->placeholder('Upload registration document')
                            ->helperText('Maximum file size: 10MB. Accepted formats: PDF, JPG, PNG')
                            ->inlineLabel(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Enter any additional notes')
                            ->rows(3)
                            ->inlineLabel(),
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

                TextColumn::make('registrationType.name')
                    ->label('Registration Type')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                IconColumn::make('is_registered')
                    ->label('Registered')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('registration_number')
                    ->label('Registration #')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),

                TextColumn::make('registration_date')
                    ->label('Registration Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->placeholder('N/A'),

                TextColumn::make('expiry_date')
                    ->label('Expiry Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->placeholder('N/A')
                    ->color(
                        fn($record) =>
                        $record->expiry_date && $record->expiry_date->isPast() ? 'danger' : ($record->expiry_date && $record->expiry_date->isBetween(now(), now()->addDays(30)) ? 'warning' : null)
                    ),

                IconColumn::make('document_path')
                    ->label('Document')
                    ->icon(fn($state) => $state ? 'heroicon-o-document-check' : 'heroicon-o-document-minus')
                    ->color(fn($state) => $state ? 'success' : 'gray')
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

                SelectFilter::make('registration_type_id')
                    ->label('Registration Type')
                    ->options(fn() => RegistrationType::where('is_active', true)->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_registered')
                    ->label('Registration Status')
                    ->placeholder('All Registrations')
                    ->trueLabel('Registered')
                    ->falseLabel('Not Registered'),

                TernaryFilter::make('expired')
                    ->label('Expiry Status')
                    ->placeholder('All')
                    ->trueLabel('Expired')
                    ->falseLabel('Valid')
                    ->queries(
                        true: fn($query) => $query->expired(),
                        false: fn($query) => $query->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()),
                    ),
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
            'index' => Pages\ListBusinessRegistrations::route('/'),
            'create' => Pages\CreateBusinessRegistration::route('/create'),
            'edit' => Pages\EditBusinessRegistration::route('/{record}/edit'),
        ];
    }
}
