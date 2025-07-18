<?php
// app/Filament/Admin/Resources/ChartOfAccountsTemplateHistoryResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ChartOfAccountsTemplateHistoryResource\Pages;
use App\Models\ChartOfAccountsTemplateHistory;
use App\Models\ChartOfAccountsTemplate;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class ChartOfAccountsTemplateHistoryResource extends Resource
{
    protected static ?string $model = ChartOfAccountsTemplateHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Business Configuration';

    protected static ?int $navigationSort = 8;

    protected static ?string $recordTitleAttribute = 'version';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Version Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('template_id')
                                    ->label('Template')
                                    ->options(fn() => ChartOfAccountsTemplate::pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select template')
                                    ->inlineLabel()
                                    ->disabled(),

                                TextInput::make('version')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Version number')
                                    ->inlineLabel()
                                    ->disabled(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('changed_by')
                                    ->label('Changed By')
                                    ->options(fn() => User::pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select user')
                                    ->inlineLabel()
                                    ->disabled(),

                                Textarea::make('change_description')
                                    ->label('Change Description')
                                    ->placeholder('Description of changes')
                                    ->rows(3)
                                    ->inlineLabel()
                                    ->disabled(),
                            ]),
                    ])
                    ->compact(),

                Section::make('Template Data')
                    ->schema([
                        KeyValue::make('template_data')
                            ->label('Template Snapshot')
                            ->keyLabel('Field')
                            ->valueLabel('Value')
                            ->inlineLabel()
                            ->disabled(),
                    ])
                    ->compact()
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('template.name')
                    ->label('Template')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('version')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('change_description')
                    ->label('Description')
                    ->limit(50)
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('changedBy.name')
                    ->label('Changed By')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('template_data')
                    ->label('Items Count')
                    ->formatStateUsing(function ($state) {
                        if (!is_array($state) || !isset($state['items'])) {
                            return '0 items';
                        }
                        return count($state['items']) . ' items';
                    })
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('template_id')
                    ->label('Template')
                    ->options(fn() => ChartOfAccountsTemplate::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

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
            'index' => Pages\ListChartOfAccountsTemplateHistories::route('/'),
            'create' => Pages\CreateChartOfAccountsTemplateHistory::route('/create'),
            'edit' => Pages\EditChartOfAccountsTemplateHistory::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // History records should not be manually created
    }
}
