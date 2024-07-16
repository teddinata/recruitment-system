@extends('layouts.frontend')

@section('title', 'Job Listing')

@section('content')

        <!-- Hero Area End -->
        <!-- Job List Area Start -->
        <form action="{{ route('job-listing') }}" method="GET">
        <div class="job-listing-area pt-120 pb-120">
            <div class="container">
                <div class="row">
                    <!-- Left content -->
                    <div class="col-xl-3 col-lg-3 col-md-4">
                        <div class="row">
                            <div class="col-12">
                                    <div class="small-section-tittle2 mb-45">
                                    <div class="ion"> <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="20px" height="12px">
                                    <path fill-rule="evenodd"  fill="rgb(27, 207, 107)"
                                        d="M7.778,12.000 L12.222,12.000 L12.222,10.000 L7.778,10.000 L7.778,12.000 ZM-0.000,-0.000 L-0.000,2.000 L20.000,2.000 L20.000,-0.000 L-0.000,-0.000 ZM3.333,7.000 L16.667,7.000 L16.667,5.000 L3.333,5.000 L3.333,7.000 Z"/>
                                    </svg>
                                    </div>
                                    <h4>Filter Jobs</h4>
                                </div>
                            </div>
                        </div>
                        <!-- Job Category Listing start -->
                            <div class="job-category-listing mb-50">
                                <!-- single one -->
                                <div class="single-listing">
                                    <div class="small-section-tittle2">
                                        <h4>Job Category</h4>
                                    </div>
                                    <!-- Select job items start -->
                                    <div class="select-job-items2">
                                        <select name="category_id">
                                            <option value="">Any Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- select-Categories End -->
                                </div>
                                <!-- single two -->
                                <div class="single-listing">
                                    <!--  Select job items End-->
                                    <!-- select-Categories start -->
                                    <div class="select-Categories pt-80 pb-50">
                                        <div class="small-section-tittle2">
                                            <h4>Experience</h4>
                                        </div>
                                        <label class="container {{ request('experience') && in_array('Junior', request('experience')) ? 'active' : '' }}">Junior (1-2 Years)
                                            <input type="checkbox" name="experience[]" value="Junior" {{ request('experience') && in_array('Junior', request('experience')) ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="container {{ request('experience') && in_array('Intermediate', request('experience')) ? 'active' : '' }}">Intermediate (2-3 Years)
                                            <input type="checkbox" name="experience[]" value="Intermediate" {{ request('experience') && in_array('Intermediate', request('experience')) ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="container {{ request('experience') && in_array('Senior', request('experience')) ? 'active' : '' }}">Senior (3-6 Years)
                                            <input type="checkbox" name="experience[]" value="Senior" {{ request('experience') && in_array('Senior', request('experience')) ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                        </label>

                                    </div>

                                    <!-- select-Categories End -->
                                </div>
                                <!-- single three -->
                                <div class="single-listing">
                                    <!-- select-Categories start -->
                                    <div class="select-Categories pb-50">
                                        <div class="small-section-tittle2">
                                            <h4>Posted Within</h4>
                                        </div>
                                        <label class="container">Any
                                            <input type="radio" name="post" value="0" {{ request('post') == '0' ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="container">Today
                                            <input type="radio" name="post" value="24hours" {{ request('post') == '24hours' ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="container">Last 3 days
                                            <input type="radio" name="post" value="3days" {{ request('post') == '3days' ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="container">Last 7 days
                                            <input type="radio" name="post" value="7days" {{ request('post') == '7days' ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="container">Last 30 days
                                            <input type="radio" name="post" value="30days" {{ request('post') == '30days' ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <!-- select-Categories End -->
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn mr-2">Filter</button>
                                <a href="{{ route('job-listing') }}" class="btn btn-secondary" style="background: #000">
                                    Reset Filter</a>
                            </div>

                        <!-- Job Category Listing End -->
                    </div>
                    <!-- Right content -->
                    <div class="col-xl-9 col-lg-9 col-md-8">
                        <!-- Featured_job_start -->
                        <section class="featured-job-area">
                            <div class="container">
                                <!-- Count of Job list Start -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="count-job mb-35">
                                            <span>{{ $jobVacancies->count() }} Jobs found</span>
                                            <!-- Select job items start -->
                                            <div class="select-job-items">
                                                <span>Sort by</span>
                                                <select name="sort_by">
                                                    <option value="">None</option>
                                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Newest</option>
                                                    <option value="experience" {{ request('sort_by') == 'experience' ? 'selected' : '' }}>Experience</option>
                                                    <option value="salary" {{ request('sort_by') == 'salary' ? 'selected' : '' }}>Salary</option>
                                                </select>
                                            </div>
                                            <!--  Select job items End-->
                                        </div>
                                    </div>
                                </div>

                                <!-- Count of Job list End -->
                                @forelse($jobVacancies as $jobVacancy)
                                <!-- single-job-content -->
                                <div class="single-job-items mb-30">
                                    <div class="job-items">
                                        <div class="company-img">
                                            <a href="{{ route('job-vacancies.show', $jobVacancy->id) }}">
                                                <img src="{{ Storage::url($jobVacancy->image) ?? 'https://via.placeholder.com/150' }}"
                                                alt="{{ $jobVacancy->image ? '' : $jobVacancy->title[0] }}"
                                                style="width: 100px; height: 100px;">

                                            </a>
                                        </div>
                                        <div class="job-tittle job-tittle2">
                                            <a href="{{ route('job-vacancies.show', $jobVacancy->id) }}">
                                                <h4>{{ $jobVacancy->title }}</h4>
                                            </a>
                                            <ul>
                                                <li>{{ strtoupper($jobVacancy->experience) }}</li>
                                                <li><i class="fas fa-map-marker-alt"></i>{{ $jobVacancy->location }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="items-link items-link2 f-right">
                                        <a href="{{ route('job-vacancies.show', $jobVacancy->id) }}">{{ $jobVacancy->work_hours }}</a>
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
                        </section>
                        <!-- Featured_job_end -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Job List Area End -->
        <!--Pagination Start  -->
        <div class="pagination-area pb-115 text-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="single-wrap d-flex justify-content-center">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-start">
                                    <!-- Previous Page Link -->
                                    @if ($jobVacancies->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link"><span class="ti-angle-left"></span></span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $jobVacancies->previousPageUrl() }}" rel="prev"><span class="ti-angle-left"></span></a>
                                        </li>
                                    @endif

                                    <!-- Pagination Elements -->
                                    @foreach ($jobVacancies->getUrlRange(1, $jobVacancies->lastPage()) as $page => $url)
                                        @if ($page == $jobVacancies->currentPage())
                                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach

                                    <!-- Next Page Link -->
                                    @if ($jobVacancies->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $jobVacancies->nextPageUrl() }}" rel="next"><span class="ti-angle-right"></span></a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link"><span class="ti-angle-right"></span></span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </form>
        <!--Pagination End  -->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryFilter = document.getElementById('categoryFilter');
        const locationFilter = document.getElementById('locationFilter');
        const jobTypeFilter = document.querySelectorAll('.jobTypeFilter');
        const experienceFilter = document.querySelectorAll('.experienceFilter');
        const postedWithinFilter = document.querySelectorAll('.postedWithinFilter');

        function applyFilters() {
            const selectedCategory = categoryFilter.value;
            const selectedLocation = locationFilter.value;
            const selectedJobTypes = Array.from(jobTypeFilter).filter(input => input.checked).map(input => input.value);
            const selectedExperience = Array.from(experienceFilter).filter(input => input.checked).map(input => input.value);
            const selectedPostedWithin = Array.from(postedWithinFilter).filter(input => input.checked).map(input => input.value);

            // Lakukan sesuatu dengan pilihan filter yang dipilih
            console.log("Selected Category:", selectedCategory);
            console.log("Selected Location:", selectedLocation);
            console.log("Selected Job Types:", selectedJobTypes);
            console.log("Selected Experience:", selectedExperience);
            console.log("Selected Posted Within:", selectedPostedWithin);
        }

        categoryFilter.addEventListener('change', applyFilters);
        locationFilter.addEventListener('change', applyFilters);
        jobTypeFilter.forEach(input => input.addEventListener('change', applyFilters));
        experienceFilter.forEach(input => input.addEventListener('change', applyFilters));
        postedWithinFilter.forEach(input => input.addEventListener('change', applyFilters));
    });
</script>
@endpush
