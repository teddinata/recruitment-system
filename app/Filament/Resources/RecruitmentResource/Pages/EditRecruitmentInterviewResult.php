<?php

namespace App\Filament\Resources\RecruitmentResource\Pages;

use Filament\Actions;
use App\Mail\SendInterviewResult;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\RecruitmentResource;
use App\Actions\ProsesEditRecruitment\isSuccessAction;
use App\Enums\InterviewType;
use App\Enums\StageRecruitment;
use App\Enums\StatusRecruitment;
use Carbon\Carbon;

class EditRecruitmentInterviewResult extends EditRecord
{
    protected static string $resource = RecruitmentResource::class;


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            if ($record->interview->where('id', $data['interview_id'])->first()->interviewResult) {
                $record->interview->interviewResult->update($data);
            } else {
                $record->interview->where('id', $data['interview_id'])->first()->interviewResult()->create($data);
            }
            $cekInterviewType = $record->interview->where('id', $data['interview_id'])->first()->interview_type;
            if ($data['is_success'] == 'yes') {

                if ($cekInterviewType == InterviewType::USER->getLabel()) {
                    $data['acceptance_status'] = StatusRecruitment::PENDING->value;
                    $data['current_stage'] = StageRecruitment::UIC->value;
                    $record->update([
                        'current_stage' => $data['current_stage'],
                        'acceptance_status' => $data['acceptance_status'],
                    ]);
                } else {
                    $data['acceptance_status'] = StatusRecruitment::PENDING->value;
                    $data['current_stage'] = StageRecruitment::HIC->value;
                    $record->update([
                        'current_stage' => $data['current_stage'],
                        'acceptance_status' => $data['acceptance_status'],
                    ]);
                }

                // $this->sendEmailInformation($record, $data);
            } else {
                $data['acceptance_status'] = StatusRecruitment::FAILED->value;
                $data['status_created_at'] = Carbon::now();
                $record->update([
                    'status_created_at' => $data['status_created_at'],
                    'acceptance_status' => $data['acceptance_status'],
                ]);
            }
        } catch (\Throwable $e) {
            throw $e;
        }

        return $record;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function sendEmailInformation($record, $data): void
    {
        $dataRecord = $record;
        $email = $record->email;
        $dataSend = $data;
        $response = Mail::to($email)->send(new SendInterviewResult($dataRecord, $dataSend));
    }
}
