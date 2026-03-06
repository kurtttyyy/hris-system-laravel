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
                            COALESCE(NULLIF(TRIM(NEW.department), ''), 'Unassigned'),
                            COALESCE(NULLIF(TRIM(NEW.position), ''), NULLIF(TRIM(NEW.job_role), ''), 'Employee'),
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

                    IF EXISTS (SELECT 1 FROM employees WHERE user_id = NEW.id) THEN
                        UPDATE employees
                        SET
                            email = COALESCE(NULLIF(TRIM(NEW.email), ''), email),
                            department = COALESCE(NULLIF(TRIM(NEW.department), ''), department, 'Unassigned'),
                            position = COALESCE(NULLIF(TRIM(NEW.position), ''), NULLIF(TRIM(NEW.job_role), ''), position, 'Employee'),
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
                            COALESCE(NULLIF(TRIM(NEW.department), ''), 'Unassigned'),
                            COALESCE(NULLIF(TRIM(NEW.position), ''), NULLIF(TRIM(NEW.job_role), ''), 'Employee'),
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

