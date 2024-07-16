<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecruitmentResource\Pages;
use App\Filament\Resources\RecruitmentResource\RelationManagers;
use App\Models\Recruitment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\UserApplyJob;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\{Card, FileUpload, TextInput, Textarea, DatePicker, Radio, Select};
use Filament\Forms\Components\{RichEditor, Table as TableComponent};
use App\Filament\Resources\RecruitmentResource\RelationManagers\InterviewsRelationManager;



class RecruitmentResource extends Resource
{
    protected static ?string $model = UserApplyJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Sedang Berlangsung';

    protected static ?string $navigationLabel = 'Recruitment';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Tambah Pendaftar')
                    ->description('Silahkan isi form berikut untuk menambahkan pendaftar.')
                    ->schema([
                        Forms\Components\FileUpload::make('cv_path')
                            ->label('PDF')
                            ->acceptedFileTypes(['application/pdf'])
                            ->previewable(true)
                            ->rules(['nullable', 'mimes:pdf', 'max:2048'])
                            ->disabled(),
                        Forms\Components\Select::make('job_vacancy_id')
                            ->label('Job Vacancy')
                            ->relationship('jobVacancy', 'title')
                            ->required()
                            ->placeholder('Select job vacancy')
                            ->rules(['required'])
                            ->disabled(),
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->placeholder('Enter first name')
                            ->rules(['required', 'max:255'])
                            ->disabled(),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->placeholder('Enter last name')
                            ->rules(['required', 'max:255'])
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->placeholder('Enter email')
                            ->rules(['required', 'email', 'max:255'])
                            ->disabled(),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->required()
                            ->placeholder('Enter phone number')
                            ->rules(['required', 'max:255'])
                            ->disabled(),
                        Forms\Components\TextInput::make('address')
                            ->label('Address')
                            ->required()
                            ->placeholder('Enter address')
                            ->rules(['required', 'max:255'])
                            ->disabled(),
                        Forms\Components\TextInput::make('place_of_birth')
                            ->label('Place of Birth')
                            ->required()
                            ->placeholder('Enter place of birth')
                            ->rules(['required', 'max:255'])
                            ->disabled(),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->required()
                            ->placeholder('Enter date of birth')
                            ->rules(['required'])
                            ->disabled(),
                        Forms\Components\TextInput::make('education')
                            ->label('Education')
                            ->required()
                            ->placeholder('Enter education')
                            ->rules(['required', 'max:255'])
                            ->disabled(),
                        Forms\Components\TextInput::make('major')
                            ->label('Major')
                            ->required()
                            ->placeholder('Enter major')
                            ->rules(['required', 'max:255'])
                            ->disabled(),
                        Forms\Components\TextInput::make('join_date')
                            ->label('Join Date')
                            ->required()
                            ->placeholder('Enter join date')
                            ->rules(['required'])
                            ->disabled(),
                        Forms\Components\TextInput::make('linkedin_url')
                            ->label('Linkedin')
                            ->required()
                            ->placeholder('Enter linkedin')
                            ->rules(['required', 'max:255'])
                            ->disabled(),
                        Forms\Components\TextInput::make('job_source')
                            ->label('Job Source')
                            ->required()
                            ->placeholder('Enter job source')
                            ->rules(['required', 'max:255'])
                            ->disabled(),
                        Forms\Components\TextInput::make('old_company')
                            ->label('Old Company')
                            ->required()
                            ->placeholder('Enter old company')
                            ->rules(['required', 'max:255'])
                            ->disabled(),
                        Forms\Components\RichEditor::make('self_description')
                            ->label('Self Description')
                            ->required()
                            ->placeholder('Enter self description')
                            ->rules(['required'])
                            ->disabled(),
                    ]),
                Section::make('Validasi Data')
                    ->description('Validasi data kandidat.')
                    ->schema([
                        Forms\Components\Select::make('is_valid')
                            ->label('Apakah data valid?')
                            ->options([
                                'yes' => 'Ya',
                                'no' => 'Tidak',
                            ])
                            ->reactive()
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cv_path')
                    ->label('PDF')
                    ->formatStateUsing(function ($state) {
                        return '<a href="' . asset('storage/' . $state) . '" target="_blank">Lihat CV</a>';
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->getStateUsing(function ($record) {
                        return $record->first_name . ' ' . $record->last_name;
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('No HP')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('place_of_birth')
                    ->label('Tempat, Tanggal Lahir')
                    ->getStateUsing(function ($record) {
                        return $record->place_of_birth . ', ' . $record->date_of_birth;
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('education')
                    ->label('Pendidikan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('old_company')
                    ->label('Perusahaan Terakhir')
                    ->searchable()
                    ->sortable()
                    ->default('-'),
                Tables\Columns\TextColumn::make('is_valid')
                    ->label('Valid')
                    ->formatStateUsing(fn ($state) => $state === 'yes' ? 'Ya' : 'Tidak')
                    ->sortable(),
                Tables\Columns\TextColumn::make('interview.is_invited')
                    ->label('Diundang Wawancara')
                    ->formatStateUsing(fn ($state) => $state === 'yes' ? 'Ya' : 'Tidak')
                    ->sortable(),
                Tables\Columns\TextColumn::make('interview_result.is_success')
                    ->label('Lulus Wawancara')
                    ->formatStateUsing(fn ($state) => $state === 'yes' ? 'Ya' : 'Tidak')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            InterviewsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecruitments::route('/'),
            'create' => Pages\CreateRecruitment::route('/create'),
            'edit' => Pages\EditRecruitment::route('/{record}/edit'),
        ];
    }
}
