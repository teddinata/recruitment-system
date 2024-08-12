<?php

namespace App\Filament\Resources\RecruitmentResource\Pages;

use Carbon\Carbon;
use App\Enums\InterviewType;
use App\Mail\SendMailInvited;
use App\Enums\StageRecruitment;
use App\Enums\StatusRecruitment;
use App\Mail\SendMailNotInvited;
use App\Mail\SendMailNotInvitedHR;
use App\Mail\SendMailNotInvitedUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\RecruitmentResource;

class EditRecruitmentInterview extends EditRecord
{
    protected static string $resource = RecruitmentResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {

            if ($record->interview->count() != 0) {
                $cek = $record->interview->contains(function ($interview) use ($data) {
                    return $interview->type === $data['interview_type'];
                });
                if ($cek) {
                    $cek->update($data);
                } else {
                    $record->interview()->create($data);
                }
            } else {
                $record->interview()->create($data);
            }
            if ($data['is_invited'] == "yes") {

                if ($data['interview_type'] == InterviewType::USER->getLabel()) {
                    $data['acceptance_status'] = StatusRecruitment::PENDING->value;
                    $data['current_stage'] = StageRecruitment::UI->value;
                    $record->update([
                        'current_stage' => $data['current_stage'],
                        'acceptance_status' => $data['acceptance_status'],
                    ]);
                } else {
                    $data['acceptance_status'] = StatusRecruitment::PENDING->value;
                    $data['current_stage'] = StageRecruitment::HI->value;
                    $record->update([
                        'current_stage' => $data['current_stage'],
                        'acceptance_status' => $data['acceptance_status'],
                    ]);
                }
                $this->sendEmailInvited($record, $data);
            } else {
                $data['acceptance_status'] = StatusRecruitment::FAILED->value;
                $data['status_created_at'] = Carbon::now();
                $record->update([
                    'status_created_at' => $data['status_created_at'],
                    'acceptance_status' => $data['acceptance_status'],
                ]);
                $this->sendEmailNotInvited($record, $data);
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

    protected function sendEmailInvited($record, $data): void
    {
        $dataRecord = $record;
        $email = $record->email;
        $dataSend = $data;
        $response = Mail::to($email)->send(new SendMailInvited($dataRecord, $dataSend));
        // dd($response);
    }

    protected function sendEmailNotInvited($record, $data): void
    {
        $dataRecord = $record;
        $email = $record->email;
        $dataSend = [
            'first_name' => $record->first_name,
            'last_name' => $record->last_name,
            'job_title' => $record->job_title,
            'email' => $record->email,
            // Any additional data you might need
            'is_invited' => $data['is_invited'],
        ];
        if ($data['interview_type'] == InterviewType::USER->value) {
            $response = Mail::to($email)->send(new SendMailNotInvitedUser($dataRecord, $dataSend));
        } else if ($data['interview_type'] == InterviewType::HR->value) {
            $response = Mail::to($email)->send(new SendMailNotInvitedHR($dataRecord, $dataSend));
        }
        // dd($response);
    }
}
