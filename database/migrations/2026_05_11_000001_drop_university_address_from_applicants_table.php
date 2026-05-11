<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('applicants', 'university_address')) {
            Schema::table('applicants', function (Blueprint $table) {
                $table->dropColumn('university_address');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('applicants', 'university_address')) {
            Schema::table('applicants', function (Blueprint $table) {
                $table->string('university_address')->nullable()->after('field_study');
            });
        }
    }
};
