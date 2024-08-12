<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\InterviewType;
use App\Models\UserApplyJob;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\Filament\Resources\RecruitmentResource\Pages;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitment;
use App\Filament\Resources\RecruitmentResource\Pages\ViewRecruitment;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentValid;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentInterview;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentInterviewResult;
use App\Filament\Resources\RecruitmentResource\RelationManagers\InterviewRelationManager;
use Filament\Forms\Components\{DateTimePicker, Repeater, RichEditor, Table as TableComponent};
use Filament\Forms\Components\{Card, FileUpload, TextInput, Textarea, DatePicker, Group, Radio, Select};

class RecruitmentResource extends Resource
{
    protected static ?string $model = UserApplyJob::class;



    // protected static ?string $label = 'Recruitments';

    // protected static ?string $navigationGroup = "Ongoing";

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(app()->getLocale() == 'id' ? 'Tambah Pelamar' : 'Add Candidate')
                    ->description(app()->getLocale() == 'id' ? 'Silakan isi formulir berikut untuk menambahkan kandidat baru' : 'Please fill out the following form to add a new candidate')
                    ->schema([
                        Forms\Components\FileUpload::make('cv_path')
                            ->label(app()->getLocale() == 'id' ? 'CV' : 'Resume Attachment')
                            ->acceptedFileTypes(['application/pdf'])
                            ->previewable(true)
                            ->rules(['nullable', 'mimes:pdf', 'max:2048'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\Select::make('job_vacancy_id')
                            ->label(app()->getLocale() == 'id' ? 'Posisi Pekerjaan' : 'Job Position')
                            ->relationship('jobVacancy', 'title')
                            ->required()
                            ->placeholder('Select job vacancy')
                            ->rules(['required'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('first_name')
                            ->label(app()->getLocale() == 'id' ? 'Nama Depan' : 'First Name')
                            ->required()
                            ->placeholder('Enter first name')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('last_name')
                            ->label(app()->getLocale() == 'id' ? 'Nama Belakang' : 'Last Name')
                            ->required()
                            ->placeholder('Enter last name')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->placeholder('Enter email')
                            ->rules(['required', 'email', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\Select::make('gender')
                            ->label(app()->getLocale() == 'id' ? 'Jenis Kelamin' : 'Gender')
                            ->required()
                            ->placeholder('Enter Gender')
                            ->options([
                                '0' => 'Male',
                                '1' => 'Female',
                            ])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('phone_number')
                            ->label(app()->getLocale() == 'id' ? 'No Telepon' : 'Phone Number')
                            ->required()
                            ->placeholder('Enter phone number')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('address')
                            ->label(app()->getLocale() == 'id' ? 'Alamat' : 'Address')
                            ->required()
                            ->placeholder('Enter address')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('place_of_birth')
                            ->label(app()->getLocale() == 'id' ? 'Tempat Lahir' : 'Place of Birth')
                            ->required()
                            ->placeholder('Enter place of birth')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label(app()->getLocale() == 'id' ? 'Tanggal Lahir' : 'Date of Birth')
                            ->required()
                            ->placeholder('Enter date of birth')
                            ->rules(['required'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\Select::make('education')
                            ->label(app()->getLocale() == 'id' ? 'Pendidikan Terakhir' : 'Last Education')
                            ->required()
                            ->placeholder('Enter education')
                            ->options([
                                'SMA/SMK' => 'SMA/SMK',
                                'D3' => 'D3',
                                'S1' => 'S1',
                                'S2' => 'S2',
                            ])
                            ->native(false)
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('major')
                            ->label('Major')
                            ->required()
                            ->placeholder('Enter major')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\Select::make('join_date')
                            ->label(app()->getLocale() == 'id' ? 'Tanggal Mulai Kerja' : 'Join Date')
                            ->required()
                            ->placeholder('Enter join date')
                            ->options(function () {
                                $dates = [];
                                // $currentDate = date('Y');
                                for ($i = 1; $i <= 31; $i++) {
                                    // $dates[$currentDate - $i] = $currentDate - $i;
                                    $dates[$i . ' Hari'] = "{$i} Hari";
                                }
                                return $dates;
                            })
                            ->native(false)
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('linkedin_url')
                            ->label('Linkedin')
                            ->required()
                            ->placeholder('Enter linkedin')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\Select::make('job_source')
                            ->label(app()->getLocale() == 'id' ? 'Sumber Info Loker' : 'Job Source')
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
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('old_company')
                            ->label(app()->getLocale() == 'id' ? 'Perusahaan Sebelumnya' : 'Old Company')
                            ->required()
                            ->placeholder('Enter old company')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\RichEditor::make('self_description')
                            ->label(app()->getLocale() == 'id' ? 'Deskripsi Diri' : 'Self Description')
                            ->required()
                            ->placeholder('Enter self description')
                            ->rules(['required'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                    ]),
                Section::make(app()->getLocale() == 'id' ? 'Verifikasi Data Pemohon' : 'Verify Applicant Data')
                    ->description(app()->getLocale() == 'id' ? 'Silakan periksa rincian yang diberikan oleh pemohon dan verifikasi kebenarannya.' : 'Please check the details provided by the applicant and verify their correctness.')
                    ->schema([
                        // Forms\Components\Hidden::make('current_stage'),
                        Forms\Components\Select::make('is_valid')
                            ->label(app()->getLocale() == 'id' ? 'Apakah data valid?' : 'Is the data valid?')
                            ->options([
                                'yes' => app()->getLocale() == 'id' ? 'Ya' : 'Yes',
                                'no' => app()->getLocale() == 'id' ? 'Tidak' : 'No',
                            ])
                            ->disabled(function () {
                                if (Request::routeIs(app()->getLocale() == 'id' ? 'filament.admin.resources.sedang-berlangsung.rekrutmen.edit' : 'filament.admin.resources.ongoing.recruitments.edit')) {
                                    return true;
                                }
                                return false;
                            })
                            ->reactive(),

                    ])
                    ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class])
                    ->hiddenOn([Pages\CreateRecruitment::class]),
                Section::make(function ($record) {
                    if ($record->interview->count() == 0) {
                        return app()->getLocale() == 'id' ? 'Undang Wawancara User' : "Invite to User Interview";
                    } elseif ($record->interview->count() == 1) {
                        return app()->getLocale() == 'id' ? 'Undang Wawancara HR' : "Invite to HR Interview";
                    }
                    return app()->getLocale() == 'id' ? 'Undang Wawancara' : "Invite to Interview";
                })
                    ->schema([
                        Group::make()
                            ->schema([
                                Hidden::make("interview_type")
                                    ->formatStateUsing(function ($record) {
                                        if ($record != null) {
                                            if ($record->interview->isEmpty()) {
                                                return InterviewType::USER->getLabel();
                                            } elseif ($record->interview->count() == 1) {
                                                return InterviewType::HR->getLabel();
                                            }
                                        }
                                        return;
                                    }),
                                Radio::make('is_invited')
                                    ->label(app()->getLocale() == 'id' ? 'Kirim Undangan Wawancara?' : "Send interview invitation?")
                                    ->required()
                                    ->options([
                                        'yes' => app()->getLocale() == 'id' ? 'Ya' : 'Yes',
                                        'no' => app()->getLocale() == 'id' ? 'Tidak' : 'No',
                                    ])
                                    ->live()
                                    ->inline()
                                    ->inlineLabel(false),
                                Group::make()
                                    ->schema([
                                        DateTimePicker::make('interview_date')
                                            ->label(app()->getLocale() == 'id' ? 'Tanggal Wawancara' : 'Interview Date')
                                            ->native(false)
                                            ->required()
                                            ->hoursStep(2)
                                            ->minutesStep(15)
                                            ->secondsStep(10),
                                        // visible only if is_invited is true
                                        // ->visible(fn ($get) => $get('is_invited') === true),


                                        TextInput::make('google_meet_link')
                                            ->url()
                                            ->label(app()->getLocale() == 'id' ? 'tautan panggilan' : 'Link Concall')
                                            // ->required()
                                            ->placeholder('Enter old company')
                                            ->rules(['max:255'])
                                        // visible only if is_invited is true
                                        ,

                                        Textarea::make('notes')
                                            ->label(app()->getLocale() == 'id' ? 'Catatan Informasi' : 'Information Notes')
                                            // ->required()
                                            ->rows(10)
                                            ->cols(20),
                                        // visible only if is_invited is true
                                    ])
                                    ->visible(function (Get $get) {
                                        $check = $get('is_invited');


                                        if ($check == 'yes') {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    })
                            ])
                    ])
                    ->hiddenOn([Pages\CreateRecruitment::class, Pages\EditRecruitment::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                Section::make(function ($record) {
                    if ($record->interview->count() == 1 && $record->interview->first()->interviewResult == false) {
                        return app()->getLocale() == 'id' ? 'Hasil Wawancara User' : "User Interview Result";
                    } elseif ($record->interview->count() == 2 && $record->interview->last()->interviewResult == false) {
                        return app()->getLocale() == 'id' ? 'Hasil Wawancara HR' : "HR Interview Result";
                    }
                    return app()->getLocale() == 'id' ? 'Hasil Wawancara' : "Interview Result";
                })
                    ->schema([
                        Group::make()
                            ->schema([
                                Hidden::make("interview_id")
                                    ->formatStateUsing(function ($record) {
                                        if ($record != null) {
                                            if ($record->interview->count() == 1 && $record->interview->first()->interviewResult == false) {
                                                return $record->interview->first()->id;
                                            } else if ($record->interview->count() == 2 && $record->interview->last()->interviewResult == false) {
                                                return $record->interview->last()->id;
                                            }
                                        }
                                    }),
                                Radio::make('is_success')
                                    ->label(app()->getLocale() == 'id' ? 'Lulus atau Tidak?' : "Passed or Not?")
                                    ->required()
                                    ->options([
                                        'yes' => app()->getLocale() == 'id' ? 'Ya' : 'Yes',
                                        'no' => app()->getLocale() == 'id' ? 'Tidak' : 'No',
                                    ])
                                    ->inline()
                                    ->live()
                                    ->inlineLabel(false),
                                Textarea::make('review')
                                    ->label(app()->getLocale() == 'id' ? 'Catatan Hasil Wawancara' : 'interview results notes')
                                    ->rows(10)
                                    ->cols(20)
                                    ->visible(function (Get $get) {
                                        $check = $get('is_success');

                                        if ($check == 'yes') {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    })
                            ])

                    ])
                    ->hiddenOn([Pages\CreateRecruitment::class, Pages\EditRecruitment::class, Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentValid::class]),
            ]);
    }

    public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(app()->getLocale() == 'id' ? 'Informasi Pelamar' : 'applicant information')
                    // ->description(function ($record){
                    //     $record->acceptend_status == 'Pending'
                    // })
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('full_name')
                                        ->label(app()->getLocale() == 'id' ? 'Nama Lengkap' : 'Full Name')
                                        ->state(function ($record) {
                                            return $record->first_name . ' ' . $record->last_name;
                                        }),
                                    Infolists\Components\TextEntry::make('gender')
                                        ->label(app()->getLocale() == 'id' ? 'Jenis Kelamin' : 'Gender')
                                        ->formatStateUsing(fn($state) => (app()->getLocale() == 'id') ? (($state == 1) ? "Laki-Laki" : "Perempuan") : (($state == 1) ? "Male" : "Female")),
                                    Infolists\Components\TextEntry::make('email'),
                                    Infolists\Components\TextEntry::make('phone_number')
                                        ->label(app()->getLocale() == 'id' ? 'No Telepon' : 'Phone Number'),
                                    Infolists\Components\TextEntry::make('address')
                                        ->label(app()->getLocale() == 'id' ? 'Alamat' : 'Address'),
                                    Infolists\Components\TextEntry::make('birth_information')
                                        ->label(app()->getLocale() == 'id' ? 'Tempat, Tanggal Lahir' : 'Place, Date of Birth')
                                        ->state(function ($record) {
                                            return $record->place_of_birth . ', ' . $record->date_of_birth;
                                        }),
                                ]),
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('education')
                                        ->label(app()->getLocale() == 'id' ? 'Pendidikan Terakhir' : 'Last Education'),
                                    Infolists\Components\TextEntry::make('major')
                                        ->label('Jurusan'),
                                    Infolists\Components\TextEntry::make('join_date')
                                        ->label(app()->getLocale() == 'id' ? 'Tanggal Mulai Kerja' : 'Join Date'),
                                    Infolists\Components\TextEntry::make('linkedin_url')
                                        ->label('LinkedIn'),
                                    Infolists\Components\TextEntry::make('job_source')
                                        ->label(app()->getLocale() == 'id' ? 'Sumber Info Loker' : 'Job Source'),
                                    Infolists\Components\TextEntry::make('old_company')
                                        ->label(app()->getLocale() == 'id' ? 'Perusahaan Sebelumnya' : 'Old Company'),
                                ]),
                                Infolists\Components\ImageEntry::make('cv_path')
                                    ->label(app()->getLocale() == 'id' ? 'Lampiran Peralamar' : 'Applicant attachments')
                                    ->grow(false),
                            ]),
                        Infolists\Components\Fieldset::make(app()->getLocale() == 'id' ? 'Tentang Diri Pelamar' : 'Self Description')
                            ->schema([
                                Infolists\Components\TextEntry::make('self_description')
                                    ->hiddenLabel()
                                    ->prose()
                                    ->markdown()
                                    ->html(),
                            ])
                    ]),
                Infolists\Components\Section::make(app()->getLocale() == 'id' ? 'Pekerjaan Yang Dilamar' : 'Job Vacancy')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\Group::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('jobVacancy.title')
                                            ->label(app()->getLocale() == 'id' ? 'Posisi Pekerjaan' : 'Job Position'),
                                        Infolists\Components\TextEntry::make('jobVacancy.work_hours')
                                            ->label(app()->getLocale() == 'id' ? 'Jam Kerja' : 'Work Hours'),
                                        Infolists\Components\TextEntry::make('jobVacancy.location')
                                            ->label(app()->getLocale() == 'id' ? 'Alamat Pekerjaan' : 'Location'),
                                    ]),
                                Infolists\Components\Group::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('jobVacancy.experience')
                                            ->label(app()->getLocale() == 'id' ? 'Level Pekerjaan' : 'Job Level'),
                                        Infolists\Components\IconEntry::make('jobVacancy.remote')
                                            ->label('Remote?')
                                            ->icon(fn(string $state): string => match ($state) {
                                                '1' => 'heroicon-o-check-circle',
                                                '0' => 'heroicon-o-x-circle',
                                            })
                                            ->color(fn(string $state): string => match ($state) {
                                                '1' => 'success',
                                                '0' => 'danger',
                                            }),

                                        Infolists\Components\TextEntry::make('jobVacancy.valid_until')
                                            ->label(app()->getLocale() == 'id' ? 'Berlaku Sampai' : 'Valid Until'),
                                    ]),
                                Infolists\Components\ImageEntry::make('jobVacancy.image')
                                    ->label('Gambar')
                            ]),
                        Infolists\Components\Fieldset::make(app()->getLocale() == 'id' ? 'Tentang Pekerjaan' : 'About Job')
                            ->schema([
                                Infolists\Components\TextEntry::make('jobVacancy.description')
                                    ->label(app()->getLocale() == 'id' ? 'Deskripsi :' : 'Description :')
                                    ->html(),
                                Infolists\Components\TextEntry::make('jobVacancy.qualifications')
                                    ->label(app()->getLocale() == 'id' ? 'Kualifikasi :' : 'Qualifications :')
                                    ->html(),
                            ])->columns(2)
                    ])
                    ->collapsible(),
                Infolists\Components\Section::make(app()->getLocale() == 'id' ? 'Data Wawancara Pelamar' : 'Applicant Interview Data')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\RepeatableEntry::make('interview')
                                    ->hiddenLabel()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('interview_type')
                                            ->label(app()->getLocale() == 'id' ? 'Jenis Wawancara' : 'Interview Type')
                                            ->formatStateUsing(fn($state) => $state == 'user' ? 'User' : 'HR'),
                                        Infolists\Components\TextEntry::make('interview_date')
                                            ->label(app()->getLocale() == 'id' ? 'Tanggal Wawancara' : 'Interview Date')
                                            ->dateTime('d M, Y \J\a\m H:i \-\ \S\e\l\e\s\a\i', 'Asia/Jakarta'),
                                        Infolists\Components\TextEntry::make('google_meet_link')
                                            ->label('Link Meeting'),
                                        Infolists\Components\TextEntry::make('is_invited')
                                            ->label(app()->getLocale() == 'id' ? 'Apakah Diundang?' : "Is Invited?")
                                            ->formatStateUsing(fn($state) => (app()->getLocale() == 'id') ? (($state == 'yes') ? "Diundang" : "Tidak Diundang") : (($state == 'yes') ? "Invited" : "Not Invited"))
                                            ->icon(fn(string $state): string => match ($state) {
                                                'yes' => 'heroicon-o-check-circle',
                                                'no' => 'heroicon-o-x-circle',
                                            })
                                            ->color(fn(string $state): string => match ($state) {
                                                'yes' => 'success',
                                                'no' => 'danger',
                                            }),
                                        Infolists\Components\TextEntry::make('notes')
                                            ->label(app()->getLocale() == 'id' ? 'Catatan Informasi' : 'Information Notes')
                                            ->prose()
                                            ->markdown(),

                                        Infolists\Components\Fieldset::make(app()->getLocale() == 'id' ? 'Hasil Wawancara' : "Interview Result")
                                            ->schema([
                                                Infolists\Components\TextEntry::make('interviewResult.is_success')
                                                    ->label(app()->getLocale() == 'id' ? 'Apakah Lulus Wawancara' : 'Did Pass Interview?')
                                                    ->formatStateUsing(fn($state) => (app()->getLocale() == 'id') ? (($state == 'yes') ? "Lulus" : "Gagal") : (($state == 'yes') ? "Accepted" : "Failed"))
                                                    ->icon(fn(string $state): string => match ($state) {
                                                        'yes' => 'heroicon-o-check-circle',
                                                        'no' => 'heroicon-o-x-circle',
                                                    })
                                                    ->color(fn(string $state): string => match ($state) {
                                                        'yes' => 'success',
                                                        'no' => 'danger',
                                                    }),
                                                Infolists\Components\TextEntry::make('interviewResult.review')
                                                    ->label(app()->getLocale() == 'id' ? 'Catatan Hasil Wawancara' : 'interview results notes')
                                            ])->columns(1)
                                    ])
                                    ->columnSpanFull()
                                    ->grid(2),

                            ]),
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        if (Request::routeIs(app()->getLocale() == 'id' ? 'filament.admin.resources.sedang-berlangsung.rekrutmen.view' : 'filament.admin.resources.ongoing.recruitments.view')) { // Pages\ViewRecruitment
            $relation = [];
        } else {
            $relation = [InterviewRelationManager::class];
        }

        return $relation;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\CustomListRecruitment::route('/'),
            'create' => Pages\CreateRecruitment::route('/create'),
            'view' => Pages\ViewRecruitment::route('/{record}/view'),
            'edit' => Pages\EditRecruitment::route('/{record}/edit'),
            'verify-data-recruitment' => Pages\EditRecruitmentValid::route('/{record}/verify-recruitment'),
            'create-interview' => Pages\EditRecruitmentInterview::route('/{record}/create-interview'),
            'create-interview-result' => Pages\EditRecruitmentInterviewResult::route('/{record}/create-interview-result'),
        ];
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            $result = 'Rekrutmen';
        } else {
            $result = 'Recruitments';
        }
        return $result;
    }
    public static function getNavigationGroup(): ?string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            $result = 'Sedang Berlangsung';
        } else {
            $result = 'Ongoing';
        }
        return $result;
    }
    // protected static ?string $slug = 'ongoing/recruitments';
    public static function getSlug(): string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            $result = 'sedang-berlangsung/rekrutmen';
        } else {
            $result = 'ongoing/recruitments';
        }
        return $result;
    }
}
