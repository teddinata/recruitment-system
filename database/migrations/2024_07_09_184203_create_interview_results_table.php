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
        Schema::create('interview_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('interview_id');
            // $table->unsignedBigInteger('user_apply_job_id');
            $table->enum('is_success', ['yes', 'no'])->nullable();
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_results');
    }
};
