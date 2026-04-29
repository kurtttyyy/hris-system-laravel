<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        foreach ($this->defaultAccounts() as $account) {
            $values = [
                'first_name' => $account['first_name'],
                'middle_name' => $account['middle_name'],
                'last_name' => $account['last_name'],
                'role' => 'Admin',
                'job_role' => 'Administrator',
                'position' => 'Administrator',
                'department' => 'Human Resources',
                'department_head' => null,
                'status' => 'Approved',
                'account_status' => 'Active',
                'password' => Hash::make($account['password']),
                'updated_at' => $now,
            ];

            $query = DB::table('users')->where('email', $account['email']);

            if ((clone $query)->exists()) {
                $query->update($values);
                continue;
            }

            DB::table('users')->insert($values + [
                'email' => $account['email'],
                'created_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        // Do not delete real admin accounts on rollback.
    }

    private function defaultAccounts(): array
    {
        return [
            [
                'email' => 'demo.admin@example.com',
                'password' => 'Demo12345',
                'first_name' => 'Demo',
                'middle_name' => 'Account',
                'last_name' => 'Admin',
            ],
            [
                'email' => 'kurtrobin20031118@gmail.com',
                'password' => 'Kurt12345',
                'first_name' => 'Kurt',
                'middle_name' => 'Admin',
                'last_name' => 'Robin',
            ],
        ];
    }
};
