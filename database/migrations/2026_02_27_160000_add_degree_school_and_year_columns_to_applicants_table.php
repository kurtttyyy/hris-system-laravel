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
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('bachelor_school_name')->nullable()->after('bachelor_degree');
            $table->string('bachelor_year_finished')->nullable()->after('bachelor_school_name');
            $table->string('master_school_name')->nullable()->after('master_degree');
            $table->string('master_year_finished')->nullable()->after('master_school_name');
            $table->string('doctoral_school_name')->nullable()->after('doctoral_degree');
            $table->string('doctoral_year_finished')->nullable()->after('doctoral_school_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'bachelor_school_name',
                'bachelor_year_finished',
                'master_school_name',
                'master_year_finished',
                'doctoral_school_name',
                'doctoral_year_finished',
            ]);
        });
    }
};
