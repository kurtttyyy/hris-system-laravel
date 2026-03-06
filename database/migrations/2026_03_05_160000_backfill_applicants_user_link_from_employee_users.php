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
        if (!Schema::hasTable('users') || !Schema::hasTable('applicants')) {
            return;
        }

        $users = DB::table('users')
            ->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
            ->whereNotNull('email')
            ->whereRaw("TRIM(email) <> ''")
            ->select(['id', 'email', 'first_name', 'last_name'])
            ->orderBy('id')
            ->get();

        foreach ($users as $user) {
            $applicant = DB::table('applicants')
                ->where('user_id', $user->id)
                ->orderByDesc('id')
                ->first();

            if (!$applicant) {
                $applicant = DB::table('applicants')
                    ->whereNull('user_id')
                    ->whereRaw('LOWER(TRIM(email)) = ?', [strtolower(trim((string) $user->email))])
                    ->orderByDesc('id')
                    ->first();
            }

            if (!$applicant) {
                continue;
            }

            $payload = [
                'user_id' => $user->id,
                'email' => $user->email,
            ];

            if (trim((string) ($user->first_name ?? '')) !== '') {
                $payload['first_name'] = $user->first_name;
            }

            if (trim((string) ($user->last_name ?? '')) !== '') {
                $payload['last_name'] = $user->last_name;
            }

            DB::table('applicants')
                ->where('id', $applicant->id)
                ->update($payload);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback for one-time backfill.
    }
};

