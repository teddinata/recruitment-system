<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all()->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'image' => asset('storage/' . $category->image),
                'jobs_count' => $category->jobs->count(),
            ];
        });

        $jobVacancies = JobVacancy::where('enable', true)->orderBy('created_at', 'desc')->get()->map(function($job) {
            return [
                'id' => $job->id,
                'title' => $job->title,
                'image' => asset('storage/' . $job->image),
                'work_hours' => $job->work_hours,
                'location' => $job->location,
                'created_at_human' => $job->created_at->diffForHumans(),
            ];
        });

        return Inertia::render('Home', [
            'categories' => $categories,
            'jobVacancies' => $jobVacancies,
            'sliderImage' => asset('frontend-new/img/viscus-team-1.png'),
            'applyBgImage' => asset('frontend-new/img/gallery/how-applybg.png'),
        ]);
    }

    // function job listing
    public function jobListing(Request $request)
    {
        $categories = Category::all();
        $jobVacanciesQuery = JobVacancy::where('enable', true);

        // filter by category
        if ($request->category_id) {
            $jobVacanciesQuery->where('category_id', $request->category_id);
        }

        // filter by experience checkbox
        if ($request->filled('experience')) {
            $jobVacanciesQuery->whereIn('experience', $request->experience);
        }

        // Sort by
        if ($request->filled('sort_by')) {
            $sort_by = $request->sort_by;
            if ($sort_by == 'experience' || $sort_by == 'salary') {
                $jobVacanciesQuery->orderBy($sort_by, 'desc');
            } else {
                $jobVacanciesQuery->orderBy($sort_by, 'asc');
            }
        } else {
            $jobVacanciesQuery->orderBy('created_at', 'desc');
        }

        // filter by post within 24 hours, 3 days, 7 days
        if ($request->post) {
            if ($request->post == '24hours') {
                $jobVacanciesQuery->where('created_at', '>=', now()->subDay());
            } elseif ($request->post == '3days') {
                $jobVacanciesQuery->where('created_at', '>=', now()->subDays(3));
            } elseif ($request->post == '7days') {
                $jobVacanciesQuery->where('created_at', '>=', now()->subDays(7));
            } elseif ($request->post == '30days') {
                $jobVacanciesQuery->where('created_at', '>=', now()->subDays(30));
            }
        }

        $jobVacancies = $jobVacanciesQuery->paginate(5);

        // Add URLs for images and convert the collection
        $jobVacancies->getCollection()->transform(function($job) {
            return [
                'id' => $job->id,
                'title' => $job->title,
                'image' => Storage::url($job->image),
                'work_hours' => $job->work_hours,
                'location' => $job->location,
                'created_at_human' => $job->created_at->diffForHumans(),
            ];
        });

        return Inertia::render('JobListing', [
            'jobVacancies' => $jobVacancies,
            'categories' => $categories,
            'sliderImage' => asset('frontend-new/img/viscus-team-1.png'),
            'applyBgImage' => asset('frontend-new/img/gallery/how-applybg.png'),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
            'description' => $job->description,
            'qualifications' => $job->qualifications,
            'work_hours' => $job->work_hours,
            'created_at_formatted' => $job->created_at->format('d M Y'),
            'application_deadline' => $job->created_at->addDays(30)->format('d M Y'),
        ];

        return Inertia::render('JobDetail', [
            'job' => $jobData,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
