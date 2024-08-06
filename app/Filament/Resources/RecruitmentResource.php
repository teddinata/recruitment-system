<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UserApplyJob;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use App\Enums\InterviewType;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\Filament\Resources\RecruitmentResource\Pages;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitment;
use App\Filament\Resources\RecruitmentResource\Pages\ViewRecruitment;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentInterview;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentInterviewResult;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentValid;
use App\Filament\Resources\RecruitmentResource\RelationManagers\InterviewRelationManager;
use Filament\Forms\Components\{DateTimePicker, Repeater, RichEditor, Table as TableComponent};
use Filament\Forms\Components\{Card, FileUpload, TextInput, Textarea, DatePicker, Group, Radio, Select};

class RecruitmentResource extends Resource
{
    protected static ?string $model = UserApplyJob::class;

    protected static ?string $slug = 'ongoing/recruitments';

    protected static ?string $label = 'Recruitments';

    protected static ?string $navigationGroup = "Ongoing";

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Add Candidate')
                    ->description('Please fill out the following form to add a new candidate')
                    ->schema([
                        Forms\Components\FileUpload::make('cv_path')
                            ->label('Curriculum Vitae')
                            ->acceptedFileTypes(['application/pdf'])
                            ->previewable(true)
                            ->rules(['nullable', 'mimes:pdf', 'max:2048'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\Select::make('job_vacancy_id')
                            ->label('Job Position')
                            ->relationship('jobVacancy', 'title')
                            ->required()
                            ->placeholder('Select job vacancy')
                            ->rules(['required'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->placeholder('Enter first name')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
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
                            ->label('Gender')
                            ->required()
                            ->placeholder('Enter Gender')
                            ->options([
                                '0' => 'Male',
                                '1' => 'Female',
                            ])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->required()
                            ->placeholder('Enter phone number')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('address')
                            ->label('Address')
                            ->required()
                            ->placeholder('Enter address')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('place_of_birth')
                            ->label('Place of Birth')
                            ->required()
                            ->placeholder('Enter place of birth')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->required()
                            ->placeholder('Enter date of birth')
                            ->rules(['required'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
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
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('major')
                            ->label('Major')
                            ->required()
                            ->placeholder('Enter major')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\Select::make('join_date')
                            ->label('Join Date')
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
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\TextInput::make('old_company')
                            ->label('Old Company')
                            ->required()
                            ->placeholder('Enter old company')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                        Forms\Components\RichEditor::make('self_description')
                            ->label('Self Description')
                            ->required()
                            ->placeholder('Enter self description')
                            ->rules(['required'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                    ]),
                Section::make('Verify Applicant Data')
                    ->description('Please check the details provided by the applicant and verify their correctness.')
                    ->schema([
                        // Forms\Components\Hidden::make('current_stage'),
                        Forms\Components\Select::make('is_valid')
                            ->label('Apakah data valid?')
                            ->options([
                                'yes' => 'Ya',
                                'no' => 'Tidak',
                            ])
                            ->disabled(function () {
                                if (Request::routeIs('filament.admin.resources.ongoing.recruitments.edit')) {
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
                        return "Invite to User Interview";
                    } elseif ($record->interview->count() == 1) {
                        return "Invite to HR Interview";
                    }
                    return "Invite to Interview";
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
                                    ->label("Send interview invitation?")
                                    ->required()
                                    ->options([
                                        'yes' => 'Yes',
                                        'no' => 'No',
                                    ])
                                    ->reactive()
                                    ->inline()
                                    ->inlineLabel(false),

                                DateTimePicker::make('interview_date')
                                    ->label('Interview Date')
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
                                    ->label('Information Notes')
                                    ->rows(10)
                                    ->cols(20),
                                // visible only if is_invited is true
                            ])
                    ])
                    ->hiddenOn([Pages\CreateRecruitment::class, Pages\EditRecruitment::class, Pages\EditRecruitmentInterviewResult::class, Pages\EditRecruitmentValid::class]),
                Section::make(function ($record) {
                    if ($record->interview->count() == 1 && $record->interview->first()->interviewResult == false) {
                        return "User Interview Result";
                    } elseif ($record->interview->count() == 2 && $record->interview->last()->interviewResult == false) {
                        return "HR Interview Result";
                    }
                    return "Interview Result";
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
                                    ->label("Passed or Not?")
                                    ->required()
                                    ->options([
                                        'yes' => 'Yes',
                                        'no' => 'No',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false),
                                Textarea::make('review')
                                    ->label('interview results notes')
                                    ->rows(10)
                                    ->cols(20)
                            ])

                    ])
                    ->hiddenOn([Pages\CreateRecruitment::class, Pages\EditRecruitment::class, Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentValid::class]),
            ]);
    }

    public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Pelamar')
                    // ->description(function ($record){
                    //     $record->acceptend_status == 'Pending'
                    // })
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('full_name')
                                        ->label('Nama Lengkap')
                                        ->state(function ($record) {
                                            return $record->first_name . ' ' . $record->last_name;
                                        }),
                                    Infolists\Components\TextEntry::make('gender')
                                        ->label('Jenis Kelamin')
                                        ->formatStateUsing(fn ($state) => ($state == 1) ? 'Laki-Laki' : 'Perempuan'),
                                    Infolists\Components\TextEntry::make('email'),
                                    Infolists\Components\TextEntry::make('phone_number')
                                        ->label('Nomor Telepon'),
                                    Infolists\Components\TextEntry::make('address')
                                        ->label('Alamat'),
                                    Infolists\Components\TextEntry::make('birth_information')
                                        ->label('Tempat, Tanggal Lahir')
                                        ->state(function ($record) {
                                            return $record->place_of_birth . ', ' . $record->date_of_birth;
                                        }),
                                ]),
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('education')
                                        ->label('Pendidikan Terakhir'),
                                    Infolists\Components\TextEntry::make('major')
                                        ->label('Jurusan'),
                                    Infolists\Components\TextEntry::make('join_date')
                                        ->label('Tanggal Mulai Kerja'),
                                    Infolists\Components\TextEntry::make('linkedin_url')
                                        ->label('Link Linked'),
                                    Infolists\Components\TextEntry::make('job_source')
                                        ->label('Sumber Informasi Loker'),
                                    Infolists\Components\TextEntry::make('old_company')
                                        ->label('Perusahaan Sebelumnya'),
                                ]),
                                Infolists\Components\ImageEntry::make('cv_path')
                                    ->label('Lampiran Peralamar')
                                    ->grow(false),
                            ]),
                        Infolists\Components\Fieldset::make('Tentang Diri Pelamar')
                            ->schema([
                                Infolists\Components\TextEntry::make('self_description')
                                    ->hiddenLabel()
                                    ->prose()
                                    ->markdown()
                                    ->html(),
                            ])
                    ]),
                Infolists\Components\Section::make('Pekerjaan Yang Dilamar')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\Group::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('jobVacancy.title')
                                            ->label('Posisi Pekerjaan'),
                                        Infolists\Components\TextEntry::make('jobVacancy.work_hours')
                                            ->label('Jam Kerja'),
                                        Infolists\Components\TextEntry::make('jobVacancy.location')
                                            ->label('Posisi Pekerjaan'),
                                    ]),
                                Infolists\Components\Group::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('jobVacancy.experience')
                                            ->label('Level Pekerjaan'),
                                        Infolists\Components\IconEntry::make('jobVacancy.remote')
                                            ->label('Remote?')
                                            ->icon(fn (string $state): string => match ($state) {
                                                '1' => 'heroicon-o-check-circle',
                                                '0' => 'heroicon-o-x-circle',
                                            })
                                            ->color(fn (string $state): string => match ($state) {
                                                '1' => 'success',
                                                '0' => 'danger',
                                            }),

                                        Infolists\Components\TextEntry::make('jobVacancy.location')
                                            ->label('Posisi Pekerjaan'),
                                    ]),
                                Infolists\Components\ImageEntry::make('jobVacancy.image')
                                    ->label('Gambar')
                            ]),
                        Infolists\Components\Fieldset::make('Tentang Pekerjaan')
                            ->schema([
                                Infolists\Components\TextEntry::make('jobVacancy.description')
                                    ->label('Deskripsi :')
                                    ->html(),
                                Infolists\Components\TextEntry::make('jobVacancy.qualifications')
                                    ->label('Kualifikasi :')
                                    ->html(),
                            ])->columns(2)
                    ])
                    ->collapsible(),
                Infolists\Components\Section::make('Data Wawancara Pelamar')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\RepeatableEntry::make('interview')
                                    ->hiddenLabel()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('interview_type')
                                            ->label('Jenis Wawancara')
                                            ->formatStateUsing(fn ($state) => $state == 'user' ? 'User' : 'HR'),
                                        Infolists\Components\TextEntry::make('interview_date')
                                            ->label('Tanggal Wawancara')
                                            ->dateTime('d M, Y \J\a\m H:i \-\ \S\e\l\e\s\a\i', 'Asia/Jakarta'),
                                        Infolists\Components\TextEntry::make('google_meet_link')
                                            ->label('Link Meeting'),
                                        Infolists\Components\TextEntry::make('is_invited')
                                            ->label('Apakah Di undang?')
                                            ->formatStateUsing(fn ($state) => $state == 'yes' ? 'Diundang' : 'Ditolak')
                                            ->icon(fn (string $state): string => match ($state) {
                                                'yes' => 'heroicon-o-check-circle',
                                                'no' => 'heroicon-o-x-circle',
                                            })
                                            ->color(fn (string $state): string => match ($state) {
                                                'yes' => 'success',
                                                'no' => 'danger',
                                            }),
                                        Infolists\Components\TextEntry::make('notes')
                                            ->label('Catatan Wawancara')
                                            ->prose()
                                            ->markdown(),

                                        Infolists\Components\Fieldset::make('Hasil Wawancara')
                                            ->schema([
                                                Infolists\Components\TextEntry::make('interviewResult.is_success')
                                                    ->label('Apakah Lulus Wawancara')
                                                    ->formatStateUsing(fn ($state) => $state == 'yes' ? 'Lulus' : 'Gagal')
                                                    ->icon(fn (string $state): string => match ($state) {
                                                        'yes' => 'heroicon-o-check-circle',
                                                        'no' => 'heroicon-o-x-circle',
                                                    })
                                                    ->color(fn (string $state): string => match ($state) {
                                                        'yes' => 'success',
                                                        'no' => 'danger',
                                                    }),
                                                Infolists\Components\TextEntry::make('interviewResult.review')
                                                    ->label('Keterangan Hasil Wawancara')
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
        if (Request::routeIs('filament.admin.resources.recruitments.view')) { // Pages\ViewRecruitment
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
}
