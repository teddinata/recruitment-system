@extends('layouts.frontend')

@section('title', 'Apply Job')

@section('content')
<div class="job-post-company mb-5">
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
                                <li>{{ strtoupper($job->experience) }}</li>
                                <li><i class="fas fa-map-marker-alt"></i> {{ $job->location }}</li>
                                <li><i class="fas fa-industry"></i> {{ $job->category->name }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- job single End -->

                <div class="card p-4">
                    <h3 class="text-center mb-4">Apply for Job</h3>
                    <form action="{{ route('apply-job.store', $job->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="first_name" class="col-md-3 col-form-label">First Name</label>
                            <div class="col-md-9">
                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="last_name" class="col-md-3 col-form-label">Last Name</label>
                            <div class="col-md-9">
                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="EMAIL" class="col-md-3 col-form-label">Email Address</label>
                            <div class="col-md-9">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-md-3 col-form-label">Phone</label>
                            <div class="col-md-9">
                                <input type="text" name="phone_number" id="phone" class="form-control" placeholder="Phone" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="old_company" class="col-md-3 col-form-label">Current Company</label>
                            <div class="col-md-9">
                                <input type="text" name="old_company" id="old_company" class="form-control" placeholder="Current Company" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date_of_birth" class="col-md-3 col-form-label">Date of Birth</label>
                            <div class="col-md-9">
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" placeholder="Date of Birth" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="place_of_birth" class="col-md-3 col-form-label">Place of Birth</label>
                            <div class="col-md-9">
                                <input type="text" name="place_of_birth" id="place_of_birth" class="form-control" placeholder="Place of Birth" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="education" class="col-md-3 col-form-label">Last Education</label>
                            <div class="col-md-9">
                                <select name="education" id="education" class="form-control">
                                    <option value="">Last Education</option>
                                    <option value="SMA">SMA</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="major" class="col-md-3 col-form-label">Major</label>
                            <div class="col-md-9">
                                <input type="text" name="major" id="major" class="form-control" placeholder="Major" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Gender</label>
                            <div class="col-md-9">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="0">
                                    <label class="form-check-label" for="male">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="1">
                                    <label class="form-check-label" for="female">
                                        Female
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label for="join_date" class="col-md-3 col-form-label">Join Date</label>
                            <div class="col-md-9">
                                <div class="input-group-icon mt-10">
                                    <div class="form-select select-scrollable" id="default-select">
                                        <select name="join_date" id="join_date" class="form-control">
                                            <option value="">Join Date</option>
                                            @for ($i = 1; $i <= 31; $i++)
                                                <option value="{{ $i }}">{{ $i }} Hari</option>
                                            @endfor
                                        </select>
                                        <span class="mb-4">Hari setelah diterima</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-md-3 col-form-label">Current Address</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="address" id="address" rows="3" placeholder="Current Address" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="self_description" class="col-md-3 col-form-label">Self Description</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="self_description" id="self_description" rows="3" placeholder="Self Description" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="linkedin_url" class="col-md-3 col-form-label">LinkedIn URL</label>
                            <div class="col-md-9">
                                <input type="text" name="linkedin_url" id="linkedin_url" class="form-control" placeholder="LinkedIn URL" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="job_source" class="col-md-3 col-form-label">Job Source</label>
                            <div class="col-md-9">
                                <select name="job_source" id="job_source" class="form-control">
                                    <option value="">Where did you find this job?</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="Twitter">Twitter</option>
                                    <option value="Linkedin">Linkedin</option>
                                    <option value="Jobstreet">Jobstreet</option>
                                    <option value="Karir.com">Karir.com</option>
                                    <option value="Jobindo.com">Jobindo.com</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cv" class="col-md-3 col-form-label">Upload CV</label>
                            <div class="col-md-9">
                                <input type="file" name="cv_path" id="cv" class="form-control" required>
                                <span class="text-danger">Only PDF format with a maximum size of 2MB</span>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Submit Application</button>
                        </div>
                    </form>
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
               </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: 1px solid #e2e2e2;
        border-radius: 5px;
        padding: 20px;
    }

    .form-group label {
        font-weight: bold;
    }
    .select-scrollable {
        /* max-height: 1000px !important; */
        /* overflow-x: auto; */
    }


    .form-group textarea,
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="file"],
    .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        margin-top: 6px;
        margin-bottom: 16px;
        resize: vertical;
    }

    .form-group input[type="radio"],
    .form-group input[type="checkbox"] {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .form-group.text-center {
        margin-top: 20px;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

</style>
@endpush
