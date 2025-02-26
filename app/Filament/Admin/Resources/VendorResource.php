<?php
// app/Filament/Admin/Resources/VendorResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VendorResource\Pages;
use App\Models\Vendor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
// use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
// use Illuminate\Database\Eloquent\Builder;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Expenses';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Vendor Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter vendor name')
                                    ->autofocus(),

                                TextInput::make('tin')
                                    ->label('TIN')
                                    ->maxLength(255)
                                    ->placeholder('Enter tax identification number'),
                            ]),
                    ]),

                Section::make('Contact Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('contact_person')
                                    ->maxLength(255)
                                    ->placeholder('Enter contact person name'),

                                TextInput::make('contact_number')
                                    ->tel()
                                    ->maxLength(255)
                                    ->placeholder('Enter contact number'),

                                TextInput::make('email')
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('Enter email address'),

                                Textarea::make('address')
                                    ->placeholder('Enter vendor address'),
                            ]),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->placeholder('Enter additional notes')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tin')
                    ->label('TIN')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),

                TextColumn::make('contact_person')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),

                TextColumn::make('contact_number')
                    ->searchable()
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('email')
                    ->searchable()
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('address')
                    ->searchable()
                    ->placeholder('N/A')
                    ->toggleable()
                    ->limit(30),

                ToggleColumn::make('is_active')
                    ->label('Active')
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
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All Vendors')
                    ->trueLabel('Active Vendors')
                    ->falseLabel('Inactive Vendors'),
            ])
            ->defaultSort('name', 'asc')
            ->searchable();
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
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}
