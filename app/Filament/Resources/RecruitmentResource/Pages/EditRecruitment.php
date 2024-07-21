<?php

namespace App\Filament\Resources\RecruitmentResource\Pages;

use Filament\Actions;
use App\Mail\SendMail;
use App\Models\Interview;
use App\Models\JobVacancy;
use App\Models\UserApplyJob;
use App\Models\InterviewResult;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\RecruitmentResource;
use App\Actions\ProsesEditRecruitment\isValidAction;
use App\Actions\ProsesEditRecruitment\isInvitedAction;
use App\Actions\ProsesEditRecruitment\isSuccessAction;
use App\Filament\Resources\RecruitmentResource\RelationManagers;
use App\Filament\Resources\RecruitmentResource\RelationManagers\InterviewsRelationManager;
use App\Filament\Resources\RecruitmentResource\RelationManagers\InterviewResultsRelationManager;

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
        // dd($record);
        if (isset($data['is_valid'])) {
            return isValidAction::run($record, $data);
        } else if (isset($data['is_invited'])) {
            // dd($data);
            return isInvitedAction::run($record, $data);
        } else if (isset($data['is_success'])) {
            return isSuccessAction::run($record, $data);
        }
        
        // Update the user apply job record
        //     $record->update($data);

        // Check if is_valid is 'yes', then insert or update interview
        // if ($record->is_valid === 'yes') {
        //     if ($record->interview) {
        //         $record->interview->update($is_invited);
        //     } else {
        //         $record->interview()->create($is_invited);
        //     }
        // }

        // Check if is_success is 'yes', then insert or update interview result
        // if (isset($data['interviewResult']['is_success']) && $data['interviewResult']['is_success'] === 'yes') {
        //     if ($record->interviewResult) {
        //         $record->interviewResult->update($data['interviewResult']);
        //     } else {
        //         $record->interviewResult()->create($data['interviewResult']);
        //     }
        // }

        // return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
