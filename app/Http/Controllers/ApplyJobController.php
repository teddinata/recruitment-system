<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;
use RealRashid\SweetAlert\Facades\Alert;
// validate request
use Illuminate\Support\Facades\Validator;
use App\Models\UserApplyJob;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;


class ApplyJobController extends Controller
{
    public function index($id)
    {
        $job = JobVacancy::with('category')->findOrFail($id);

        // Format the data as needed
        $jobData = [
            'id' => $job->id,
            'title' => $job->title,
            'image' => asset('storage/' . $job->image),
            'location' => $job->location,
            'category' => [
                'name' => $job->category->name,
            ],
            'experience' => $job->experience,
            'work_hours' => $job->work_hours,
            'created_at_formatted' => $job->created_at->format('d M Y'),
            'application_deadline' => $job->created_at->addDays(30)->format('d M Y'),
        ];

        return Inertia::render('ApplyJob', [
            'job' => $jobData,
            'csrf_token' => csrf_token(),
        ]);
    }

    public function store(Request $request, $job_vacancy_id)
    {
        // Mendefinisikan aturan validasi
        $rules = [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'phone_number'      => 'required|string|max:20',
            'address'           => 'required|string',
            'place_of_birth'    => 'required|string|max:255',
            'date_of_birth'     => 'required|date',
            'education'         => 'required|string|max:50',
            'major'             => 'required|string|max:255',
            'join_date'         => 'required|integer|min:1|max:31',
            'linkedin_url'      => 'required|url',
            'job_source'        => 'required|string|max:50',
            'old_company'       => 'required|string|max:255',
            'self_description'  => 'required|string',
            'gender'            => 'required|in:0,1',
            'cv_path'           => 'required|file|mimes:pdf|max:2048',
        ];

        // Membuat validator
        $validator = Validator::make($request->all(), $rules);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Mengembalikan respons JSON dengan pesan error jika validasi gagal
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Menyiapkan data untuk disimpan
        $data = $validator->validated();

        // Handle file upload
        if ($request->hasFile('cv_path')) {
            $file = $request->file('cv_path');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs('cv', $file, $filename);
            $data['cv_path'] = $filePath;
        }

        // Menyimpan data aplikasi
        try {
            $applyJob = new UserApplyJob($data);
            $applyJob->job_vacancy_id = $job_vacancy_id;
            $applyJob->save();
        } catch (\Exception $e) {
            // Handle kesalahan saat menyimpan data
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Mengembalikan respons sukses
        // return redirect()->route('apply-job', $job_vacancy_id)->with('success', 'Your application has been submitted successfully.');
        // return response()->json([
        //     'message' => 'Your application has been submitted successfully.',
        //     'tracking_code' => $applyJob->tracking_code,
        //     'job_title' => $applyJob->jobVacancy->title,
        //     'applicant_name' => $applyJob->first_name . ' ' . $applyJob->last_name,
        //     // 'tracking_url' => route('tracking-status', $applyJob->tracking_code),
        // ]);
        return redirect()->route('tracking-status', $applyJob->tracking_code)->with([
            'success' => 'Your application has been submitted successfully.',
            'tracking_code' => $applyJob->tracking_code,
            'job_title' => $applyJob->jobVacancy->title,
            'applicant_name' => $applyJob->first_name . ' ' . $applyJob->last_name,
            // 'tracking_url' => route('tracking-status', $tracking_code),
        ]);
    }



    // public function store(Request $request, $job_vacancy_id)
    // {

    //     $dataValid = $request->validate([
    //         'first_name'                => 'nullable',
    //         'last_name'                 => 'nullable',
    //         'email'                     => 'nullable',
    //         'phone_number'              => 'nullable',
    //         'address'                   => 'nullable',
    //         'place_of_birth'            => 'nullable',
    //         'date_of_birth'             => 'nullable',
    //         'education'                 => 'nullable',
    //         'major'                     => 'nullable',
    //         'join_date'                 => 'nullable',
    //         'linkedin_url'              => 'nullable',
    //         'job_source'                => 'nullable',
    //         'old_company'               => 'nullable',
    //         'self_description'          => 'nullable',
    //         'gender'                    => 'nullable',
    //         'cv_path'                   => 'nullable|mimes:pdf|max:2048',
    //     ]);

    //     // upload file
    //     if ($request->hasFile('cv_path')) {
    //         $file = $request->file('cv_path')->store('cv');
    //     }

    //     // create data
    //     $dataValid['job_vacancy_id'] = $job_vacancy_id;
    //     $dataValid['cv_path'] = $file;

    //     // save data
    //     $job = JobVacancy::find($job_vacancy_id);

    //     // use new method
    //     $applyJob = new UserApplyJob([
    //         'job_vacancy_id' => $job_vacancy_id,
    //         'first_name' => $request->first_name,
    //         'last_name' => $request->last_name,
    //         'email' => $request->email,
    //         'phone_number' => $request->phone_number,
    //         'address' => $request->address,
    //         'place_of_birth' => $request->place_of_birth,
    //         'date_of_birth' => $request->date_of_birth,
    //         'education' => $request->education,
    //         'major' => $request->major,
    //         'join_date' => $request->join_date,
    //         'linkedin_url' => $request->linkedin_url,
    //         'job_source' => $request->job_source,
    //         'old_company' => $request->old_company,
    //         'self_description' => $request->self_description,
    //         'cv_path' => $file,
    //     ]);

    //     $job->userApplyJobs()->save($applyJob);

    //     // Tampilkan sweet alert
    //     Alert::success('Lamaran Anda Berhasil Dikirim!', 'Silahkan Tunggu Konfirmasi selanjutnya.');

    //     // return with sweet alert
    //     return redirect()->route('job-vacancies.show', $job_vacancy_id);

    // }

    // public function store(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255',
    //         'phone_number' => 'required|string|max:20',
    //         'old_company' => 'required|string|max:255',
    //         'date_of_birth' => 'required|date',
    //         'place_of_birth' => 'required|string|max:255',
    //         'education' => 'required|string|max:50',
    //         'major' => 'required|string|max:255',
    //         'gender' => 'required|in:0,1',
    //         'join_date' => 'required|integer|min:1|max:31',
    //         'address' => 'required|string',
    //         'self_description' => 'required|string',
    //         'linkedin_url' => 'required|url',
    //         'job_source' => 'required|string|max:50',
    //         'cv_path' => 'required|file|mimes:pdf|max:2048',
    //     ]);

    //     // Handle file upload
    //     if ($request->hasFile('cv_path')) {
    //         $file = $request->file('cv_path')->store('cvs', 'public');
    //         $validated['cv_path'] = $file;
    //     }

    //     // Save the job application
    //     $application = new UserApplyJob($validated);
    //     $application->job_vacancy_id = $id;
    //     $application->save();

    //     return redirect()->route('job-detail', $id)->with('success', 'Your application has been submitted successfully.');
    // }

}
