<?php

namespace App\Actions\ProsesEditRecruitment;

use App\Mail\SendMail;
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
            // if($data['is_valid'] == "yes"){
            //     $this->sendEmailInformation($record, $data);
            // }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }

        return $record;
        
    }
    protected function sendEmailInformation($record, $data): void
    {
        $name = $record->first_name;
        $body = 'untuk testing';
        $mailMassage = "Sehubungan dengan kelanjutan proses seleksi, bersama ini kami mengundang Anda untuk mengikuti proses selanjutnya yang akan diadakan pada ";
        $email = $record->email;
        $dataSend = $data;
        $response = Mail::to($email)->send(new SendMail($mailMassage, $dataSend));
        dd($response);
    }
}
