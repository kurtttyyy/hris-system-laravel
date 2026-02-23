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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('open_position_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('education_attainment');
            $table->string('field_study');
            $table->string('university_name');
            $table->string('university_address');
            $table->string('year_complete');
            $table->string('work_position');
            $table->string('work_employer');
            $table->string('work_location');
            $table->string('work_duration');
            $table->date('date_hired')->nullable();
            $table->string('experience_years');
            $table->string('skills_n_expertise');
            $table->string('starRatings')->nullable();
            $table->string('application_status')->default('Under Review');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('open_position_id')->references('id')->on('open_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
