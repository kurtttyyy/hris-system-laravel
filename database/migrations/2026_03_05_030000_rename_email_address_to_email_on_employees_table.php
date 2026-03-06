<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('employees')) {
            return;
        }

        $hasEmail = Schema::hasColumn('employees', 'email');
        $hasEmailAddress = Schema::hasColumn('employees', 'email_address');

        if (!$hasEmail && $hasEmailAddress) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('email')->nullable()->after('employee_id');
            });
        }

        if (Schema::hasColumn('employees', 'email') && Schema::hasColumn('employees', 'email_address')) {
            DB::table('employees')
                ->whereNull('email')
                ->update(['email' => DB::raw('email_address')]);

            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('email_address');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('employees')) {
            return;
        }

        $hasEmail = Schema::hasColumn('employees', 'email');
        $hasEmailAddress = Schema::hasColumn('employees', 'email_address');

        if (!$hasEmailAddress && $hasEmail) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('email_address')->nullable()->after('employee_id');
            });
        }

        if (Schema::hasColumn('employees', 'email_address') && Schema::hasColumn('employees', 'email')) {
            DB::table('employees')
                ->whereNull('email_address')
                ->update(['email_address' => DB::raw('email')]);

            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }
};

