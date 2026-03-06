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
        if (!Schema::hasColumn('employees', 'service_record_rows')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->json('service_record_rows')->nullable()->after('job_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('employees', 'service_record_rows')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('service_record_rows');
            });
        }
    }
};

