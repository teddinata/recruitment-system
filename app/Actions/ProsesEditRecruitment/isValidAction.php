<?php

namespace App\Actions\ProsesEditRecruitment;

use App\Mail\SendMail;
use App\Mail\SendMailNotInvited;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

class isValidAction
{
    use AsAction;

    public function handle($record, array $data)
    {
        try {
            DB::beginTransaction();

            $record->update($data);
            if($data['is_valid'] == "no"){
                $this->sendEmailNotInvited($record, $data);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }

        return $record;

    }
    // protected function sendEmailInformation($record, $data): void
    // {
    //     $name = $record->first_name;
    //     $body = 'untuk testing';
    //     $mailMassage = "Sehubungan dengan kelanjutan proses seleksi, bersama ini kami mengundang Anda untuk mengikuti proses selanjutnya yang akan diadakan pada ";
    //     $email = $record->email;
    //     $dataSend = $data;
    //     $response = Mail::to($email)->send(new SendMail($mailMassage, $dataSend));
    //     dd($response);
    // }
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
            // 'is_invited' => $data['is_invited'],
        ];
        $response = Mail::to($email)->send(new SendMailNotInvited($dataRecord, $dataSend));
        // dd($response);
    }
}
