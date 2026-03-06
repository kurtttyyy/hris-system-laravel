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
        if (!Schema::hasTable('applicants')) {
            return;
        }

        if (!Schema::hasColumn('applicants', 'benefit')) {
            Schema::table('applicants', function (Blueprint $table) {
                $table->text('benefit')->nullable()->after('skills_n_expertise');
            });
        }

        if (!Schema::hasTable('open_positions') || !Schema::hasColumn('open_positions', 'benifits')) {
            return;
        }

        $rows = DB::table('applicants as a')
            ->join('open_positions as op', 'op.id', '=', 'a.open_position_id')
            ->select(['a.id as applicant_id', 'a.benefit as current_benefit', 'op.benifits as position_benefit'])
            ->get();

        foreach ($rows as $row) {
            $current = trim((string) ($row->current_benefit ?? ''));
            $fromPosition = trim((string) ($row->position_benefit ?? ''));
            if ($current !== '' || $fromPosition === '') {
                continue;
            }

            DB::table('applicants')
                ->where('id', $row->applicant_id)
                ->update([
                    'benefit' => $fromPosition,
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('applicants')) {
            return;
        }

        if (Schema::hasColumn('applicants', 'benefit')) {
            Schema::table('applicants', function (Blueprint $table) {
                $table->dropColumn('benefit');
            });
        }
    }
};

