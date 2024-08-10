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

    protected static ?int $navigationSort = 2;

    protected static bool $softDelete = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(app()->getLocale() == 'id' ? 'Tambah Lowongan Kerja' : 'Add Job Vacancy')
                    ->description(app()->getLocale() == 'id' ? 'Silahkan isi form berikut untuk menambahkan lowongan kerja.' : 'Please fill in the following form to add job vacancies')
                    ->schema([
                        // category id
                        Forms\Components\Select::make('category_id')
                            ->label(app()->getLocale() == 'id' ? 'Kategori' : 'Category')
                            ->relationship('category', 'name')
                            ->required()
                            ->placeholder(app()->getLocale() == 'id' ? 'Pilih Kategori' : 'Select category')
                            ->rules(['required']),
                        Forms\Components\FileUpload::make('image')
                            ->label(app()->getLocale() == 'id' ? 'Gambar' : 'Image')
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
                            ->disabled(),
                        Forms\Components\RichEditor::make('description')
                            ->label(app()->getLocale() == 'id' ? 'deskripsi' : 'Description')
                            ->required()
                            ->placeholder(app()->getLocale() == 'id' ? 'Masukkan Deskripsi' : 'Enter Description')
                            ->rules(['required']),
                        Forms\Components\TextInput::make('work_hours')
                            ->label(app()->getLocale() == 'id' ? 'Jam Kerja' : 'Work Hours')
                            ->required()
                            ->placeholder(app()->getLocale() == 'id' ? 'Masukkan Jam Kerja' : 'Enter Work Hours')
                            ->rules(['required', 'max:255']),
                        Forms\Components\TextInput::make('location')
                            ->label(app()->getLocale() == 'id' ? 'Alamat Pekerjaan' : 'Location')
                            ->required()
                            ->placeholder(app()->getLocale() == 'id' ? 'Alamat Pekerjaan' : 'Enter Location')
                            ->rules(['required', 'max:255']),
                        Forms\Components\RichEditor::make('qualifications')
                            ->label(app()->getLocale() == 'id' ? 'Kualifikasi :' : 'Qualifications :')
                            ->required()
                            ->placeholder(app()->getLocale() == 'id' ? 'Masukkan Kualifikasi :' : 'Enter Qualifications :')
                            ->rules(['required']),
                        Datepicker::make('valid_until')
                            ->label(app()->getLocale() == 'id' ? 'Berlaku Sampai' : 'Valid Until')
                            ->required()
                            ->rules(['required']),
                        // Forms\Components\Datepicker::make('valid_until')
                        //     ->label('Valid Until')
                        //     ->required()
                        //     ->rules(['required']),
                        Forms\Components\Select::make('experience')
                            ->label(app()->getLocale() == 'id' ? 'Pengalaman' : 'Experience')
                            ->options([
                                'junior' => 'Junior',
                                'intermediate' => 'Intermediate',
                                'senior' => 'Senior',
                            ])
                            ->placeholder(app()->getLocale() == 'id' ? 'Pilih Pengalaman' : 'Select experience'),
                        Forms\Components\Toggle::make('remote')
                            ->label('Remote')
                            ->default(false),
                        Forms\Components\Toggle::make('enable')
                            ->label(app()->getLocale() == 'id' ? 'Mengizinkan' : 'Enable')
                            ->default(true),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateActions([
                Tables\Actions\CreateAction::make('create')
                    ->label(app()->getLocale() == 'id' ? 'Tambah Lowongan Kerja' : 'Add Job Vacancy')
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
                    ->label(app()->getLocale() == 'id' ? 'Deskripsi' : 'Description')
                    ->limit(50) // Batasi 50 karakter
                    ->html(),
                Tables\Columns\TextColumn::make('qualifications')
                    ->label(app()->getLocale() == 'id' ? 'Kualifikasi' : 'Qualifications')
                    ->limit(50) // Batasi 50 karakter
                    ->html(),
                Tables\Columns\TextColumn::make('valid_until')
                    ->label(app()->getLocale() == 'id' ? 'Berlaku Sampai' : 'Valid Until')
                    ->date()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('enable')
                    ->label(app()->getLocale() == 'id' ? 'Mengizinkan' : 'Enable')
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
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            $result = 'Loker';
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
            $result = 'master-data/loker';
        } else {
            $result = 'data-master/job-vacancies';
        }
        return $result;
    }
}
