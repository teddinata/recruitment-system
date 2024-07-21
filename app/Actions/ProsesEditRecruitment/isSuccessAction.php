<?php

namespace App\Actions\ProsesEditRecruitment;

use App\Mail\SendInterviewResult;
use App\Mail\SendMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

class isSuccessAction
{
    use AsAction;

    public function handle($record, array $data)
    {
        try {
            DB::beginTransaction();

            if ($record->interview->interviewResult) {
                $record->interview->interviewResult->update($data);
            } else {
                $record->interview->interviewResult()->create($data);
            }
            if($data['is_success'] == true){
                $this->sendEmailInformation($record, $data);
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
        $response = Mail::to($email)->send(new SendInterviewResult($dataRecord, $dataSend));
        dd($response);
    }
}
