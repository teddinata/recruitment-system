<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobVacancyResource\Pages;
use App\Filament\Resources\JobVacancyResource\RelationManagers;
use App\Models\JobVacancy;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class JobVacancyResource extends Resource
{
    protected static ?string $model = JobVacancy::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Master Data';

    protected static bool $softDelete = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Tambah Lowongan Kerja')
                    ->description('Silahkan isi form berikut untuk menambahkan lowongan kerja.')
                    ->schema([
                        // category id
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->required()
                            ->placeholder('Select category')
                            ->rules(['required']),
                        Forms\Components\FileUpload::make('image')
                        ->image()
                        ->avatar()
                        ->previewable(true)
                        ->imageEditor()
                        ->imageEditorViewportWidth('1920')
                        ->imageEditorViewportHeight('1080')
                        ->maxWidth('w-80')
                        ->rules(['nullable', 'image', 'max:1024']),
                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->placeholder('Enter title')
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                if (($get('slug') ?? '') !== Str::slug($old)) {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            })
                            ->rules(['required', 'max:255']),
                        // slug auto generate from title
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->disabled()
                            ->placeholder('Enter slug'),
                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->required()
                            ->placeholder('Enter description')
                            ->rules(['required']),
                        Forms\Components\TextInput::make('work_hours')
                            ->label('Work Hours')
                            ->required()
                            ->placeholder('Enter work hours')
                            ->rules(['required', 'max:255']),
                        Forms\Components\TextInput::make('location')
                            ->label('Location')
                            ->required()
                            ->placeholder('Enter location')
                            ->rules(['required', 'max:255']),
                        Forms\Components\RichEditor::make('qualifications')
                            ->label('Qualifications')
                            ->required()
                            ->placeholder('Enter qualifications')
                            ->rules(['required']),
                        Forms\Components\Datepicker::make('valid_until')
                            ->label('Valid Until')
                            ->required()
                            ->rules(['required']),
                        Forms\Components\Select::make('experience')
                        ->label('Experience')
                        ->options([
                            'junior' => 'Junior',
                            'intermediate' => 'Intermediate',
                            'senior' => 'Senior',
                        ])
                        ->placeholder('Select experience'),
                        Forms\Components\Toggle::make('remote')
                            ->label('Remote')
                            ->default(false),
                        Forms\Components\Toggle::make('enable')
                            ->label('Enable')
                            ->default(true),
                    ])
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateActions([
                Tables\Actions\CreateAction::make('create')
                    ->label('Tambah Lowongan Kerja')
                    ->icon('heroicon-o-briefcase')
                    ->url('/admin/job-vacancies/create')
                    ->button(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50) // Batasi 50 karakter
                    ->label('Description')
                    ->html(),
                Tables\Columns\TextColumn::make('qualifications')
                    ->label('Qualifications')
                    ->limit(50) // Batasi 50 karakter
                    ->html(),
                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Valid Until')
                    ->date()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('enable')
                    ->label('Enable')
                    ->sortable(),
            ])
            ->filters([
                // filter by title
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators[] = 'Created from ' . Carbon::parse($data['created_from'])->format('M j, Y');
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators[] = 'Created until ' . Carbon::parse($data['created_until'])->format('M j, Y');
                        }

                        return $indicators;
                    }),

            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
            // ->paginated([10, 25, 50, 100, 'all']);
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
            'index' => Pages\ListJobVacancies::route('/'),
            'create' => Pages\CreateJobVacancy::route('/create'),
            'edit' => Pages\EditJobVacancy::route('/{record}/edit'),
        ];
    }
}
