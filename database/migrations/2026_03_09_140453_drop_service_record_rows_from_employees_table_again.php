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
        if (!Schema::hasTable('employees') || !Schema::hasColumn('employees', 'service_record_rows')) {
            return;
        }

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('service_record_rows');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback implementation: intentionally removed per current data model.
    }
};
