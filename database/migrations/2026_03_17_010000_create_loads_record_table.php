<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loads_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loads_upload_id')->constrained('loads_upload')->cascadeOnDelete();
            $table->unsignedInteger('row_no');
            $table->json('payload')->nullable();
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();

            $table->index(['loads_upload_id', 'row_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loads_record');
    }
};
