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
        Schema::table('user_apply_jobs', function (Blueprint $table) {
            $table->enum('current_stage', [
                'document_screening',
                'document_screening(completed)',
                'user_interview',
                'user_interview(completed)',
                'hr_interview',
                'hr_interview(completed)',
                'final_decision',
                'final_decision(completed)',
                ])->default('document_screening')->after('acceptance_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_apply_jobs', function (Blueprint $table) {
            $table->dropColumn('current_stage');
        });
    }
};
