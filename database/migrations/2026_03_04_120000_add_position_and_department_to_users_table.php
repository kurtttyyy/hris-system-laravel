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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable()->after('job_role');
            }

            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('position');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'department')) {
                $table->dropColumn('department');
            }

            if (Schema::hasColumn('users', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
};

