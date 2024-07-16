<?php

namespace App\Filament\Resources\RecruitmentResource\Pages;

use App\Filament\Resources\RecruitmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\RecruitmentResource\RelationManagers;
use App\Filament\Resources\RecruitmentResource\RelationManagers\InterviewsRelationManager;
use App\Filament\Resources\RecruitmentResource\RelationManagers\InterviewResultsRelationManager;
use App\Models\Interview;
use App\Models\InterviewResult;
use App\Models\UserApplyJob;
use App\Models\JobVacancy;

class EditRecruitment extends EditRecord
{
    protected static string $resource = RecruitmentResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //     ];
    // }

    // edit recruitment form fields with interview and interview result
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Update the user apply job record
        $record->update($data);

        // Check if is_valid is 'yes', then insert or update interview
        if ($record->is_valid === 'yes') {
            if ($record->interview) {
                $record->interview->update($data);
            } else {
                $record->interview()->create($data);
            }
        }

        // Check if is_success is 'yes', then insert or update interview result
        if (isset($data['interviewResult']['is_success']) && $data['interviewResult']['is_success'] === 'yes') {
            if ($record->interviewResult) {
                $record->interviewResult->update($data['interviewResult']);
            } else {
                $record->interviewResult()->create($data['interviewResult']);
            }
        }

        return $record;
    }
}
