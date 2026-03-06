<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('users') || !Schema::hasTable('applicants') || !Schema::hasTable('open_positions')) {
            return;
        }

        $openPositionId = DB::table('open_positions')
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->value('id');

        if (!$openPositionId) {
            return;
        }

        $users = DB::table('users')
            ->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
            ->select(['id', 'first_name', 'last_name', 'email'])
            ->orderBy('id')
            ->get();

        foreach ($users as $user) {
            $linkedApplicant = DB::table('applicants')
                ->where('user_id', $user->id)
                ->orderByDesc('id')
                ->first();

            if ($linkedApplicant) {
                continue;
            }

            $email = trim((string) ($user->email ?? ''));

            $emailMatchedApplicant = null;
            if ($email !== '') {
                $emailMatchedApplicant = DB::table('applicants')
                    ->whereNull('user_id')
                    ->whereRaw('LOWER(TRIM(email)) = ?', [strtolower($email)])
                    ->orderByDesc('id')
                    ->first();
            }

            if ($emailMatchedApplicant) {
                DB::table('applicants')
                    ->where('id', $emailMatchedApplicant->id)
                    ->update([
                        'user_id' => $user->id,
                        'first_name' => trim((string) ($user->first_name ?? '')) !== '' ? $user->first_name : $emailMatchedApplicant->first_name,
                        'last_name' => trim((string) ($user->last_name ?? '')) !== '' ? $user->last_name : $emailMatchedApplicant->last_name,
                        'email' => $email !== '' ? $email : $emailMatchedApplicant->email,
                        'updated_at' => now(),
                    ]);
                continue;
            }

            $safeEmail = $email !== '' ? $email : ('employee-'.$user->id.'@placeholder.local');

            DB::table('applicants')->insert([
                'user_id' => $user->id,
                'open_position_id' => (int) $openPositionId,
                'first_name' => trim((string) ($user->first_name ?? '')) !== '' ? $user->first_name : 'Employee',
                'last_name' => trim((string) ($user->last_name ?? '')) !== '' ? $user->last_name : ('#'.$user->id),
                'email' => $safeEmail,
                'phone' => null,
                'address' => null,
                'bachelor_degree' => null,
                'bachelor_school_name' => null,
                'bachelor_year_finished' => null,
                'master_degree' => null,
                'master_school_name' => null,
                'master_year_finished' => null,
                'doctoral_degree' => null,
                'doctoral_school_name' => null,
                'doctoral_year_finished' => null,
                'field_study' => '-',
                'university_address' => '-',
                'work_position' => '-',
                'work_employer' => '-',
                'work_location' => '-',
                'work_duration' => '-',
                'date_hired' => null,
                'experience_years' => '0',
                'skills_n_expertise' => '-',
                'starRatings' => null,
                'application_status' => 'Hired',
                'fresh_graduate' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback for one-time data backfill.
    }
};

