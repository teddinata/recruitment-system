<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Recruitment;
use App\Models\UserApplyJob;
use Filament\Tables\Columns;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RecruitmentResource\Pages;
use App\Filament\Resources\RecruitmentResource\RelationManagers;
use Filament\Forms\Components\{DateTimePicker, RichEditor, Table as TableComponent};
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitment;
use App\Filament\Resources\RecruitmentResource\RelationManagers\InterviewsRelationManager;
use Filament\Forms\Components\{Card, FileUpload, TextInput, Textarea, DatePicker, Group, Radio, Select};
use Filament\Forms\Components\Get;


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
                            ->label('Upload CV')
                            ->acceptedFileTypes(['application/pdf'])
                            ->previewable(true)
                            ->rules(['nullable', 'mimes:pdf', 'max:2048'])
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\Select::make('job_vacancy_id')
                            ->label('Job Vacancy')
                            ->relationship('jobVacancy', 'title')
                            ->required()
                            ->placeholder('Select job vacancy')
                            ->rules(['required'])
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->placeholder('Enter first name')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->placeholder('Enter last name')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->placeholder('Enter email')
                            ->rules(['required', 'email', 'max:255'])
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->required()
                            ->placeholder('Enter phone number')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\TextInput::make('address')
                            ->label('Address')
                            ->required()
                            ->placeholder('Enter address')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\TextInput::make('place_of_birth')
                            ->label('Place of Birth')
                            ->required()
                            ->placeholder('Enter place of birth')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->required()
                            ->placeholder('Enter date of birth')
                            ->rules(['required'])
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\Select::make('education')
                            ->label('Last Education')
                            ->required()
                            ->placeholder('Enter education')
                            ->options([
                                'SMA/SMK' => 'SMA/SMK',
                                'D3' => 'D3',
                                'S1' => 'S1',
                                'S2' => 'S2',
                            ])
                            ->native(false)
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\TextInput::make('major')
                            ->label('Major')
                            ->required()
                            ->placeholder('Enter major')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\Select::make('join_date')
                            ->label('Join Date')
                            ->required()
                            ->placeholder('Enter join date')
                            ->options(function () {
                                $dates = [];
                                // $currentDate = date('Y');
                                for ($i = 1; $i <= 31; $i++) {
                                    // $dates[$currentDate - $i] = $currentDate - $i;
                                    $dates[$i] = $i . " Hari";
                                }
                                return $dates;
                            })
                            ->native(false)
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\TextInput::make('linkedin_url')
                            ->label('Linkedin')
                            ->required()
                            ->placeholder('Enter linkedin')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\Select::make('job_source')
                            ->label('Job Source')
                            ->required()
                            ->options([
                                'Instagram' => 'Instagram',
                                'Facebook' => 'Facebook',
                                'Twitter' => 'Twitter',
                                'Linkedin' => 'Linkedin',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->native(false)
                            ->placeholder('Where did you find this job?')
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\TextInput::make('old_company')
                            ->label('Old Company')
                            ->required()
                            ->placeholder('Enter old company')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitment::class]),
                        Forms\Components\RichEditor::make('self_description')
                            ->label('Self Description')
                            ->required()
                            ->placeholder('Enter self description')
                            ->rules(['required'])
                            ->disabledOn([Pages\EditRecruitment::class]),
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
                    ->visible(fn ($record) => ($record->is_valid != "yes"))
                    ->hiddenOn([Pages\CreateRecruitment::class]),
                Section::make('Undangan Wawancara')
                    ->schema([
                        Radio::make('is_invited')
                            ->label("Apakah Undang Wawancara?")
                            ->required()
                            ->options([
                                'yes' => 'Ya',
                                'no' => 'Tidak',
                            ])
                            ->reactive()
                            ->inline()
                            ->inlineLabel(false),

                        DateTimePicker::make('interview_date')
                            ->label('Tanggal Wawancara')
                            ->native(false)
                            // ->required()
                            ->hoursStep(2)
                            ->minutesStep(15)
                            ->secondsStep(10),
                            // visible only if is_invited is true
                            // ->visible(fn ($get) => $get('is_invited') === true),


                        TextInput::make('google_meet_link')
                            ->url()
                            ->label('Link Concall')
                            // ->required()
                            ->placeholder('Enter old company')
                            ->rules(['max:255'])
                            // visible only if is_invited is true
                            ,

                        Textarea::make('notes')
                            ->label('Keterangan')
                            ->rows(10)
                            ->cols(20)
                            // visible only if is_invited is true

                    ])


                    // ->visible(fn ($record) => $record->is_valid == "yes")
                    ->visible(function ($record) {
                        if ($record->interview) {
                            return ($record->is_valid == "yes" && $record->interview->is_invited == null);
                        } else {
                            return ($record->is_valid == "yes");
                        };
                    })
                    ->hiddenOn([Pages\CreateRecruitment::class]),
                Section::make('Input Hasil Wawancara')
                    ->schema([
                        Radio::make('is_success')
                            ->label("Apakah Lulus?")
                            ->required()
                            ->boolean()
                            ->inline()
                            ->inlineLabel(false),
                        Textarea::make('review')
                            ->label('Keterangan Hasil Wawancara')
                            ->rows(10)
                            ->cols(20)
                    ])
                    ->visible(function ($record) {
                        if ($record->interview) {
                            return ($record->interview->is_invited == "yes");
                        } else {
                            return false;
                        };
                    })
                    ->hiddenOn([Pages\CreateRecruitment::class]),
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
                Tables\Columns\TextColumn::make('interviewResult.is_success')
                    ->label('Lulus Wawancara')
                    ->formatStateUsing(fn ($state) => $state === 'yes' ? 'Ya' : 'Tidak')
                    // ->formatStateUsing(fn ($state) => dd($state))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Review')
                    ->icon('heroicon-s-eye')
                    ->url(fn (Model $record) => EditRecruitment::getUrl([$record->id]))
                    ->visible(fn ($record) => ($record->is_valid != "yes")),
                Action::make('Undang Wawancara')
                    ->icon('heroicon-s-phone')
                    ->url(fn (Model $record) => EditRecruitment::getUrl([$record->id]))
                    ->visible(function ($record) {
                        if ($record->interview) {
                            return ($record->is_valid == "yes" && $record->interview->is_invited == null);
                        } else {
                            return ($record->is_valid == "yes");
                        };
                    }),
                Action::make('Input Hasil Wawancara')
                    ->icon('heroicon-s-clipboard-document')
                    ->url(fn (Model $record) => EditRecruitment::getUrl([$record->id]))
                    ->visible(function ($record) {
                        if ($record->interview) {
                            return ($record->interview->is_invited == "yes");
                        } else {
                            return false;
                        };
                    }),
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
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
