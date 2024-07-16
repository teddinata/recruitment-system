<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_apply_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_vacancy_id')->constrained('job_vacancies');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('education')->nullable();
            $table->string('major')->nullable();
            $table->string('join_date')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('job_source')->nullable();
            $table->string('old_company')->nullable();

            $table->text('self_description')->nullable();

            $table->boolean('gender')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('tracking_code')->nullable();
            $table->enum('is_valid', ['yes', 'no'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_apply_jobs');
    }
};
