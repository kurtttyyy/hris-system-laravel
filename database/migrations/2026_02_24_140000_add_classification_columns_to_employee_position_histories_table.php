<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_position_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_position_histories', 'old_classification')) {
                $table->string('old_classification')->nullable()->after('new_position');
            }
            if (!Schema::hasColumn('employee_position_histories', 'new_classification')) {
                $table->string('new_classification')->nullable()->after('old_classification');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employee_position_histories', function (Blueprint $table) {
            if (Schema::hasColumn('employee_position_histories', 'new_classification')) {
                $table->dropColumn('new_classification');
            }
            if (Schema::hasColumn('employee_position_histories', 'old_classification')) {
                $table->dropColumn('old_classification');
            }
        });
    }
};

