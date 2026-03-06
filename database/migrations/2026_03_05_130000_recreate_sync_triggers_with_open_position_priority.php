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
        if (!Schema::hasTable('users') || !Schema::hasTable('employees') || !Schema::hasTable('applicants') || !Schema::hasTable('open_positions')) {
            return;
        }

        $driver = DB::connection()->getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_users_after_insert_sync_employee');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_users_after_update_sync_employee');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_employees_after_insert_sync_user');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_employees_after_update_sync_user');

        DB::unprepared("
            CREATE TRIGGER trg_users_after_insert_sync_employee
            AFTER INSERT ON users
            FOR EACH ROW
            BEGIN
                IF LOWER(TRIM(COALESCE(NEW.role, ''))) = 'employee' AND COALESCE(@sync_origin, '') <> 'employees' THEN
                    SET @sync_origin = 'users';

                    SET @op_department = (
                        SELECT NULLIF(TRIM(op.department), '')
                        FROM applicants a
                        JOIN open_positions op ON op.id = a.open_position_id
                        WHERE a.user_id = NEW.id
                          AND a.deleted_at IS NULL
                          AND op.deleted_at IS NULL
                        ORDER BY a.id DESC
                        LIMIT 1
                    );

                    SET @op_title = (
                        SELECT NULLIF(TRIM(op.title), '')
                        FROM applicants a
                        JOIN open_positions op ON op.id = a.open_position_id
                        WHERE a.user_id = NEW.id
                          AND a.deleted_at IS NULL
                          AND op.deleted_at IS NULL
                        ORDER BY a.id DESC
                        LIMIT 1
                    );

                    IF NOT EXISTS (SELECT 1 FROM employees WHERE user_id = NEW.id) THEN
                        INSERT INTO employees (
                            user_id,
                            employee_id,
                            email,
                            employement_date,
                            birthday,
                            account_number,
                            sex,
                            civil_status,
                            contact_number,
                            address,
                            department,
                            position,
                            classification,
                            created_at,
                            updated_at
                        ) VALUES (
                            NEW.id,
                            CONCAT('EMP-', LPAD(NEW.id, 5, '0')),
                            NULLIF(TRIM(NEW.email), ''),
                            COALESCE(DATE(NEW.created_at), CURDATE()),
                            DATE_SUB(CURDATE(), INTERVAL 18 YEAR),
                            'N/A',
                            'Unspecified',
                            'Single',
                            'N/A',
                            'N/A',
                            COALESCE(@op_department, NULLIF(TRIM(NEW.department), ''), NULL),
                            COALESCE(@op_title, NULLIF(TRIM(NEW.position), ''), NULLIF(TRIM(NEW.job_role), ''), NULL),
                            'Probationary',
                            NOW(),
                            NOW()
                        );
                    END IF;

                    SET @sync_origin = NULL;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_users_after_update_sync_employee
            AFTER UPDATE ON users
            FOR EACH ROW
            BEGIN
                IF LOWER(TRIM(COALESCE(NEW.role, ''))) = 'employee' AND COALESCE(@sync_origin, '') <> 'employees' THEN
                    SET @sync_origin = 'users';

                    SET @op_department = (
                        SELECT NULLIF(TRIM(op.department), '')
                        FROM applicants a
                        JOIN open_positions op ON op.id = a.open_position_id
                        WHERE a.user_id = NEW.id
                          AND a.deleted_at IS NULL
                          AND op.deleted_at IS NULL
                        ORDER BY a.id DESC
                        LIMIT 1
                    );

                    SET @op_title = (
                        SELECT NULLIF(TRIM(op.title), '')
                        FROM applicants a
                        JOIN open_positions op ON op.id = a.open_position_id
                        WHERE a.user_id = NEW.id
                          AND a.deleted_at IS NULL
                          AND op.deleted_at IS NULL
                        ORDER BY a.id DESC
                        LIMIT 1
                    );

                    IF EXISTS (SELECT 1 FROM employees WHERE user_id = NEW.id) THEN
                        UPDATE employees
                        SET
                            email = COALESCE(NULLIF(TRIM(NEW.email), ''), email),
                            department = COALESCE(@op_department, NULLIF(TRIM(NEW.department), ''), NULL),
                            position = COALESCE(@op_title, NULLIF(TRIM(NEW.position), ''), NULLIF(TRIM(NEW.job_role), ''), NULL),
                            updated_at = NOW()
                        WHERE user_id = NEW.id;
                    ELSE
                        INSERT INTO employees (
                            user_id,
                            employee_id,
                            email,
                            employement_date,
                            birthday,
                            account_number,
                            sex,
                            civil_status,
                            contact_number,
                            address,
                            department,
                            position,
                            classification,
                            created_at,
                            updated_at
                        ) VALUES (
                            NEW.id,
                            CONCAT('EMP-', LPAD(NEW.id, 5, '0')),
                            NULLIF(TRIM(NEW.email), ''),
                            COALESCE(DATE(NEW.created_at), CURDATE()),
                            DATE_SUB(CURDATE(), INTERVAL 18 YEAR),
                            'N/A',
                            'Unspecified',
                            'Single',
                            'N/A',
                            'N/A',
                            COALESCE(@op_department, NULLIF(TRIM(NEW.department), ''), NULL),
                            COALESCE(@op_title, NULLIF(TRIM(NEW.position), ''), NULLIF(TRIM(NEW.job_role), ''), NULL),
                            'Probationary',
                            NOW(),
                            NOW()
                        );
                    END IF;

                    SET @sync_origin = NULL;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_employees_after_insert_sync_user
            AFTER INSERT ON employees
            FOR EACH ROW
            BEGIN
                IF COALESCE(@sync_origin, '') <> 'users' THEN
                    SET @sync_origin = 'employees';

                    UPDATE users
                    SET
                        email = COALESCE(NULLIF(TRIM(NEW.email), ''), email),
                        department = COALESCE(NULLIF(TRIM(NEW.department), ''), department),
                        position = COALESCE(NULLIF(TRIM(NEW.position), ''), position)
                    WHERE id = NEW.user_id
                      AND LOWER(TRIM(COALESCE(role, ''))) = 'employee';

                    SET @sync_origin = NULL;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_employees_after_update_sync_user
            AFTER UPDATE ON employees
            FOR EACH ROW
            BEGIN
                IF COALESCE(@sync_origin, '') <> 'users' THEN
                    SET @sync_origin = 'employees';

                    UPDATE users
                    SET
                        email = COALESCE(NULLIF(TRIM(NEW.email), ''), email),
                        department = COALESCE(NULLIF(TRIM(NEW.department), ''), department),
                        position = COALESCE(NULLIF(TRIM(NEW.position), ''), position)
                    WHERE id = NEW.user_id
                      AND LOWER(TRIM(COALESCE(role, ''))) = 'employee';

                    SET @sync_origin = NULL;
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_users_after_insert_sync_employee');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_users_after_update_sync_employee');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_employees_after_insert_sync_user');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_employees_after_update_sync_user');
    }
};

