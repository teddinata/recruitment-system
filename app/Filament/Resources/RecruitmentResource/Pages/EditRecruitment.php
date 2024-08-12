<?php

namespace App\Filament\Resources\RecruitmentResource\Pages;

use Filament\Actions;
use App\Mail\SendMail;
use App\Models\Interview;
use App\Models\JobVacancy;
use App\Models\UserApplyJob;
use App\Enums\StageRecruitment;
use App\Models\InterviewResult;
use App\Enums\StatusRecruitment;
use App\Mail\SendMailNotInvited;
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
use Carbon\Carbon;

class EditRecruitment extends EditRecord
{
    protected static string $resource = RecruitmentResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            $record->update($data);
        } catch (\Throwable $e) {
            throw $e;
        }
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
