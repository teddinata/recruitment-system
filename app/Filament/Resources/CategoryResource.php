<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Section;



class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Tambah Kategori')
                    ->description('Silahkan isi form berikut untuk menambahkan kategori.')
                    ->schema([
                        // image
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->avatar()
                            ->previewable(true)
                            ->imageEditor()
                            ->imageEditorViewportWidth('1920')
                            ->imageEditorViewportHeight('1080')
                            ->maxWidth('w-80')
                            ->rules(['nullable', 'image', 'max:1024']),
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->placeholder('Enter name')
                            ->rules(['required', 'max:255']),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateActions([
                Tables\Actions\CreateAction::make('create')
                    ->label(app()->getLocale() == 'id' ? 'Tambah Kategori' : 'Create Category')
                    ->icon('heroicon-o-academic-cap')
                    ->button(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(app()->getLocale() == 'id' ? 'Kategori' : 'Categories')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }



    // protected static ?string $slug = 'data-master/categories';

    //     protected static ?string $navigationGroup = 'Master Data';
    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            $result = 'Kategori';
        } else {
            $result = 'Categories';
        }
        return $result;
    }
    public static function getNavigationGroup(): ?string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            $result = 'Data Master';
        } else {
            $result = 'Master Data';
        }
        return $result;
    }
    public static function getSlug(): string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            $result = 'master-data/Kategori';
        } else {
            $result = 'data-master/categories';
        }
        return $result;
    }
}
