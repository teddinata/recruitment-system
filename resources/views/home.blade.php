@extends('layouts.frontend')

@section('title', 'Home')
@section('content')


<!-- slider Area Start-->
<div class="slider-area ">
    <!-- Mobile Menu -->
    <div class="slider-active">
        <div class="single-slider slider-height d-flex align-items-center"
            data-background="{{ asset('frontend-new/img/viscus-team-1.png') }}">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-9 col-md-10">
                        <div class="hero__caption">
                            <h1>creating stunning & engaging online experiences</h1>
                        </div>
                    </div>
                </div>
                <!-- Search Box -->
                <div class="row">
                    <div class="col-xl-8">
                        <!-- form -->
                        {{-- <form action="#" class="search-box">
                            <div class="input-form">
                                <input type="text" placeholder="Job Tittle or keyword">
                            </div>
                            <div class="select-form">
                                <div class="select-itms">
                                    <select name="select" id="select1">
                                        <option value="">Location BD</option>
                                        <option value="">Location PK</option>
                                        <option value="">Location US</option>
                                        <option value="">Location UK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="search-form">
                                <a href="#">Find job</a>
                            </div>
                        </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- slider Area End-->
<!-- Our Services Start -->
<div class="our-services section-pad-t30">
    <div class="container">
        <!-- Section Tittle -->
        <div class="row">
            <div class="col-lg-12">
                <div class="section-tittle text-center">
                    <h2>Browse Top Categories </h2>
                </div>
            </div>
        </div>
        <div class="row d-flex justify-contnet-center">
            @foreach($categories as $category)
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                <div class="single-services text-center mb-30">
                    <div class="services-ion">
                        {{-- <span class="flaticon-tour"></span> --}}
                        <img src="{{ Storage::url($category->image) }}" alt="" style="width: 100px; height: 100px;">
                    </div>
                    <div class="services-cap">
                       <h5><a href="{{ route('job-listing') }}">{{ $category->name }}</a></h5>
                        <span>{{ $category->jobs->count() }} Jobs</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <!-- More Btn -->
        <!-- Section Button -->
        <div class="row">
            <div class="col-lg-12">
                <div class="browse-btn2 text-center mt-50">
                    <a href="{{ route('job-listing') }}" class="border-btn2">Browse All Sectors</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Our Services End -->

<!-- Featured_job_start -->
<section class="featured-job-area pt-10 pb-80">
    <div class="container">
        <!-- Section Tittle -->
        <div class="row">
            <div class="col-lg-12">
                <div class="section-tittle text-center">
                    <span>Recent Job</span>
                    <h2>Featured Jobs</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-10">
                @forelse($jobVacancies as $jobVacancy)
                <!-- single-job-content -->
                <div class="single-job-items mb-30">
                    <div class="job-items">
                        <div class="company-img">
                            <a href="{{ route('job-vacancies.show', $jobVacancy->id) }}">
                                <img src="{{ Storage::url($jobVacancy->image) }}"
                                    alt="" style="width: 100px; height: 100px;">
                        </div>
                        <div class="job-tittle">
                            <a href="{{ route('job-vacancies.show', $jobVacancy->id) }}">
                                <h4>{{ $jobVacancy->title }}</h4>
                            </a>
                            <ul>
                                <li>{{ $jobVacancy->work_hours }}</li>
                                <li><i class="fas fa-map-marker-alt"></i>{{ $jobVacancy->location }}</li>
                                {{-- <li>$3500 - $4000</li> --}}
                            </ul>
                        </div>
                    </div>
                    <div class="items-link f-right">
                        <a href="{{ route('job-vacancies.show', $jobVacancy->id) }}">Full Time</a>
                        <span>{{ $jobVacancy->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="single-job items mb-30">
                    <div class="section-tittle text-center">
                        <h4>No Jobs Available</h4>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
<!-- Featured_job_end -->
<!-- How  Apply Process Start-->
<div class="apply-process-area apply-bg pt-150 pb-150" data-background="{{ asset('frontend-new/img/gallery/how-applybg.png') }}">
    <div class="container">
        <!-- Section Tittle -->
        <div class="row">
            <div class="col-lg-12">
                <div class="section-tittle white-text text-center">
                    <span>Apply process</span>
                    <h2> How it works</h2>
                </div>
            </div>
        </div>
        <!-- Apply Process Caption -->
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="single-process text-center mb-30">
                    <div class="process-ion">
                        <span class="flaticon-search"></span>
                    </div>
                    <div class="process-cap">
                       <h5>1. Search a job</h5>
                       <p>Explore job opportunities tailored to your skills and interests.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single-process text-center mb-30">
                    <div class="process-ion">
                        <span class="flaticon-curriculum-vitae"></span>
                    </div>
                    <div class="process-cap">
                       <h5>2. Apply for job</h5>
                       <p>Apply for the job that fits you best and showcase your qualifications.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single-process text-center mb-30">
                    <div class="process-ion">
                        <span class="flaticon-tour"></span>
                    </div>
                    <div class="process-cap">
                       <h5>3. Get your job</h5>
                       <p>Secure the job you applied for and embark on a new career journey.</p>
                    </div>
                </div>
            </div>
        </div>
     </div>
</div>

@endsection
