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
        if (!Schema::hasTable('employees') || !Schema::hasTable('applicants')) {
            return;
        }

        $isMeaningful = static function (?string $value): bool {
            $text = trim((string) ($value ?? ''));
            if ($text === '') {
                return false;
            }
            $lower = strtolower($text);
            return !in_array($lower, ['n/a', '-', 'na'], true);
        };

        $rows = DB::table('employees as e')
            ->join('applicants as a', 'a.user_id', '=', 'e.user_id')
            ->select([
                'a.id as applicant_id',
                'a.phone as applicant_phone',
                'a.address as applicant_address',
                'a.work_position as applicant_work_position',
                'e.contact_number as employee_contact_number',
                'e.address as employee_address',
                'e.position as employee_position',
            ])
            ->orderBy('a.id')
            ->get();

        foreach ($rows as $row) {
            $updates = [];

            $appPhone = trim((string) ($row->applicant_phone ?? ''));
            if ($appPhone === '' && $isMeaningful($row->employee_contact_number)) {
                $updates['phone'] = trim((string) $row->employee_contact_number);
            }

            $appAddress = trim((string) ($row->applicant_address ?? ''));
            if ($appAddress === '' && $isMeaningful($row->employee_address)) {
                $updates['address'] = trim((string) $row->employee_address);
            }

            $appWorkPosition = trim((string) ($row->applicant_work_position ?? ''));
            if (($appWorkPosition === '' || in_array(strtolower($appWorkPosition), ['n/a', '-', 'na'], true))
                && $isMeaningful($row->employee_position)) {
                $updates['work_position'] = trim((string) $row->employee_position);
            }

            if (!empty($updates)) {
                $updates['updated_at'] = now();
                DB::table('applicants')->where('id', $row->applicant_id)->update($updates);
            }
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

