<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserApplyJob;
use Inertia\Inertia;

class TrackingController extends Controller
{
    //
    public function show($tracking_code)
    {
        // Temukan aplikasi berdasarkan tracking code
        $application = UserApplyJob::where('tracking_code', $tracking_code)->with('jobVacancy')->first();

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
                'created_at' => $application->created_at->format('d F Y H:i:s')
            ],
            'trackingBaseUrl' => url('/workflow/rekrutmen/tracker/')
        ]);
    }
}
