<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\JobVacancy;
use Filament\Tables\Table;
use App\Models\Recruitment;
use Illuminate\Support\Arr;
use App\Models\UserApplyJob;
use Filament\Tables\Columns;
use Filament\Resources\Resource;
use Filament\Forms\Components\Get;
use App\Models\Enums\InterviewType;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RecruitmentResource\Pages;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitment;
use App\Filament\Resources\RecruitmentResource\Pages\ViewRecruitment;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentInterview;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentInterviewResult;
use App\Filament\Resources\RecruitmentResource\RelationManagers\InterviewRelationManager;
use App\Filament\Resources\RecruitmentResource\RelationManagers\InterviewsRelationManager;
use App\Infolists\Components\LivewireEntry;
use App\Livewire\WorkflowRecruitment;
use Filament\Forms\Components\{DateTimePicker, Repeater, RichEditor, Table as TableComponent};
use Filament\Forms\Components\{Card, FileUpload, TextInput, Textarea, DatePicker, Group, Radio, Select};
use Filament\Infolists\Infolist;

class RecruitmentResource extends Resource
{
    protected static ?string $model = UserApplyJob::class;

    protected static ?string $label = 'Recruitment';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Sedang Berlangsung';


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
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
                        Forms\Components\Select::make('job_vacancy_id')
                            ->label('Job Vacancy')
                            ->relationship('jobVacancy', 'title')
                            ->required()
                            ->placeholder('Select job vacancy')
                            ->rules(['required'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->placeholder('Enter first name')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->placeholder('Enter last name')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->placeholder('Enter email')
                            ->rules(['required', 'email', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->required()
                            ->placeholder('Enter phone number')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
                        Forms\Components\TextInput::make('address')
                            ->label('Address')
                            ->required()
                            ->placeholder('Enter address')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
                        Forms\Components\TextInput::make('place_of_birth')
                            ->label('Place of Birth')
                            ->required()
                            ->placeholder('Enter place of birth')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->required()
                            ->placeholder('Enter date of birth')
                            ->rules(['required'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
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
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
                        Forms\Components\TextInput::make('major')
                            ->label('Major')
                            ->required()
                            ->placeholder('Enter major')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
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
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
                        Forms\Components\TextInput::make('linkedin_url')
                            ->label('Linkedin')
                            ->required()
                            ->placeholder('Enter linkedin')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
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
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
                        Forms\Components\TextInput::make('old_company')
                            ->label('Old Company')
                            ->required()
                            ->placeholder('Enter old company')
                            ->rules(['required', 'max:255'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
                        Forms\Components\RichEditor::make('self_description')
                            ->label('Self Description')
                            ->required()
                            ->placeholder('Enter self description')
                            ->rules(['required'])
                            ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class]),
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
                    ->disabledOn([Pages\EditRecruitmentInterview::class, Pages\EditRecruitmentInterviewResult::class])
                    ->hiddenOn([Pages\CreateRecruitment::class]),
                Section::make(function ($record) {
                    if ($record->interview->count() == 0) {
                        return "Undangan Wawancara User";
                    } elseif ($record->interview->count() == 1) {
                        return "Undangan Wawancara HR";
                    }
                    return "Undangan Wawancara";
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
                                        // perbaiki1
                                    }),
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
                                    ->cols(20),
                                // visible only if is_invited is true
                            ])
                            ->hiddenOn([Pages\CreateRecruitment::class, Pages\EditRecruitment::class, Pages\ViewRecruitment::class], Pages\EditRecruitmentInterviewResult::class),

                        Group::make()
                            ->schema([
                                Repeater::make('interview')
                                    ->relationship()
                                    ->schema([
                                        Select::make("interview_type")
                                            ->options(fn () => array_column(InterviewType::cases(), 'name', 'value'))
                                            ->native(false),
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
                                            ->cols(20),
                                    ])
                            ])
                            ->hiddenOn([Pages\EditRecruitmentInterview::class]),
                    ])
                    ->hiddenOn([Pages\CreateRecruitment::class, Pages\EditRecruitment::class, Pages\EditRecruitmentInterviewResult::class]),
                Section::make(function ($record) {
                    if ($record->interview->count() == 1 && $record->interview->first()->interviewResult == false) {
                        return "Input Hasil Wawancara User";
                    } elseif ($record->interview->count() == 2 && $record->interview->last()->interviewResult == false) {
                        return "Input Hasil Wawancara HR";
                    }
                    return "Input Wawancara";
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
                                    ->label("Apakah Lulus?")
                                    ->required()
                                    ->options([
                                        'yes' => 'Ya',
                                        'no' => 'Tidak',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false),
                                Textarea::make('review')
                                    ->label('Keterangan Hasil Wawancara')
                                    ->rows(10)
                                    ->cols(20)
                            ])
                            ->hiddenOn([Pages\EditRecruitment::class]),

                    ])
                    ->hiddenOn([Pages\ViewRecruitment::class, Pages\CreateRecruitment::class, Pages\EditRecruitment::class, Pages\EditRecruitmentInterview::class]),
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jobVacancy.title')
                    ->label('Job Vacancy')
                    ->sortable()
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('gender')
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_valid')
                    ->label('Valid')
                    ->formatStateUsing(fn ($state) => $state === 'yes' ? 'Ya' : 'Tidak')
                    ->sortable(),
                Tables\Columns\TextColumn::make('interview.is_invited')
                    ->label('Diundang Wawancara')
                    ->formatStateUsing(function ($record, $state) {
                        $data = $record->interview;
                        if ($data->count() == 2) {
                            $cek = $record->interview->every(fn ($item) => ($item->is_invited === "yes"));
                            // dd("oke");
                            if ($cek) {
                                return 'Yes';
                            }
                            return '';
                        }
                        return '';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('interviewResult.is_success')
                    ->label('Lulus Wawancara')
                    ->formatStateUsing(fn ($state) => $state === 'yes' ? 'Ya' : 'Tidak')
                    // ->formatStateUsing(fn ($state) => dd($state))
                    ->sortable(),
            ])
            ->filters([
                Filter::make('task_id')
                    ->form([
                        Hidden::make('element_id')
                            ->formatStateUsing(function () {
                                $filter = Request::query('filter');
                                if ($filter) {
                                    return $filter;
                                }
                                return;
                            })
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // dd($query);
                        switch ($data['element_id']) {
                            case 'Activity_1l23d1f': //filter data pelamar belum di validasi -> Review Data Diri
                                return $query->where('is_valid', null);
                                break;
                            case 'Event_0dnse37': // filter data pelamar ditolak -> Notifikasi Tidak Lulus Seleksi
                                return $query->where('is_valid', 'no');
                                break;
                            case 'Activity_1h0pos4': // filter data pelamar yang di lulus tahap seleksi data diri -> input jadwal interview User
                                return $query->where('is_valid', 'yes')->whereDoesntHave('interview');
                                break;
                            case 'Activity_1frsqol': // filter data pelamar yang di undang wawancara user -> Input Hasil Interview User
                                return $query->whereHas(
                                    'interview',
                                    fn (Builder $subQuery) =>
                                    $subQuery->where('is_invited', 'yes')->where('interview_type', 'user')->whereDoesntHave('interviewResult')
                                );
                                break;
                            case 'Event_1yf59cf': // filter data pelamar tidak lulus tahap wawancara user -> Notifikasi Tidak Lulus Interview User
                                return $query->whereHas('interview', function (Builder $subQuery) {
                                    $subQuery->where('is_invited', 'yes')
                                        ->where('interview_type', 'user')
                                        ->whereHas('interviewResult', fn (Builder $subQuery2) => $subQuery2->where('is_success', 'no'));
                                })->has('interview', '=', 1);
                                break;
                            case 'Activity_0y70sst': // filter data pelamar lulus tahap wawancara user -> Input jadwal Interview HR
                                return $query->whereHas('interview', function (Builder $subQuery) {
                                    $subQuery->where('is_invited', 'yes')
                                        ->where('interview_type', 'user')
                                        ->whereHas('interviewResult', fn (Builder $subQuery2) => $subQuery2->where('is_success', 'yes'));
                                })->has('interview', '=', 1);
                                break;
                            case 'Activity_0qi07gz': // filter data pelamar yang diundang wawancara hr -> Input Hasil Interview HR
                                return $query->whereHas(
                                    'interview',
                                    fn (Builder $subQuery) =>
                                    $subQuery->where('is_invited', 'yes')->where('interview_type', 'hr')->whereDoesntHave('interviewResult')
                                );
                                break;
                            case 'Event_00wxbct': // filter data pelamar tidak lulus tahap wawancara hr -> Notifikasi Tidak Lulus Interview HR
                                return $query->whereHas('interview', function (Builder $subQuery) {
                                    $subQuery->where('is_invited', 'yes')
                                        ->where('interview_type', 'hr')
                                        ->whereHas('interviewResult', fn (Builder $subQuery2) => $subQuery2->where('is_success', 'no'));
                                });
                                break;
                            case 'Activity_00fjfo4': // filter data pelamar lulus tahap wawancara hr -> Input Hasil Akhir
                                return $query->whereHas('interview', function (Builder $subQuery) {
                                    $subQuery->where('is_invited', 'yes')
                                        ->where('interview_type', 'hr')
                                        ->whereHas('interviewResult', fn (Builder $subQuery2) => $subQuery2->where('is_success', 'yes'));
                                });
                                break;
                                // case 'Gateway_1awh5yc':
                                //     $query->where('column_name', 'Exclusive Gateway');
                                //     break;
                            default:
                                return $query;
                                break;
                        }
                        return $query;
                    }),
            ])
            // ->deferFilters()
            // ->persistFiltersInSession()
            ->deselectAllRecordsWhenFiltered(false)
            ->recordUrl(fn (Model $record) => ViewRecruitment::getUrl([$record->id]))
            ->actions([
                Action::make('Review')
                    ->icon('heroicon-s-eye')
                    ->url(fn (Model $record) => EditRecruitment::getUrl([$record->id]))
                    ->visible(fn ($record) => ($record->is_valid != "yes")),
                Action::make('Undang Wawancara')
                    ->label(function ($record) {
                        if ($record->interview->count() == 0 && $record->is_valid == "yes") {
                            return "Undangan Wawancara User";
                        } elseif ($record->interview->count() == 1 && $record->interview->first()->interviewResult == true) {
                            return "Undangan Wawancara HR";
                        }
                    })
                    ->icon('heroicon-s-phone')
                    ->url(fn (Model $record) => EditRecruitmentInterview::getUrl([$record->id]))
                    ->visible(function ($record) {
                        if ($record->interview->count() == 0 && $record->is_valid == "yes") {
                            return true;
                        } elseif ($record->interview->count() == 1 && $record->interview->first()->interviewResult == true) {
                            return true;
                        }
                        return false;
                    }),
                Action::make('Input Hasil Wawancara')
                    ->label(function ($record) {
                        if ($record->interview->count() == 1 && $record->interview->first()->interviewResult == false) {
                            return "Input Hasil Wawancara User";
                        } else if ($record->interview->count() == 2 && $record->interview->last()->interviewResult == false) {
                            return "Input Hasil Wawancara HR";
                        } else {
                            return '';
                        }
                    })
                    ->icon('heroicon-s-clipboard-document')
                    ->url(fn (Model $record) => EditRecruitmentInterviewResult::getUrl([$record->id]))
                    ->visible(function ($record) {
                        if ($record->interview->count() == 1 && $record->interview->first()->interviewResult == false) {
                            return true;
                        } else if ($record->interview->count() == 2 && $record->interview->last()->interviewResult == false) {
                            return true;
                        } else {
                            return false;
                        }
                    }),
                Action::make('Terima')
                    ->icon('heroicon-s-check-circle')
                    ->action(function ($record) {
                        return $record->update([
                            'acceptance_status' => 'accepted'
                        ]);
                    })
                    ->visible(function ($record) {
                        if ($record->interview->count() == 2 && $record->interview->last()->interviewResult->where('is_success', 'yes')) {
                            return true;
                        } else {
                            return false;
                        }
                    }),
                Action::make('Tolak')
                    ->icon('heroicon-s-x-circle')
                    ->action(function ($record) {
                        return $record->update([
                            'acceptance_status' => 'rejected'
                        ]);
                    })
                    ->visible(function ($record) {
                        if ($record->interview->count() == 2 && $record->interview->last()->interviewResult->where('is_success', 'yes')) {
                            return true;
                        } else {
                            return false;
                        }
                    })
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                LivewireEntry::make('table-filter-recruitment')
                    ->livewire(WorkflowRecruitment::class)
            ]);
    }

    public static function getRelations(): array
    {

        if (request()->routeIs('filament.admin.resources.recruitments.view')) {
            $relation = [];
        } else {
            $relation = [InterviewRelationManager::class];
        }

        return $relation;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecruitments::route('/'),
            'create' => Pages\CreateRecruitment::route('/create'),
            'edit' => Pages\EditRecruitment::route('/{record}/edit'),
            'view' => Pages\ViewRecruitment::route('/{record}/view'),
            'create-interview' => Pages\EditRecruitmentInterview::route('/{record}/createInterview'),
            'create-interview-result' => Pages\EditRecruitmentInterviewResult::route('/{record}/createInterviewResult'),
        ];
    }
}
