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
        if (!Schema::hasColumn('employees', 'email_address')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('email_address')->nullable()->after('employee_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('employees', 'email_address')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('email_address');
            });
        }
    }
};

