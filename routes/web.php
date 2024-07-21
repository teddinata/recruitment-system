<?php

use App\Http\Controllers\ApplyJobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrackingController;

Route::get('/', function () {
    return view('home');
});

// route home use HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');

// route job listing use HomeController
Route::get('/job-listing', [HomeController::class, 'jobListing'])->name('job-listing');

// route resource job vacancies
Route::resource('job-vacancies', HomeController::class);

// route apply job use HomeController
Route::post('/job-vacancies/{id}/apply', [ApplyJobController::class, 'store'])->name('apply-job.store');
Route::get('/job-vacancies/{id}/apply-job', [ApplyJobController::class, 'index'])->name('apply-job');

Route::get('/workflow/rekrutmen/tracker/{tracking_code}', [TrackingController::class, 'show'])->name('tracking-status');
Route::get('/send-mail', function(){
    return view("emails.send-mail");
});
