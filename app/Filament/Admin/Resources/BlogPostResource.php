<?php
// app/Filament/Admin/Resources/BlogPostResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use App\Models\Tag;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Blog Post Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter blog post title')
                            ->inlineLabel()
                            ->autofocus()
                            ->columnSpan(1),

                        TextInput::make('author')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter author name')
                            ->inlineLabel()
                            ->columnSpan(1),

                        DatePicker::make('published_date')
                            ->required()
                            ->format('M d, Y')
                            ->placeholder('Select publication date')
                            ->inlineLabel()
                            ->default(now())
                            ->columnSpan(1),

                        MarkdownEditor::make('description')
                            ->required()
                            ->placeholder('Enter blog post description')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('blog-images')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpan(4),


                    ]),

                Section::make('Settings & Tags')
                    ->columns(2)
                    ->schema([
                        TextInput::make('order')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->placeholder('Enter display order')
                            ->inlineLabel()
                            ->columnSpan(1),

                        Toggle::make('active')
                            ->default(true)
                            ->inlineLabel()
                            ->columnSpan(1),

                        Select::make('tags')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter tag name'),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                return Tag::create($data)->getKey();
                            })
                            ->placeholder('Select or create tags')
                            ->inlineLabel()
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('author')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('published_date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('tags.name')
                    ->badge()
                    ->searchable()
                    ->toggleable()
                    ->placeholder('N/A'),

                TextColumn::make('order')
                    ->sortable()
                    ->toggleable(),

                ToggleColumn::make('active')
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
                TernaryFilter::make('active')
                    ->label('Status')
                    ->placeholder('All Posts')
                    ->trueLabel('Active Posts')
                    ->falseLabel('Inactive Posts'),

                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Filter::make('published_date')
                    ->form([
                        DatePicker::make('from')
                            ->label('Published From')
                            ->format('M d, Y')
                            ->placeholder('From date'),
                        DatePicker::make('until')
                            ->label('Published Until')
                            ->format('M d, Y')
                            ->placeholder('Until date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('published_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('published_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc');
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
