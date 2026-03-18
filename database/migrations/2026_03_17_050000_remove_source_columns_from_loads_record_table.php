<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loads_record', function (Blueprint $table) {
            $table->dropIndex(['source_file_path']);
            $table->dropColumn(['source_file_name', 'source_file_path']);
        });
    }

    public function down(): void
    {
        Schema::table('loads_record', function (Blueprint $table) {
            $table->string('source_file_name')->nullable()->after('id');
            $table->string('source_file_path')->nullable()->after('source_file_name');
            $table->index(['source_file_path']);
        });
    }
};
