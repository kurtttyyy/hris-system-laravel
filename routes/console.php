<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Support\EmployeeAccountStatusManager;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('employee-status:sync', function () {
    $count = app(EmployeeAccountStatusManager::class)->syncAllEmployeeStatuses();
    $this->info("Synced account status for {$count} employee records.");
})->purpose('Sync employee account_status values based on approved leave and resignation records');

Schedule::command('employee-status:sync')->dailyAt('00:05');
