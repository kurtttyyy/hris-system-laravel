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
            $table->string('bachelor_degree')->nullable()->after('address');
            $table->string('master_degree')->nullable()->after('bachelor_degree');
            $table->string('doctoral_degree')->nullable()->after('master_degree');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn(['bachelor_degree', 'master_degree', 'doctoral_degree']);
        });
    }
};
