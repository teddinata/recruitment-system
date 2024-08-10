<?php

namespace App\Livewire;

use stdClass;
use Filament\Tables;
use Livewire\Component;
use App\Models\UserApplyJob;
use App\Enums\StageRecruitment;
use App\Enums\StatusRecruitment;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitment;
use App\Filament\Resources\RecruitmentResource\Pages\ViewRecruitment;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentValid;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentInterview;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentInterviewResult;
use Carbon\Carbon;

class WorkflowRecruitment extends Component implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;

    public UserApplyJob $record;

    public $filter = null;

    public $filterBackup = null;

    public $resetTable = false;

    public $loadFilter;

    protected $listeners = ['filterRefresh' => '$refresh'];

    public function refreshFilter()
    {
        $this->loadFilter = Request::query("filter");
    }

    public function resetFilter()
    {
        $this->resetTable = true;
        $this->dispatch('filterRefresh');
    }

    public function mount(UserApplyJob $record)
    {
        $this->record = $record;
        $this->refreshFilter();
        if ($this->loadFilter) {
            $this->filterBackup = $this->loadFilter;
        }
    }
    public function updateFilter($filter)
    {
        $this->filter = $filter;
        $this->dispatch('filterRefresh');
        // $this->dispatch('filter-updated', filter: $filter);
    }

    protected function getQuery()
    {
        $query = UserApplyJob::query();
        if (!$this->resetTable) {
            if ($this->filter ?? $this->filterBackup) {
                switch ($this->filter ?? $this->filterBackup) {
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
                            fn(Builder $subQuery) =>
                            $subQuery->where('is_invited', 'yes')->where('interview_type', 'user')->whereDoesntHave('interviewResult')
                        );
                        break;
                    case 'Event_1yf59cf': // filter data pelamar tidak lulus tahap wawancara user -> Notifikasi Tidak Lulus Interview User
                        return $query->whereHas('interview', function (Builder $subQuery) {
                            $subQuery->where('is_invited', 'yes')
                                ->where('interview_type', 'user')
                                ->whereHas('interviewResult', fn(Builder $subQuery2) => $subQuery2->where('is_success', 'no'));
                        })->has('interview', '=', 1);
                        break;
                    case 'Activity_0y70sst': // filter data pelamar lulus tahap wawancara user -> Input jadwal Interview HR
                        return $query->whereHas('interview', function (Builder $subQuery) {
                            $subQuery->where('is_invited', 'yes')
                                ->where('interview_type', 'user')
                                ->whereHas('interviewResult', fn(Builder $subQuery2) => $subQuery2->where('is_success', 'yes'));
                        })->has('interview', '=', 1);
                        break;
                    case 'Activity_0qi07gz': // filter data pelamar yang diundang wawancara hr -> Input Hasil Interview HR
                        return $query->whereHas(
                            'interview',
                            fn(Builder $subQuery) =>
                            $subQuery->where('is_invited', 'yes')->where('interview_type', 'hr')->whereDoesntHave('interviewResult')
                        );
                        break;
                    case 'Event_00wxbct': // filter data pelamar tidak lulus tahap wawancara hr -> Notifikasi Tidak Lulus Interview HR
                        return $query->whereHas('interview', function (Builder $subQuery) {
                            $subQuery->where('is_invited', 'yes')
                                ->where('interview_type', 'hr')
                                ->whereHas('interviewResult', fn(Builder $subQuery2) => $subQuery2->where('is_success', 'no'));
                        });
                        break;
                    case 'Activity_00fjfo4': // filter data pelamar lulus tahap wawancara hr -> Input Hasil Akhir
                        return $query->whereHas('interview', function (Builder $subQuery) {
                            $subQuery->where('is_invited', 'yes')
                                ->where('interview_type', 'hr')
                                ->whereHas('interviewResult', fn(Builder $subQuery2) => $subQuery2->where('is_success', 'yes'));
                        });
                        break;
                        // default:
                        //     return $query->whereNot(function (Builder $subQuery) {
                        //         $subQuery->whereIn('acceptance_status', [
                        //             StatusRecruitment::FAILED->value,
                        //             StatusRecruitment::REJECTED->value,
                        //             StatusRecruitment::ACCEPTED->value
                        //         ]);
                        //     });
                        //     break;
                }
            }
        }
        return $query->whereNot(function (Builder $subQuery) {
            $subQuery->whereIn('acceptance_status', [
                StatusRecruitment::FAILED->value,
                StatusRecruitment::REJECTED->value,
                StatusRecruitment::ACCEPTED->value
            ]);
        });;
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('jobVacancy.title')
                    ->label(app()->getLocale() == 'id' ? 'Posisi Pekerjaan' : 'Job Position')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cv_path')
                    ->label(app()->getLocale() == 'id' ? 'CV' : 'Resume Attachment')
                    ->formatStateUsing(function ($state) {
                        return '<a href="' . asset('storage/' . $state) . '" target="_blank">Lihat CV</a>';
                    })
                    ->html()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('full_name')
                    ->label(app()->getLocale() == 'id' ? 'Nama Lengkap' : 'Full Name')
                    ->state(function ($record) {
                        return $record->first_name . ' ' . $record->last_name;
                    })
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('gender')
                    ->label(app()->getLocale() == 'id' ? 'Jenis Kelamin' : 'Gender')
                    ->formatStateUsing(fn($state) => (app()->getLocale() == 'id') ? (($state == 1) ? "Laki-Laki" : "Perempuan") : (($state == 1) ? "Male" : "Female")),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('acceptance_status')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_stage')
                    ->label(app()->getLocale() == 'id' ? 'Tahap Saat Ini' : 'Current Stage')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label(app()->getLocale() == 'id' ? 'No Telepon' : 'Phone Number')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('place_and_date_birth')
                    ->label(app()->getLocale() == 'id' ? 'Tempat, Tanggal Lahir' : 'Place, Date of Birth')
                    ->getStateUsing(function ($record) {
                        return $record->place_of_birth . ', ' . $record->date_of_birth;
                    })
                    ->searchable(['place_of_birth', 'date_of_birth'])
                    ->sortable(['place_of_birth', 'date_of_birth'])
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('old_company')
                    ->label(app()->getLocale() == 'id' ? 'Perusahaan Sebelumnya' : 'Old Company')
                    ->searchable()
                    ->sortable()
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->persistSearchInSession()
            ->filters([
                //
            ])
            ->toggleColumnsTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label(app()->getLocale() == 'id' ? 'Beralih Kolom' : 'Toggle columns'),
            )
            ->recordUrl(fn(Model $record) => ViewRecruitment::getUrl([$record->id]))
            ->actions([
                Tables\Actions\Action::make('Review')
                    ->label(app()->getLocale() == 'id' ? 'Tinjauan' : 'Review')
                    ->icon('heroicon-s-eye')
                    ->url(fn(Model $record) => EditRecruitmentValid::getUrl([$record->id]))
                    ->visible(fn($record) => (is_null($record->is_valid))),
                Tables\Actions\Action::make('Invite to Interview')
                    ->label(function ($record) {
                        if ($record->interview->count() == 0 && $record->is_valid == "yes") {
                            return app()->getLocale() == 'id' ? 'Undang Wawancara User' : "Invite to User Interview";
                        } elseif ($record->interview->count() == 1 && $record->interview->first()->interviewResult == true) {
                            return app()->getLocale() == 'id' ? 'Undang Wawancara HR' : "Invite to HR Interview";
                        }
                    })
                    ->icon('heroicon-s-phone')
                    ->url(fn(Model $record) => EditRecruitmentInterview::getUrl([$record->id]))
                    ->visible(function ($record) {
                        if ($record->interview->count() == 0 && $record->is_valid == "yes") {
                            return true;
                        } elseif ($record->interview->count() == 1 && $record->interview->first()->interviewResult == true) {
                            return true;
                        }
                        return false;
                    }),
                Tables\Actions\Action::make('Interview Result')
                    ->label(function ($record) {
                        if ($record->interview->count() == 1 && $record->interview->first()->interviewResult == false) {
                            return app()->getLocale() == 'id' ? 'Hasil Wawancara User' : "User Interview Result";
                        } else if ($record->interview->count() == 2 && $record->interview->last()->interviewResult == false) {
                            return app()->getLocale() == 'id' ? 'Hasil Wawancara HR' : "HR Interview Result";
                        }
                    })
                    ->icon('heroicon-s-clipboard-document')
                    ->url(fn(Model $record) => EditRecruitmentInterviewResult::getUrl([$record->id]))
                    ->visible(function ($record) {
                        if ($record->interview->count() == 1 && $record->interview->first()->interviewResult == false) {
                            return true;
                        } else if ($record->interview->count() == 2 && $record->interview->last()->interviewResult == false) {
                            return true;
                        } else {
                            return false;
                        }
                    }),
                Tables\Actions\Action::make('Accepted')
                    ->label(app()->getLocale() == 'id' ? 'Terima' : 'Accepted')
                    ->color('success')
                    ->icon('heroicon-s-check-circle')
                    ->action(function ($record) {

                        $record->update([
                            'current_stage' => StageRecruitment::FDC->value,
                            'acceptance_status' => StatusRecruitment::ACCEPTED->value,
                            'status_created_at' => Carbon::now(),
                        ]);
                        Notification::make()
                            ->title('Saved successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->visible(function ($record) {
                        if ($record->interview->count() == 2 && $record->interview->last()->interviewResult) {
                            if ($record->interview->last()->interviewResult->where('is_success', 'yes')) {
                                if ($record->acceptance_status == StatusRecruitment::PENDING) {
                                    return true;
                                };
                                return false;
                            }
                            return false;
                        } else {
                            return false;
                        }
                    }),
                Tables\Actions\Action::make('Rejected')
                    ->label(app()->getLocale() == 'id' ? 'Terima' : 'Rejected')
                    ->color('danger')
                    ->icon('heroicon-s-x-circle')
                    ->action(function ($record) {
                        $record->update([
                            'current_stage' => StageRecruitment::FD->value,
                            'acceptance_status' => StatusRecruitment::REJECTED->value,
                            'status_created_at' => Carbon::now(),
                        ]);
                        Notification::make()
                            ->title('Saved successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->visible(function ($record) {

                        if ($record->interview->count() == 2 && $record->interview->last()->interviewResult) {
                            if ($record->interview->last()->interviewResult->where('is_success', 'yes')) {
                                if ($record->acceptance_status == StatusRecruitment::PENDING) {
                                    return true;
                                };
                                return false;
                            }
                            return false;
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
    public function render()
    {
        return view('livewire.workflow-recruitment');
    }
}
