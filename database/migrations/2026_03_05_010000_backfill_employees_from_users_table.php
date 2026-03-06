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
        if (!Schema::hasTable('users') || !Schema::hasTable('employees')) {
            return;
        }

        $now = now();

        $users = DB::table('users')
            ->leftJoin('employees', 'employees.user_id', '=', 'users.id')
            ->whereNull('employees.id')
            ->whereRaw("LOWER(TRIM(COALESCE(users.role, ''))) = ?", ['employee'])
            ->select([
                'users.id',
                'users.created_at',
                'users.department',
                'users.position',
                'users.job_role',
            ])
            ->orderBy('users.id')
            ->get();

        foreach ($users as $user) {
            DB::table('employees')->insert([
                'user_id' => $user->id,
                'employee_id' => 'EMP-'.str_pad((string) $user->id, 5, '0', STR_PAD_LEFT),
                'employement_date' => !empty($user->created_at)
                    ? \Illuminate\Support\Carbon::parse($user->created_at)->toDateString()
                    : $now->toDateString(),
                'birthday' => $now->copy()->subYears(18)->toDateString(),
                'account_number' => 'N/A',
                'sex' => 'Unspecified',
                'civil_status' => 'Single',
                'contact_number' => 'N/A',
                'address' => 'N/A',
                'department' => trim((string) ($user->department ?? '')) !== '' ? $user->department : 'Unassigned',
                'position' => trim((string) ($user->position ?? '')) !== ''
                    ? $user->position
                    : (trim((string) ($user->job_role ?? '')) !== '' ? $user->job_role : 'Employee'),
                'classification' => 'Probationary',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left blank to avoid deleting possibly edited employee records.
    }
};

