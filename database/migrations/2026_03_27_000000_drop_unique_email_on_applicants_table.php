<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            try {
                $table->dropUnique('applicants_email_unique');
            } catch (\Throwable $e) {
                // The unique index may already be missing in some environments.
            }
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            try {
                $table->unique('email');
            } catch (\Throwable $e) {
                // Re-adding can fail if duplicate emails already exist after the feature is used.
            }
        });
    }
};
