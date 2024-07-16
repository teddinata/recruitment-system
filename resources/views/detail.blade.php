@extends('layouts.frontend')

@section('title', 'Detail Job' . $job->title)

@section('content')
<div class="job-post-company">
    <div class="container">
        <div class="row justify-content-between">
            <!-- Left Content -->
            <div class="col-xl-7 col-lg-8">
                <!-- job single -->
                <div class="single-job-items mb-50">
                    <div class="job-items">
                        <div class="company-img company-img-details">
                            <a href="#">
                                <img src="{{ Storage::url($job->image) }}" alt="" style="width: 100px; height: 100px;">
                            </a>
                        </div>
                        <div class="job-tittle">
                            <a href="#">
                                <h4>{{ $job->title }}</h4>
                            </a>
                            <ul>
                                <li>{{ $job->category->name }}</li>
                                <li><i class="fas fa-map-marker-alt"></i>{{ $job->location }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                  <!-- job single End -->

                <div class="job-post-details">
                    <div class="post-details1 mb-50">
                        <!-- Small Section Tittle -->
                        <div class="small-section-tittle">
                            <h4>Job Description</h4>
                        </div>
                        {!! $job->description !!}
                    </div>
                    <div class="post-details2  mb-50">
                         <!-- Small Section Tittle -->
                        <div class="small-section-tittle">
                            <h4>Required Knowledge, Skills, and Abilities</h4>
                        </div>
                        <p>
                            {!! $job->qualifications !!}
                        </p>
                    </div>
                </div>

            </div>
            <!-- Right Content -->
            <div class="col-xl-4 col-lg-4">
                <div class="post-details3  mb-50">
                    <!-- Small Section Tittle -->
                   <div class="small-section-tittle">
                       <h4>Job Overview</h4>
                   </div>
                  <ul>
                      <li>Posted date : <span>{{ $job->created_at->format('d M Y') }}</li></span>
                      <li>Location : <span>{{ $job->location }}</span></li>
                      <li>Job nature : <span>{{ $job->work_hours }}</span></li>
                      <li>Application date : <span>{{ $job->created_at->addDays(30)->format('d M Y') }}</span></li>
                  </ul>
                 <div class="apply-btn2">
                    <a href="{{ route('apply-job', $job->id) }}"
                    class="btn">Apply Now</a>
                 </div>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ $errors->first() }}',
        });
    @endif
</script>

@endpush
