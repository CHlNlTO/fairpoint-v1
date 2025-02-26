<?php
// app/Filament/Admin/Resources/JournalEntryResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\JournalEntryResource\Pages;
use App\Models\JournalEntry;
use App\Models\ChartOfAccount;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class JournalEntryResource extends Resource
{
    protected static ?string $model = JournalEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Accounting';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'journal_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Journal Entry Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('journal_number')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter journal number')
                                    ->unique(ignoreRecord: true)
                                    ->autofocus(),

                                DatePicker::make('transaction_date')
                                    ->required()
                                    ->format('M d, Y')
                                    ->default(now())
                                    ->placeholder('Select transaction date'),
                            ]),
                    ]),

                Section::make('Entry Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->maxLength(255)
                                    ->placeholder('Enter entry name/title'),

                                Select::make('account_id')
                                    ->label('Account')
                                    ->options(fn() => ChartOfAccount::where('is_active', true)->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select account'),
                            ]),

                        Grid::make(1)
                            ->schema([
                                TextInput::make('amount')
                                    ->required()
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('Enter amount')
                                    ->prefix('₱'),

                                Textarea::make('particulars')
                                    ->required()
                                    ->placeholder('Enter journal entry particulars'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('journal_number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('transaction_date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),

                TextColumn::make('account.name')
                    ->label('Account')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('particulars')
                    ->limit(30)
                    ->searchable()
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
                SelectFilter::make('account_id')
                    ->label('Account')
                    ->options(fn() => ChartOfAccount::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')
                            ->label('From Date')
                            ->format('M d, Y')
                            ->placeholder('From date'),
                        DatePicker::make('until')
                            ->label('Until Date')
                            ->format('M d, Y')
                            ->placeholder('Until date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    }),
            ])
            ->defaultSort('transaction_date', 'desc')
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
            'index' => Pages\ListJournalEntries::route('/'),
            'create' => Pages\CreateJournalEntry::route('/create'),
            'edit' => Pages\EditJournalEntry::route('/{record}/edit'),
        ];
    }
}
