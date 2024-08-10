<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Enums\InterviewType;
use App\Models\UserApplyJob;
use Illuminate\Http\Request;
use App\Enums\StatusRecruitment;

class TrackingController extends Controller
{
    //
    public function show($tracking_code)
    {
        // dd(StatusRecruitment::class);
        // Temukan aplikasi berdasarkan tracking code
        $application = UserApplyJob::where('tracking_code', $tracking_code)->with(['jobVacancy', 'interview', 'interviewResult'])->first();
        $userInterview = $application->interview->where('interview_type', InterviewType::USER->getLabel())->first();
        $hrInterview = $application->interview->where('interview_type', InterviewType::HR->getLabel())->first();
        // dd(Carbon::parse($application->valid_created_at)->format('d F Y H:i:s'));
        if($userInterview){
            $userInterviewResult = $userInterview->interviewResult()->first();
        }else{
            $userInterviewResult = null;
        }
        if($hrInterview){
            $hrInterviewResult = $hrInterview->interviewResult()->first();
        }else{
            $hrInterviewResult = null;
        }
        // Jika aplikasi tidak ditemukan, kembalikan halaman 404
        if (!$application) {
            abort(404, 'Application not found.');
        }

        // Kembalikan tampilan dengan data aplikasi Iniertia
        return Inertia::render('Tracking', [
            'application' => [
                'id' => $application->id,
                'first_name' => $application->first_name,
                'last_name' => $application->last_name,
                'email' => $application->email,
                'phone_number' => $application->phone_number,
                'address' => $application->address,
                'place_of_birth' => $application->place_of_birth,
                'date_of_birth' => $application->date_of_birth,
                'education' => $application->education,
                'major' => $application->major,
                'join_date' => $application->join_date,
                'linkedin_url' => $application->linkedin_url,
                'job_source' => $application->job_source,
                'old_company' => $application->old_company,
                'self_description' => $application->self_description,
                'tracking_code' => $application->tracking_code,
                'title' => $application->jobVacancy->title,
                'is_valid' => $application->is_valid,
                'acceptance_status' => $application->acceptance_status->value,
                'diterima' => StatusRecruitment::ACCEPTED->value,
                'ditolak' => StatusRecruitment::REJECTED->value,
                'status_created_at' => ($application->status_created_at ?? null !== null) ? (Carbon::parse($application->status_created_at)->format('d M, Y \p\u\k\u\l H:i')) : null,
                'valid_created_at' => ($application->valid_created_at ?? null !== null) ? (Carbon::parse($application->valid_created_at)->format('d M, Y \p\u\k\u\l H:i')) : null,
                'created_at' => $application->created_at->format('d M, Y \p\u\k\u\l H:i'),

                'user_interview' => [
                    'id' => $userInterview->id ?? null,
                    'user_apply_job_id' => $userInterview->user_apply_job_id ?? null,
                    'interview_date' => $userInterview->interview_date ?? null,
                    'google_meet_link' => $userInterview->google_meet_link ?? null,
                    'notes' => $userInterview->notes ?? null,
                    'is_invited' => $userInterview->is_invited ?? null,
                    'interview_type' => $userInterview->interview_type ?? null,
                    'created_at' => ($userInterview->created_at ?? null !== null) ? $userInterview->created_at->format('d M, Y \p\u\k\u\l H:i') : null,
                ],

                'hr_interview' => [
                    'id' => $hrInterview->id ?? null,
                    'user_apply_job_id' => $hrInterview->user_apply_job_id ?? null,
                    'interview_date' => $hrInterview->interview_date ?? null,
                    'google_meet_link' => $hrInterview->google_meet_link ?? null,
                    'notes' => $hrInterview->notes ?? null,
                    'is_invited' => $hrInterview->is_invited ?? null,
                    'interview_type' => $hrInterview->interview_type ?? null,
                    'created_at' => ($hrInterview->created_at ?? null !== null) ? $hrInterview->created_at->format('d M, Y \p\u\k\u\l H:i'): null,
                ],

                'user_interview_result' => [
                    'id' => $userInterviewResult,
                    'interview_id' => $userInterviewResult,
                    'is_success' => $userInterviewResult,
                    'review' => $userInterviewResult,
                    'created_at' => ($userInterviewResult->created_at?? null !== null) ? $userInterviewResult->created_at->format('d M, Y \p\u\k\u\l H:i') : null,
                ],
                'hr_interview_result' => [
                    'id' => $hrInterviewResult,
                    'interview_id' => $hrInterviewResult,
                    'is_success' => $hrInterviewResult,
                    'review' => $hrInterviewResult,
                    'created_at' => ($hrInterviewResult->created_at?? null !== null) ? $hrInterviewResult->created_at->format('d M, Y \p\u\k\u\l H:i') : null,
                ]
            ],
            'trackingBaseUrl' => url('/workflow/rekrutmen/tracker/')
        ]);
    }
}
