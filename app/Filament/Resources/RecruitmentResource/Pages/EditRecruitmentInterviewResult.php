<?php

namespace App\Filament\Resources\RecruitmentResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use App\Enums\InterviewType;
use App\Mail\SendMailSuccess;
use App\Enums\StageRecruitment;
use App\Enums\StatusRecruitment;
use App\Mail\SendMailNotInvited;
use App\Mail\SendMailNotSuccess;
use App\Mail\SendInterviewResult;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\RecruitmentResource;
use App\Actions\ProsesEditRecruitment\isSuccessAction;

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

                $this->sendEmailResult($record, $data);
            } else {
                $data['acceptance_status'] = StatusRecruitment::FAILED->value;
                $data['status_created_at'] = Carbon::now();
                $record->update([
                    'status_created_at' => $data['status_created_at'],
                    'acceptance_status' => $data['acceptance_status'],
                ]);
                $this->sendEmailNotResult($record, $data);
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

    protected function sendEmailResult($record, $data): void
    {
        
        $dataRecord = $record;
        $email = $record->email;
        $dataSend = $data;
        $response = Mail::to($email)->send(new SendMailSuccess($dataRecord, $dataSend));
    }

    protected function sendEmailNotResult($record, $data): void
    {
        $dataRecord = $record;
        $email = $record->email;
        $dataSend = [
            'first_name' => $record->first_name,
            'last_name' => $record->last_name,
            'job_title' => $record->job_title,
            'email' => $record->email,
            // Any additional data you might need
            // 'is_invited' => $data['is_invited'],
        ];
        Mail::to($email)->send(new SendMailNotSuccess($dataRecord, $dataSend));
        // dd($response);
    }
}
