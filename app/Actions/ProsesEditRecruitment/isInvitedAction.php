<?php

namespace App\Actions\ProsesEditRecruitment;

use App\Mail\SendMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

class isInvitedAction
{
    use AsAction;

    public function handle($record, array $data)
    {
        // dd("oke");
        try {
            DB::beginTransaction();
            // dd($data['is_invited'] == true);
            if ($record->interview) {
                // dd("1");
                $record->interview->update($data);
            } else {
                // dd($record);
                $record->interview()->create($data);
            }
            if($data['is_invited'] == true){
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
        $response = Mail::to($email)->send(new SendMail($dataRecord, $dataSend));
        // dd($response);
    }
}
