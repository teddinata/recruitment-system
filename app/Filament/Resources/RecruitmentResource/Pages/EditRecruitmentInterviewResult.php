<?php

namespace App\Filament\Resources\RecruitmentResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\RecruitmentResource;
use App\Actions\ProsesEditRecruitment\isSuccessAction;

class EditRecruitmentInterviewResult extends EditRecord
{
    protected static string $resource = RecruitmentResource::class;

    
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return isSuccessAction::run($record, $data);
        // if (isset($data['is_success'])) {
        //     return isSuccessAction::run($record, $data);
        // }
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
