<?php

namespace App\Livewire;

use Filament\Tables;
use Livewire\Component;
use App\Models\UserApplyJob;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitment;
use App\Filament\Resources\RecruitmentResource\Pages\ViewRecruitment;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentInterview;
use App\Filament\Resources\RecruitmentResource\Pages\EditRecruitmentInterviewResult;

class WorkflowRecruitment extends Component implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;

    public $record;

    public function mount(UserApplyJob $record)
    {
    }
    public function render()
    {
        return view('livewire.workflow-recruitment');
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(UserApplyJob::query())
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
                        if($data->count() == 2){
                            return (strpos($state, 'yes') !== false ? 'Ya' : 'Tidak');
                        }
                        return '';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('interviewResult.is_success')
                    ->label('Lulus Wawancara')
                    ->formatStateUsing(function ($record, $state) {
                        $data = $record->interviewResult;
                        if($data->count() == 2){
                            return (strpos($state, 'yes') !== false ? 'Ya' : 'Tidak');
                        }
                        return '';
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordUrl(fn (Model $record) => ViewRecruitment::getUrl([$record->id]))
            ->actions([
                Tables\Actions\Action::make('Review')
                    ->icon('heroicon-s-eye')
                    ->url(fn (Model $record) => EditRecruitment::getUrl([$record->id]))
                    ->visible(fn ($record) => ($record->is_valid != "yes")),
                Tables\Actions\Action::make('Undang Wawancara')
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
                Tables\Actions\Action::make('Input Hasil Wawancara')
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
                Tables\Actions\Action::make('Terima')
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
                Tables\Actions\Action::make('Tolak')
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
}
