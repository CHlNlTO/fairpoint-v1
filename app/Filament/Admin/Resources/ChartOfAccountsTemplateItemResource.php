<?php
// app/Filament/Admin/Resources/ChartOfAccountsTemplateItemResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ChartOfAccountsTemplateItemResource\Pages;
use App\Models\ChartOfAccountsTemplateItem;
use App\Models\ChartOfAccountsTemplate;
use App\Models\AccountType;
use App\Models\SubAccountType;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class ChartOfAccountsTemplateItemResource extends Resource
{
    protected static ?string $model = ChartOfAccountsTemplateItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Chart of Accounts Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Template Item Information')
                    ->schema([
                        Select::make('template_id')
                            ->label('Template')
                            ->options(fn() => ChartOfAccountsTemplate::with(['industry', 'businessStructure'])
                                ->where('is_active', true)
                                ->get()
                                ->mapWithKeys(fn($template) => [
                                    $template->id => "{$template->name} ({$template->industry->name} - {$template->businessStructure->name})"
                                ]))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Select template')
                            ->inlineLabel(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('code')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter account code (e.g., 1000)')
                                    ->inlineLabel(),

                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter account name')
                                    ->inlineLabel(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('account_type_id')
                                    ->label('Account Type')
                                    ->options(fn() => AccountType::where('is_active', true)->ordered()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select account type')
                                    ->reactive()
                                    ->inlineLabel(),

                                Select::make('sub_account_type_id')
                                    ->label('Sub-Account Type')
                                    ->options(function (Get $get) {
                                        $accountTypeId = $get('account_type_id');
                                        if (!$accountTypeId) {
                                            return [];
                                        }
                                        return SubAccountType::where('account_type_id', $accountTypeId)
                                            ->where('is_active', true)
                                            ->ordered()
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select sub-account type')
                                    ->inlineLabel(),
                            ]),

                        Textarea::make('description')
                            ->placeholder('Enter account description')
                            ->rows(3)
                            ->inlineLabel(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->placeholder('Enter sort order')
                                    ->inlineLabel(),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
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
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('template.name')
                    ->label('Template')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('accountType.name')
                    ->label('Account Type')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Asset' => 'success',
                        'Liability' => 'warning',
                        'Equity' => 'info',
                        'Revenue' => 'success',
                        'Expense' => 'danger',
                        'COGS' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('subAccountType.name')
                    ->label('Sub-Account Type')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('description')
                    ->limit(30)
                    ->placeholder('N/A')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(),

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
                SelectFilter::make('template_id')
                    ->label('Template')
                    ->options(fn() => ChartOfAccountsTemplate::with(['industry', 'businessStructure'])
                        ->where('is_active', true)
                        ->get()
                        ->mapWithKeys(fn($template) => [
                            $template->id => "{$template->name} ({$template->industry->name} - {$template->businessStructure->name})"
                        ]))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('account_type_id')
                    ->label('Account Type')
                    ->options(fn() => AccountType::where('is_active', true)->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All Items')
                    ->trueLabel('Active Items')
                    ->falseLabel('Inactive Items'),
            ])
            ->defaultSort('template_id', 'asc')
            ->reorderable('sort_order');
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
            'index' => Pages\ListChartOfAccountsTemplateItems::route('/'),
            'create' => Pages\CreateChartOfAccountsTemplateItem::route('/create'),
            'edit' => Pages\EditChartOfAccountsTemplateItem::route('/{record}/edit'),
        ];
    }
}
