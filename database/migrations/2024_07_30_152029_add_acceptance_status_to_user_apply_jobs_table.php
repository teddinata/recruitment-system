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
            $table->enum('acceptance_status', [
                'accepted',
                'rejected',
                'pending',
                'failed'
                ])->default('pending')->after('is_valid');
            $table->timestamp('status_created_at')->nullable()->after('acceptance_status');
            $table->timestamp('valid_created_at')->nullable()->after('is_valid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_apply_jobs', function (Blueprint $table) {
            $table->dropColumn('acceptance_status');
            $table->dropColumn('status_created_at');
            $table->dropColumn('valid_created_at');
        });
    }
};
