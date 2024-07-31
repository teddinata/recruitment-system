<?php

namespace App\Actions\ProsesEditRecruitment;

use App\Mail\SendMail;
use App\Mail\SendMailNotInvited;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

class isInvitedAction
{
    use AsAction;

    public function handle($record, array $data)
    {
        try {
            DB::beginTransaction();
            // dd($data);

            if ($record->interview->count() != 0) {
                $cek = $record->interview->contains(function ($interview) use ($data) {
                    return $interview->type === $data['interview_type'];
                });
                if($cek){
                    $cek->update($data);
                }else{
                    $record->interview()->create([
                        'is_invited' => $data['is_invited'],
                        'interview_date' => $data['interview_date'],
                        'google_meet_link' => $data['google_meet_link'],
                        'notes' => $data['notes'],
                        'interview_type' => $data['interview_type']
                    ]);
                }
            } else {
                $record->interview()->create([
                    'is_invited' => $data['is_invited'],
                    'interview_date' => $data['interview_date'],
                    'google_meet_link' => $data['google_meet_link'],
                    'notes' => $data['notes'],
                    'interview_type' => $data['interview_type']
                ]);
            }
            if ($data['is_invited'] == "yes") {
                // $this->sendEmailInformation($record, $data);
            } else {
                // dd
                // send email not invited
                $this->sendEmailNotInvited($record, $data);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }

        return $record;
    }
    protected function sendEmailInformation($record, $data): void
    {
        $dataRecord = $record;
        $email = $record->email;
        $dataSend = $data;
        $response = Mail::to($email)->send(new SendMail($dataRecord, $dataSend));
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
        $response = Mail::to($email)->send(new SendMailNotInvited($dataRecord, $dataSend));
        // dd($response);
    }
}
