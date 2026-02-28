<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payslip_records', function (Blueprint $table) {
            $table->date('pay_date')->nullable()->after('pay_date_text');
        });
    }

    public function down(): void
    {
        Schema::table('payslip_records', function (Blueprint $table) {
            $table->dropColumn('pay_date');
        });
    }
};

