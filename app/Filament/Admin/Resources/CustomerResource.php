<?php
// app/Filament/Admin/Resources/CustomerResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CustomerResource\Pages;
use App\Models\Customer;
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

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Customer Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter customer name')
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
                                    ->placeholder('Enter customer address'),
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
                    ->placeholder('All Customers')
                    ->trueLabel('Active Customers')
                    ->falseLabel('Inactive Customers'),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
