<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('attendance_records', 'is_holiday_present')) {
            return;
        }

        Schema::table('attendance_records', function (Blueprint $table) {
            $table->boolean('is_holiday_present')
                ->default(false)
                ->after('is_tardy');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('attendance_records', 'is_holiday_present')) {
            return;
        }

        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropColumn('is_holiday_present');
        });
    }
};
