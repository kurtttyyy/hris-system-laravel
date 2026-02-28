<?php

namespace App\Http\Controllers;

use App\Models\AttendanceUpload;
use App\Models\AttendanceRecord;
use App\Models\Applicant;
use App\Models\Employee;
use App\Models\GuestLog;
use App\Models\Interviewer;
use App\Models\OpenPosition;
use App\Models\PayslipRecord;
use App\Models\PayslipUpload;
use App\Models\LeaveApplication;
use App\Models\Resignation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdministratorPageController extends Controller
{
    private ?array $hiddenOfficialHolidayDatesCache = null;
    private ?array $calendarHolidayConfigCache = null;
    private array $holidayDateCheckCache = [];

    public function display_home(){
        $employee = User::query()
                        ->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
                        ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['pending'])
                        ->latest()
                        ->get();
        $accept = User::with([
            'employee',
            'applicant',
            'applicant.position:id,department',
        ])->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
                        ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['approved'])
                        ->latest()
                        ->get();
        
        // Get department overview
        $departments = User::with('employee')
                        ->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
                        ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['approved'])
                        ->get()
                        ->groupBy(function($user) {
                            return $user->employee->department ?? 'Unassigned';
                        })
                        ->map(function($group) {
                            return [
                                'name' => $group->first()->employee->department ?? 'Unassigned',
                                'count' => $group->count()
                            ];
                        })
                        ->values();

        $totalEmployeeCount = User::query()
            ->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
            ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['approved'])
            ->count();

        $today = now();
        $currentMonthStart = (clone $today)->startOfMonth();
        $currentRangeEnd = (clone $today)->endOfDay();

        $previousMonthReference = (clone $today)->subMonthNoOverflow();
        $previousMonthStart = (clone $previousMonthReference)->startOfMonth();
        $sameDayLastMonth = min(
            (int) $today->day,
            (int) $previousMonthReference->daysInMonth
        );
        $previousRangeEnd = (clone $previousMonthStart)
            ->addDays($sameDayLastMonth - 1)
            ->endOfDay();

        // "Applied" employees are based on account creation date.
        $employeesThisMonth = User::query()
            ->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
            ->whereBetween('created_at', [$currentMonthStart, $currentRangeEnd])
            ->count();

        $employeesLastMonth = User::query()
            ->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
            ->whereBetween('created_at', [$previousMonthStart, $previousRangeEnd])
            ->count();

        if ($employeesLastMonth > 0) {
            $monthlyEmployeePercentChange = (($employeesThisMonth - $employeesLastMonth) / $employeesLastMonth) * 100;
        } elseif ($employeesThisMonth > 0) {
            $monthlyEmployeePercentChange = 100;
        } else {
            $monthlyEmployeePercentChange = 0;
        }
        $monthlyEmployeePercentChange = round($monthlyEmployeePercentChange, 1);

        $todayDate = now()->toDateString();
        $isTodaySundayNoClass = $this->isSundayDate($todayDate);
        $isTodayHoliday = $this->isHolidayDate($todayDate);

        $presentTodayCount = 0;
        if (!$isTodaySundayNoClass) {
            $todayRecords = AttendanceRecord::query()
                ->whereDate('attendance_date', $todayDate)
                ->orderByDesc('id')
                ->get()
                ->filter(function ($row) {
                    return $this->normalizeEmployeeId($row->employee_id) !== '';
                })
                ->unique(function ($row) {
                    return $this->normalizeEmployeeId($row->employee_id);
                })
                ->values();

            if ($isTodayHoliday && $todayRecords->isEmpty()) {
                // On holidays with no uploads, treat approved employees as present for the day.
                $presentTodayCount = $totalEmployeeCount;
            } else {
                $presentTodayCount = $todayRecords
                    ->filter(function ($row) use ($isTodayHoliday) {
                        $hasAnyTimeLog = !empty($row->morning_in)
                            || !empty($row->morning_out)
                            || !empty($row->afternoon_in)
                            || !empty($row->afternoon_out);
                        $isHolidayPresent = (bool) ($row->is_holiday_present ?? false);
                        $mainGate = strtolower(trim((string) ($row->main_gate ?? '')));
                        $isWithPayLeave = $mainGate === 'leave - with pay';
                        return !(bool) ($row->is_absent ?? false)
                            && ($hasAnyTimeLog || $isHolidayPresent || $isTodayHoliday || $isWithPayLeave);
                    })
                    ->count();
            }
        }

        $presentTodayRate = $totalEmployeeCount > 0
            ? round(($presentTodayCount / $totalEmployeeCount) * 100, 1)
            : 0;

        $approvedLeaveToday = LeaveApplication::query()
            ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['approved'])
            ->orderByDesc('created_at')
            ->get()
            ->filter(function ($application) use ($todayDate) {
                $startDate = $application->filing_date
                    ? Carbon::parse($application->filing_date)->startOfDay()
                    : Carbon::parse($application->created_at)->startOfDay();
                $days = (float) ($application->number_of_working_days ?? 0);
                if ($days <= 0) {
                    $days = max(
                        (float) ($application->days_with_pay ?? 0),
                        (float) ($application->applied_total ?? 0)
                    );
                }

                $rangeDays = max((int) ceil($days), 1);
                $endDate = $startDate->copy()->addDays($rangeDays - 1);

                return $todayDate >= $startDate->toDateString() && $todayDate <= $endDate->toDateString();
            })
            ->unique(function ($application) {
                $userId = $application->user_id ?? null;
                if (!is_null($userId)) {
                    return 'user:'.$userId;
                }

                return 'name:'.strtolower(trim((string) ($application->employee_name ?? '')));
            })
            ->values();

        $onLeaveTodayCount = (int) $approvedLeaveToday->count();
        $pendingLeaveRequestCount = (int) LeaveApplication::query()
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereRaw("TRIM(status) = ''")
                    ->orWhereRaw("LOWER(TRIM(status)) = ?", ['pending']);
            })
            ->count();
        $pendingLeaveRequestsForHome = LeaveApplication::query()
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereRaw("TRIM(status) = ''")
                    ->orWhereRaw("LOWER(TRIM(status)) = ?", ['pending']);
            })
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $openPositionsCount = OpenPosition::query()->count();
        $openPositionApplicationsCount = Applicant::query()->count();
        
        return view('admin.adminHome', compact(
            'employee',
            'accept',
            'departments',
            'totalEmployeeCount',
            'monthlyEmployeePercentChange',
            'presentTodayCount',
            'presentTodayRate',
            'onLeaveTodayCount',
            'pendingLeaveRequestCount',
            'pendingLeaveRequestsForHome',
            'openPositionsCount',
            'openPositionApplicationsCount'
        ));
    }

    public function display_employee(){
        $employee = User::with([
            'applicant',
            'applicant.documents' => function ($query) {
                $query->select([
                    'id',
                    'applicant_id',
                    'filename',
                    'filepath',
                    'type',
                    'mime_type',
                    'size',
                    'created_at',
                ])
                ->where('type', 'not like', '__REQUIRED__::%')
                ->where('type', '!=', '__NOTICE__')
                ->orderByDesc('created_at');
            },
            'applicant.position:id,title,department,employment,collage_name,work_mode,job_description,responsibilities,requirements,experience_level,location,skills,benifits,job_type,one,two,passionate',
            'employee',
            'education',
            'government',
            'salary',
            'license',
            'resignations' => function ($query) {
                $query
                    ->select([
                        'id',
                        'user_id',
                        'submitted_at',
                        'effective_date',
                        'status',
                        'admin_note',
                        'processed_at',
                        'created_at',
                    ])
                    ->orderByDesc('submitted_at')
                    ->orderByDesc('id');
            },
            'leaveApplications' => function ($query) {
                $query
                    ->select([
                        'id',
                        'user_id',
                        'leave_type',
                        'number_of_working_days',
                        'beginning_vacation',
                        'beginning_sick',
                        'earned_vacation',
                        'earned_sick',
                        'ending_vacation',
                        'ending_sick',
                        'status',
                        'filing_date',
                        'created_at',
                    ])
                    ->orderByDesc('filing_date')
                    ->orderByDesc('id');
            },
            'positionHistories' => function ($query) {
                $query
                    ->select([
                        'id',
                        'user_id',
                        'old_position',
                        'new_position',
                        'old_classification',
                        'new_classification',
                        'changed_by',
                        'changed_at',
                        'note',
                        'created_at',
                    ])
                    ->orderByDesc('changed_at')
                    ->orderByDesc('id');
            },
            ])->where('role','Employee')->get();

        Log::info($employee);
        return view('admin.adminEmployee', compact('employee'));
    }

    public function display_attendance(Request $request){
        return $this->buildAttendanceView($request, 'all');
    }

    public function display_attendance_present(Request $request){
        return $this->buildAttendanceView($request, 'present');
    }

    public function display_attendance_absent(Request $request){
        return $this->buildAttendanceView($request, 'absent');
    }

    public function display_attendance_tardiness(Request $request){
        return $this->buildAttendanceView($request, 'tardiness');
    }

    public function display_attendance_total_employee(Request $request){
        return $this->buildAttendanceView($request, 'total_employee');
    }

    private function buildAttendanceView(Request $request, string $activeAttendanceTab = 'all'){// activeAttendanceTab can be 'all', 'present', 'absent', 'tardiness', 'total_employee'
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $selectedUploadId = $request->query('upload_id');
        $searchName = trim((string) $request->query('search_name', ''));
        if ($searchName === '') {
            $searchName = null;
        }
        $normalizedFromDate = $this->normalizeFilterDate($fromDate);
        $normalizedToDate = $this->normalizeFilterDate($toDate);
        $selectedJobType = $this->normalizeJobType($request->query('job_type'));
        $allowedJobTypes = ['Teaching', 'Non-Teaching'];
        if ($selectedJobType && !in_array($selectedJobType, $allowedJobTypes, true)) {
            $selectedJobType = null;
        }

        $hasDateFilter = (bool) ($normalizedFromDate || $normalizedToDate);
        $exactDateFilter = null;
        $rangeStartDate = null;
        $rangeEndDate = null;

        if ($normalizedFromDate && $normalizedToDate) {
            if ($normalizedFromDate === $normalizedToDate) {
                $exactDateFilter = $normalizedFromDate;
            } elseif ($normalizedFromDate < $normalizedToDate) {
                $rangeStartDate = $normalizedFromDate;
                $rangeEndDate = $normalizedToDate;
            } else {
                $rangeStartDate = $normalizedToDate;
                $rangeEndDate = $normalizedFromDate;
            }
        } elseif ($normalizedFromDate) {
            $exactDateFilter = $normalizedFromDate;
        } elseif ($normalizedToDate) {
            $exactDateFilter = $normalizedToDate;
        }

        $attendanceFiles = AttendanceUpload::query()
            ->when($hasDateFilter, function ($query) use ($exactDateFilter, $rangeStartDate, $rangeEndDate) {
                $query->whereHas('records', function ($recordsQuery) use ($exactDateFilter, $rangeStartDate, $rangeEndDate) {
                    if ($exactDateFilter) {
                        $recordsQuery->whereDate('attendance_date', $exactDateFilter);
                    } elseif ($rangeStartDate && $rangeEndDate) {
                        $recordsQuery->whereDate('attendance_date', '>=', $rangeStartDate)
                            ->whereDate('attendance_date', '<=', $rangeEndDate);
                    }
                });
            })
            ->orderByDesc('uploaded_at')
            ->orderByDesc('id')
            ->get();

        if (!$selectedUploadId && !$hasDateFilter) {
            $selectedUploadId = optional(
                $attendanceFiles->firstWhere('status', 'Processed') ?? $attendanceFiles->first()
            )->id;
        }

        $records = collect();
        if ($hasDateFilter) {
            $recordsQuery = AttendanceRecord::query();
            if ($exactDateFilter) {
                $recordsQuery->whereDate('attendance_date', $exactDateFilter);
            } elseif ($rangeStartDate && $rangeEndDate) {
                $recordsQuery->whereDate('attendance_date', '>=', $rangeStartDate)
                    ->whereDate('attendance_date', '<=', $rangeEndDate);
            }

            $records = $recordsQuery
                ->orderByDesc('attendance_date')
                ->orderBy('employee_id')
                ->get();
        } elseif ($selectedUploadId) {
            $records = AttendanceRecord::query()
                ->where('attendance_upload_id', $selectedUploadId)
                ->orderBy('employee_id')
                ->get();
        }

        // If an official holiday was hidden from the Admin Calendar,
        // do not keep previously generated holiday-present rows for that date.
        $records = collect($records)
            ->filter(function ($row) {
                $isHolidayPresent = (bool) ($row->is_holiday_present ?? false);
                if (!$isHolidayPresent) {
                    return true;
                }

                try {
                    $date = $row->attendance_date
                        ? Carbon::parse($row->attendance_date)->toDateString()
                        : null;
                } catch (\Throwable $e) {
                    $date = null;
                }

                if (!$date) {
                    return true;
                }

                return !$this->isHiddenOfficialHolidayDate($date);
            })
            ->values();

        $employeesWithJobType = Employee::query()
            ->select(['employee_id', 'job_type'])
            ->whereNotNull('employee_id')
            ->orderBy('employee_id')
            ->get();

        $employeeJobTypeMap = $employeesWithJobType
            ->mapWithKeys(function ($employee) {
                $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                if ($employeeId === '') {
                    return [];
                }

                $jobTypeFromEmployee = $this->normalizeJobType($employee->job_type);

                return [$employeeId => $jobTypeFromEmployee];
            });
        $employeeDepartmentMap = Employee::query()
            ->select(['employee_id', 'department'])
            ->whereNotNull('employee_id')
            ->orderBy('employee_id')
            ->get()
            ->mapWithKeys(function ($employee) {
                $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                if ($employeeId === '') {
                    return [];
                }

                return [$employeeId => $employee->department ? (string) $employee->department : null];
            });
        $employeeDisplayNameMap = Employee::query()
            ->with('user:id,first_name,middle_name,last_name')
            ->select(['employee_id', 'user_id'])
            ->whereNotNull('employee_id')
            ->orderBy('employee_id')
            ->get()
            ->mapWithKeys(function ($employee) {
                $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                if ($employeeId === '') {
                    return [];
                }

                return [$employeeId => $this->formatEmployeeDisplayName(
                    $employee->user?->first_name,
                    $employee->user?->middle_name,
                    $employee->user?->last_name
                )];
            });

        $jobTypeOptions = collect($allowedJobTypes);

        $isSundayNoClassDate = $exactDateFilter ? $this->isSundayDate($exactDateFilter) : false;
        $isHolidayDate = $exactDateFilter ? $this->isHolidayDate($exactDateFilter) : false;
        $isSingleSidedDateFilter = ($normalizedFromDate && !$normalizedToDate)
            || (!$normalizedFromDate && $normalizedToDate);
        $shouldAutoPresentHolidayDate = $isHolidayDate
            && !$isSundayNoClassDate
            && !$isSingleSidedDateFilter;

        // No-class Sundays are excluded from attendance counting.
        if ($isSundayNoClassDate) {
            $records = collect();
        } elseif ($shouldAutoPresentHolidayDate) {
            if ($exactDateFilter) {
                $hasAnyRecordForDate = AttendanceRecord::query()
                    ->whereDate('attendance_date', $exactDateFilter)
                    ->exists();

                // Only auto-generate holiday-present rows when there are no records at all for that date.
                if (!$hasAnyRecordForDate) {
                    $this->persistHolidayAttendanceForDate($exactDateFilter, $employeeJobTypeMap);
                }
                $records = $this->getAttendanceRecordsByDate($exactDateFilter);
            } else {
                $records = $this->buildHolidayPresentEmployees($fromDate, $selectedJobType, $employeeJobTypeMap);
            }
        }

        // For date ranges, auto-add present rows on holiday dates (excluding Sundays)
        // so holidays are represented as present without requiring manual uploads.
        if ($rangeStartDate && $rangeEndDate && !$isSundayNoClassDate) {
            $records = $this->appendHolidayPresentRowsForRange(
                $records,
                $rangeStartDate,
                $rangeEndDate,
                $selectedJobType,
                $employeeJobTypeMap
            );
        }

        if ($selectedJobType) {
            $records = $records
                ->filter(function ($row) use ($employeeJobTypeMap, $selectedJobType) {
                    $employeeId = $this->normalizeEmployeeId($row->employee_id);
                    $employeeJobType = $this->normalizeJobType($employeeJobTypeMap->get($employeeId));
                    return $employeeJobType === $selectedJobType;
                })
                ->values();
        }

        $records = $records->map(function ($row) use ($employeeJobTypeMap, $employeeDepartmentMap, $employeeDisplayNameMap) {
            $employeeId = $this->normalizeEmployeeId($row->employee_id);
            $rowJobType = $this->normalizeJobType($employeeJobTypeMap->get($employeeId));
            $rowDepartment = $employeeDepartmentMap->get($employeeId);
            $rowName = $employeeDisplayNameMap->get($employeeId) ?: $this->normalizeLooseDisplayName($row->employee_name ?? null);
            $computedLateMinutes = $this->calculateLateMinutesFromInTimes($row);
            $isWithinPresentWindow = $this->isPresentByTimeWindow($row);
            $rowDate = null;
            try {
                $rowDate = $row->attendance_date ? Carbon::parse($row->attendance_date)->toDateString() : null;
            } catch (\Throwable $e) {
                $rowDate = null;
            }
            $isHolidayPresent = (bool) ($row->is_holiday_present ?? false);
            if ($isHolidayPresent && $rowDate && !$this->isHolidayDate($rowDate)) {
                $isHolidayPresent = false;
            }
            $hasAnyTimeLog = !empty($row->morning_in)
                || !empty($row->morning_out)
                || !empty($row->afternoon_in)
                || !empty($row->afternoon_out);
            $mainGate = strtolower(trim((string) ($row->main_gate ?? '')));
            $isWithPayLeave = $mainGate === 'leave - with pay';
            $isWithoutPayLeave = $mainGate === 'leave - without pay';
            // Business rule: if there is no scan log for a class day, mark as absent.
            // Keep holiday and leave-with-pay rows excluded from this rule.
            $isAbsentByRule = !$isHolidayPresent && !$isWithPayLeave && !$hasAnyTimeLog;
            $isAbsent = (bool) ($row->is_absent ?? false) || $isAbsentByRule;
            if ($isWithoutPayLeave) {
                $isAbsent = true;
            }
            $isTardyByRule = !$isAbsent && !$isWithinPresentWindow && $computedLateMinutes > 0;
            $gateLabel = $row->main_gate;
            if ($isHolidayPresent && !$isAbsent && (is_null($gateLabel) || trim((string) $gateLabel) === '')) {
                $gateLabel = 'Holiday - No Class';
            }
            if ($isAbsent && trim((string) $gateLabel) === 'Holiday - No Class') {
                $gateLabel = null;
            }

            if (method_exists($row, 'setAttribute')) {
                $row->setAttribute('job_type', $rowJobType);
                $row->setAttribute('department', $row->department ?? $rowDepartment);
                $row->setAttribute('main_gate', $gateLabel);
                $row->setAttribute('employee_name', $rowName);
                $row->setAttribute('computed_late_minutes', $computedLateMinutes);
                $row->setAttribute('is_absent', $isAbsent);
                $row->setAttribute('is_tardy_by_rule', $isTardyByRule);
                $row->setAttribute('is_holiday_present', $isHolidayPresent);
            } else {
                $row->job_type = $rowJobType;
                $row->department = $row->department ?? $rowDepartment;
                $row->main_gate = $gateLabel;
                $row->employee_name = $rowName;
                $row->computed_late_minutes = $computedLateMinutes;
                $row->is_absent = $isAbsent;
                $row->is_tardy_by_rule = $isTardyByRule;
                $row->is_holiday_present = $isHolidayPresent;
            }
            return $row;
        });

        // Enforce exact row-level filtering by normalized job type.
        if ($selectedJobType) {
            $records = $records
                ->filter(fn ($row) => $this->normalizeJobType($row->job_type) === $selectedJobType)
                ->values();
        }

        $isExpandedRangeRecords = false;
        if (!$shouldAutoPresentHolidayDate && !$isSundayNoClassDate && $rangeStartDate && $rangeEndDate) {
            // For range filters, build a full employee-day matrix so absences always appear.
            $records = $this->expandRecordsForDateRange(
                $records,
                $rangeStartDate,
                $rangeEndDate,
                $selectedJobType,
                $employeeJobTypeMap,
                $employeeDepartmentMap
            );
            $isExpandedRangeRecords = true;
        }

        $rowLevelAbsentEmployees = $records
            ->filter(fn ($row) => (bool) ($row->is_absent ?? false))
            ->values();
        $presentEmployees = $records
            ->reject(fn ($row) => (bool) ($row->is_absent ?? false))
            ->values();
        $absentEmployees = $rowLevelAbsentEmployees;

        // Business rule: employees with no attendance row for a day are also absent.
        if (!$shouldAutoPresentHolidayDate && !$isSundayNoClassDate) {
            if ($exactDateFilter) {
                $absentEmployees = $absentEmployees
                    ->concat($this->buildMissingEmployeeAbsences($records, $exactDateFilter, $selectedJobType, $employeeJobTypeMap))
                    ->values();
            } elseif ($rangeStartDate && $rangeEndDate) {
                if (!$isExpandedRangeRecords) {
                    $absentEmployees = $absentEmployees
                        ->concat($this->buildMissingEmployeeAbsencesForRange($records, $rangeStartDate, $rangeEndDate, $selectedJobType, $employeeJobTypeMap))
                        ->values();
                }
            } else {
                // No explicit date filter: infer the covered dates from current rows and
                // build absences per employee per day (not just per employee).
                $recordDates = $records
                    ->map(function ($row) {
                        try {
                            return $row->attendance_date ? Carbon::parse($row->attendance_date)->toDateString() : null;
                        } catch (\Throwable $e) {
                            return null;
                        }
                    })
                    ->filter()
                    ->unique()
                    ->sort()
                    ->values();

                if ($recordDates->count() >= 2) {
                    $absentEmployees = $absentEmployees
                        ->concat($this->buildMissingEmployeeAbsencesForRange(
                            $records,
                            (string) $recordDates->first(),
                            (string) $recordDates->last(),
                            $selectedJobType,
                            $employeeJobTypeMap
                        ))
                        ->values();
                } else {
                    $fallbackDate = $exactDateFilter
                        ?? $normalizedFromDate
                        ?? $normalizedToDate
                        ?? $recordDates->first();

                    $absentEmployees = $absentEmployees
                        ->concat($this->buildMissingEmployeeAbsences(
                            $records,
                            $fallbackDate ? (string) $fallbackDate : null,
                            $selectedJobType,
                            $employeeJobTypeMap
                        ))
                        ->values();
                }
            }
        }
        $tardyEmployees = $records
            ->filter(fn ($row) => (bool) ($row->is_tardy_by_rule ?? false))
            ->map(function ($row) {
                // Keep Blade compatibility by showing the computed late minutes in the existing column.
                $row->late_minutes = (int) ($row->computed_late_minutes ?? 0);
                return $row;
            })
            ->values();
        $allEmployees = $records
            ->map(function ($row) {
                $row->late_minutes = (int) ($row->computed_late_minutes ?? 0);
                return $row;
            })
            ->values();

        if ($activeAttendanceTab === 'total_employee' && $absentEmployees->isNotEmpty() && !$isExpandedRangeRecords) {
            $allEmployees = $allEmployees
                ->concat($absentEmployees->map(function ($row) {
                    $row->late_minutes = (int) ($row->late_minutes ?? 0);
                    return $row;
                }))
                ->sortBy(function ($row) {
                    $date = null;
                    try {
                        $date = $row->attendance_date ? Carbon::parse($row->attendance_date)->toDateString() : '';
                    } catch (\Throwable $e) {
                        $date = '';
                    }

                    return $date.'|'.$this->normalizeEmployeeId($row->employee_id);
                })
                ->values();
        }

        if ($searchName) {
            $presentEmployees = $this->filterAttendanceRowsByEmployeeName($presentEmployees, $searchName);
            $absentEmployees = $this->filterAttendanceRowsByEmployeeName($absentEmployees, $searchName);
            $tardyEmployees = $this->filterAttendanceRowsByEmployeeName($tardyEmployees, $searchName);
            $allEmployees = $this->filterAttendanceRowsByEmployeeName($allEmployees, $searchName);
        }

        $presentCount = $presentEmployees->count();
        $absentCount = $absentEmployees->count();
        $tardyCount = $tardyEmployees->count();
        $totalCount = $presentEmployees->count() + $absentEmployees->count();

        return view('admin.adminAttendance', compact(
            'attendanceFiles',
            'fromDate',
            'toDate',
            'selectedUploadId',
            'selectedJobType',
            'searchName',
            'jobTypeOptions',
            'activeAttendanceTab',
            'presentEmployees',
            'absentEmployees',
            'tardyEmployees',
            'allEmployees',
            'presentCount',
            'absentCount',
            'tardyCount',
            'totalCount'
        ));
    }

    private function isSundayDate(?string $fromDate): bool
    {
        if (!$fromDate) {
            return false;
        }

        try {
            $date = Carbon::parse($fromDate)->startOfDay();
        } catch (\Throwable $e) {
            return false;
        }

        return $date->isSunday();
    }

    private function isHolidayDate(?string $fromDate): bool
    {
        if (!$fromDate) {
            return false;
        }

        if (array_key_exists($fromDate, $this->holidayDateCheckCache)) {
            return $this->holidayDateCheckCache[$fromDate];
        }

        try {
            $date = Carbon::parse($fromDate)->startOfDay();
        } catch (\Throwable $e) {
            $this->holidayDateCheckCache[$fromDate] = false;
            return false;
        }

        $dateString = $date->toDateString();

        if ($this->isCustomHolidayDate($dateString)) {
            $this->holidayDateCheckCache[$fromDate] = true;
            return true;
        }

        if (!$this->isHiddenOfficialHolidayDate($dateString) && $this->isUsPublicHoliday($date)) {
            $this->holidayDateCheckCache[$fromDate] = true;
            return true;
        }

        if ($this->isChineseNewYearDate($date)) {
            $this->holidayDateCheckCache[$fromDate] = true;
            return true;
        }

        $this->holidayDateCheckCache[$fromDate] = false;
        return false;
    }

    private function isHiddenOfficialHolidayDate(string $date): bool
    {
        $hiddenDates = $this->getHiddenOfficialHolidayDates();
        return in_array($date, $hiddenDates, true);
    }

    private function isCustomHolidayDate(string $date): bool
    {
        $config = $this->getCalendarHolidayConfig();
        $customHolidays = $config['custom_holidays'] ?? [];
        if (array_key_exists($date, $customHolidays) && !empty($customHolidays[$date])) {
            return true;
        }

        $monthDay = substr($date, 5);
        $recurringHolidays = $config['recurring_holidays'] ?? [];
        return array_key_exists($monthDay, $recurringHolidays) && !empty($recurringHolidays[$monthDay]);
    }

    private function getCalendarHolidayConfig(): array
    {
        if (!is_null($this->calendarHolidayConfigCache)) {
            return $this->calendarHolidayConfigCache;
        }

        $default = [
            'hidden_official_holidays' => [],
            'custom_holidays' => [],
            'recurring_holidays' => [],
        ];

        try {
            if (!Storage::disk('local')->exists('calendar_holiday_config.json')) {
                $this->calendarHolidayConfigCache = $default;
                return $this->calendarHolidayConfigCache;
            }

            $raw = Storage::disk('local')->get('calendar_holiday_config.json');
            $payload = json_decode($raw, true);
            if (!is_array($payload)) {
                $this->calendarHolidayConfigCache = $default;
                return $this->calendarHolidayConfigCache;
            }

            $customHolidays = collect($payload['custom_holidays'] ?? [])
                ->filter(fn ($names, $date) => is_string($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && is_array($names))
                ->map(fn ($names) => array_values(array_filter(array_map(fn ($name) => is_string($name) ? trim($name) : '', $names), fn ($name) => $name !== '')))
                ->filter(fn ($names) => !empty($names))
                ->all();

            $recurringHolidays = collect($payload['recurring_holidays'] ?? [])
                ->filter(fn ($names, $monthDay) => is_string($monthDay) && preg_match('/^\d{2}-\d{2}$/', $monthDay) && is_array($names))
                ->map(fn ($names) => array_values(array_filter(array_map(fn ($name) => is_string($name) ? trim($name) : '', $names), fn ($name) => $name !== '')))
                ->filter(fn ($names) => !empty($names))
                ->all();

            $hiddenOfficialHolidays = collect($payload['hidden_official_holidays'] ?? [])
                ->filter(fn ($names, $date) => is_string($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && is_array($names) && !empty($names))
                ->all();

            $this->calendarHolidayConfigCache = [
                'hidden_official_holidays' => $hiddenOfficialHolidays,
                'custom_holidays' => $customHolidays,
                'recurring_holidays' => $recurringHolidays,
            ];

            return $this->calendarHolidayConfigCache;
        } catch (\Throwable $e) {
            $this->calendarHolidayConfigCache = $default;
            return $this->calendarHolidayConfigCache;
        }
    }

    private function getHiddenOfficialHolidayDates(): array
    {
        if (!is_null($this->hiddenOfficialHolidayDatesCache)) {
            return $this->hiddenOfficialHolidayDatesCache;
        }

        $config = $this->getCalendarHolidayConfig();
        $fromConfig = collect($config['hidden_official_holidays'] ?? [])
            ->keys()
            ->filter(fn ($date) => is_string($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date))
            ->unique()
            ->values()
            ->all();

        if (!empty($fromConfig)) {
            $this->hiddenOfficialHolidayDatesCache = $fromConfig;
            return $this->hiddenOfficialHolidayDatesCache;
        }

        try {
            if (!Storage::disk('local')->exists('calendar_hidden_holidays.json')) {
                $this->hiddenOfficialHolidayDatesCache = [];
                return $this->hiddenOfficialHolidayDatesCache;
            }

            $raw = Storage::disk('local')->get('calendar_hidden_holidays.json');
            $payload = json_decode($raw, true);
            $dates = is_array($payload['dates'] ?? null) ? $payload['dates'] : [];
            $normalized = collect($dates)
                ->filter(fn ($value) => is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value))
                ->unique()
                ->values()
                ->all();

            $this->hiddenOfficialHolidayDatesCache = $normalized;
            return $this->hiddenOfficialHolidayDatesCache;
        } catch (\Throwable $e) {
            $this->hiddenOfficialHolidayDatesCache = [];
            return $this->hiddenOfficialHolidayDatesCache;
        }
    }

    private function isUsPublicHoliday(Carbon $date): bool
    {
        try {
            $response = Http::timeout(6)
                ->acceptJson()
                ->get("https://date.nager.at/api/v3/PublicHolidays/{$date->year}/US");

            if (!$response->ok()) {
                return false;
            }

            $holidays = $response->json();
            if (!is_array($holidays)) {
                return false;
            }

            $targetDate = $date->toDateString();
            foreach ($holidays as $holiday) {
                if (($holiday['date'] ?? null) === $targetDate) {
                    return true;
                }
            }
        } catch (\Throwable $e) {
            return false;
        }

        return false;
    }

    private function isChineseNewYearDate(Carbon $date): bool
    {
        $chineseNewYearByYear = [
            2024 => '2024-02-10',
            2025 => '2025-01-29',
            2026 => '2026-02-17',
            2027 => '2027-02-06',
            2028 => '2028-01-26',
            2029 => '2029-02-13',
            2030 => '2030-02-03',
            2031 => '2031-01-23',
            2032 => '2032-02-11',
            2033 => '2033-01-31',
            2034 => '2034-02-19',
            2035 => '2035-02-08',
        ];

        $target = $chineseNewYearByYear[$date->year] ?? null;
        return $target === $date->toDateString();
    }

    private function buildHolidayPresentEmployees(?string $fromDate, ?string $selectedJobType = null, $employeeJobTypeMap = null)
    {
        $attendanceDate = null;
        if ($fromDate) {
            try {
                $attendanceDate = Carbon::parse($fromDate)->startOfDay();
            } catch (\Throwable $e) {
                $attendanceDate = null;
            }
        }

        // Use the Admin Employee master list as source of truth.
        $employees = User::query()
            ->with('employee')
            ->where('role', 'Employee')
            ->whereHas('employee', function ($query) {
                $query->whereNotNull('employee_id')
                    ->where('employee_id', '!=', '');
            })
            ->orderBy('id')
            ->get();

        if ($selectedJobType && $employeeJobTypeMap) {
            $employees = $employees
                ->filter(function ($user) use ($employeeJobTypeMap, $selectedJobType) {
                    $employeeId = $this->normalizeEmployeeId($user->employee?->employee_id);
                    $employeeJobType = $this->normalizeJobType($employeeJobTypeMap->get($employeeId));
                    return $employeeJobType === $selectedJobType;
                })
                ->values();
        }

        return $employees
            ->map(function ($user) use ($attendanceDate, $employeeJobTypeMap) {
                $employeeProfile = $user->employee;
                $name = $this->formatEmployeeDisplayName(
                    $user->first_name,
                    $user->middle_name,
                    $user->last_name
                );
                $employeeId = $this->normalizeEmployeeId($employeeProfile?->employee_id);
                $jobType = $this->normalizeJobType($employeeJobTypeMap?->get($employeeId));

                return (object) [
                    'employee_id' => (string) ($employeeProfile?->employee_id ?? ''),
                    'employee_name' => $name,
                    'job_type' => $jobType,
                    'main_gate' => 'Holiday - No Class',
                    'attendance_date' => $attendanceDate,
                    'morning_in' => null,
                    'morning_out' => null,
                    'afternoon_in' => null,
                    'afternoon_out' => null,
                    'late_minutes' => 0,
                    'computed_late_minutes' => 0,
                    'missing_time_logs' => [],
                    'is_absent' => false,
                    'is_tardy_by_rule' => false,
                    'is_holiday_present' => true,
                ];
            })
            ->values();
    }

    private function getAttendanceRecordsByDate(string $date)  // Retrieves attendance records for a specific date, ensuring uniqueness by normalized employee ID and sorted by employee ID for consistent display.
    {
        return AttendanceRecord::query()
            ->whereDate('attendance_date', $date)
            ->orderByDesc('id')
            ->get()
            ->unique(function ($row) {
                return $this->normalizeEmployeeId($row->employee_id);
            })
            ->sortBy('employee_id')
            ->values();
    }

    private function persistHolidayAttendanceForDate(string $date, $employeeJobTypeMap = null): void // For a given date, creates a synthetic AttendanceUpload record if not already exists, and inserts AttendanceRecord entries for all employees without existing records on that date, marking them as present for the holiday. This ensures that holiday attendance is consistently represented in the system, even if the holiday is auto-detected after the fact.
    {
        $holidayUpload = AttendanceUpload::query()->firstOrCreate(
            ['original_name' => "System Holiday Attendance {$date}"],
            [
                'file_path' => "attendance_excels/system_holiday_{$date}.txt",
                'file_size' => 0,
                'status' => 'Processed',
                'processed_rows' => 0,
                'uploaded_at' => Carbon::parse($date)->endOfDay(),
            ]
        );

        $existingEmployeeIds = AttendanceRecord::query()
            ->whereDate('attendance_date', $date)
            ->pluck('employee_id')
            ->map(fn ($id) => $this->normalizeEmployeeId($id))
            ->filter()
            ->values()
            ->all();

        $employees = User::query()
            ->with('employee')
            ->where('role', 'Employee')
            ->whereHas('employee', function ($query) {
                $query->whereNotNull('employee_id')
                    ->where('employee_id', '!=', '');
            })
            ->orderBy('id')
            ->get();

        $hasEmployeeNameColumn = Schema::hasColumn('attendance_records', 'employee_name');
        $hasDepartmentColumn = Schema::hasColumn('attendance_records', 'department');
        $hasMainGateColumn = Schema::hasColumn('attendance_records', 'main_gate');
        $hasJobTypeColumn = Schema::hasColumn('attendance_records', 'job_type');
        $now = now();
        $recordsToInsert = [];

        foreach ($employees as $user) {
            $employeeId = $this->normalizeEmployeeId($user->employee?->employee_id);
            if ($employeeId === '' || in_array($employeeId, $existingEmployeeIds, true)) {
                continue;
            }

            $name = $this->formatEmployeeDisplayName(
                $user->first_name,
                $user->middle_name,
                $user->last_name
            );

            $record = [
                'attendance_upload_id' => $holidayUpload->id,
                'employee_id' => (string) $employeeId,
                'attendance_date' => $date,
                'morning_in' => null,
                'morning_out' => null,
                'afternoon_in' => null,
                'afternoon_out' => null,
                'late_minutes' => 0,
                'missing_time_logs' => json_encode([]),
                'is_absent' => false,
                'is_tardy' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($hasEmployeeNameColumn) {
                $record['employee_name'] = $name;
            }
            if ($hasDepartmentColumn) {
                $record['department'] = $user->employee?->department ?: null;
            }
            if ($hasMainGateColumn) {
                $record['main_gate'] = 'Holiday - No Class';
            }
            if ($hasJobTypeColumn) {
                $record['job_type'] = $this->normalizeJobType($employeeJobTypeMap?->get($employeeId));
            }

            $recordsToInsert[] = $record;
        }

        if (!empty($recordsToInsert)) {
            AttendanceRecord::insert($recordsToInsert);
        }

        $holidayUpload->update([
            'status' => 'Processed',
            'processed_rows' => AttendanceRecord::query()
                ->where('attendance_upload_id', $holidayUpload->id)
                ->count(),
        ]);
    }

    private function isPresentByTimeWindow($row): bool
    {
        return $this->isTimeWithinRange($row->morning_in, '03:00:00', '08:15:00')
            && $this->isTimeWithinRange($row->morning_out, '11:55:00', '12:45:00')
            && $this->isTimeWithinRange($row->afternoon_in, '12:45:00', '13:15:00')
            && $this->isTimeWithinRange($row->afternoon_out, '17:00:00', '20:00:00');
    }

    private function isTimeWithinRange(?string $time, string $start, string $end): bool
    {
        if (!$time) {
            return false;
        }

        try {
            $timeValue = Carbon::createFromFormat('H:i:s', $time)->format('H:i:s');
            return $timeValue >= $start && $timeValue <= $end;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function calculateLateMinutesFromInTimes($row): int
    {
        $late = 0;

        if ($row->morning_in) {
            try {
                $morningActual = Carbon::createFromFormat('H:i:s', $row->morning_in);
                $morningExpected = Carbon::createFromFormat('H:i:s', '08:00:00');
                $morningGraceEnd = Carbon::createFromFormat('H:i:s', '08:15:00');
                if ($morningActual->greaterThan($morningGraceEnd)) {
                    $late += $morningExpected->diffInMinutes($morningActual);
                }
            } catch (\Throwable $e) {
            }
        }

        if ($row->afternoon_in) {
            try {
                $afternoonActual = Carbon::createFromFormat('H:i:s', $row->afternoon_in);
                $afternoonExpected = Carbon::createFromFormat('H:i:s', '13:00:00');
                $afternoonGraceEnd = Carbon::createFromFormat('H:i:s', '13:15:00');
                if ($afternoonActual->greaterThan($afternoonGraceEnd)) {
                    $late += $afternoonExpected->diffInMinutes($afternoonActual);
                }
            } catch (\Throwable $e) {
            }
        }

        return $late;
    }

    private function buildMissingEmployeeAbsences($records, ?string $fromDate, ?string $selectedJobType = null, $employeeJobTypeMap = null)
    {
        $recordedEmployeeIds = $records
            ->pluck('employee_id')
            ->map(fn ($id) => $this->normalizeEmployeeId($id))
            ->filter()
            ->values()
            ->all();

        $employees = Employee::query()
            ->with('user:id,first_name,middle_name,last_name,role,status')
            ->whereNotNull('employee_id')
            ->where('employee_id', '!=', '')
            ->orderBy('employee_id')
            ->get();

        if ($selectedJobType && $employeeJobTypeMap) {
            $employees = $employees
                ->filter(function ($employee) use ($employeeJobTypeMap, $selectedJobType) {
                    $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                    $employeeJobType = $this->normalizeJobType($employeeJobTypeMap->get($employeeId));
                    return $employeeJobType === $selectedJobType;
                })
                ->values();
        }

        $attendanceDate = null;
        if ($fromDate) {
            try {
                $attendanceDate = Carbon::parse($fromDate)->startOfDay();
            } catch (\Throwable $e) {
                $attendanceDate = null;
            }
        }

        return $employees
            ->reject(function ($employee) use ($recordedEmployeeIds) {
                $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                return in_array($employeeId, $recordedEmployeeIds, true);
            })
            ->map(function ($employee) use ($attendanceDate, $employeeJobTypeMap) {
                $user = $employee->user;
                $name = $this->formatEmployeeDisplayName(
                    $user?->first_name,
                    $user?->middle_name,
                    $user?->last_name
                );
                $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                $jobType = $this->normalizeJobType($employeeJobTypeMap?->get($employeeId));

                return (object) [
                    'employee_id' => (string) $employee->employee_id,
                    'employee_name' => $name,
                    'job_type' => $jobType,
                    'main_gate' => null,
                    'attendance_date' => $attendanceDate,
                    'morning_in' => null,
                    'morning_out' => null,
                    'afternoon_in' => null,
                    'afternoon_out' => null,
                    'late_minutes' => 0,
                    'computed_late_minutes' => 0,
                    'missing_time_logs' => ['morning_in', 'morning_out', 'afternoon_in', 'afternoon_out'],
                    'is_absent' => true,
                    'is_tardy_by_rule' => false,
                ];
            })
            ->values();
    }

    private function buildMissingEmployeeAbsencesForRange($records, string $startDate, string $endDate, ?string $selectedJobType = null, $employeeJobTypeMap = null)
    {
        $recordedEmployeeDateKeys = collect($records)
            ->filter(function ($row) {
                return !empty($row->employee_id) && !empty($row->attendance_date);
            })
            ->map(function ($row) {
                $employeeId = $this->normalizeEmployeeId($row->employee_id);
                if ($employeeId === '') {
                    return null;
                }

                try {
                    $date = Carbon::parse($row->attendance_date)->toDateString();
                } catch (\Throwable $e) {
                    $date = null;
                }

                if (!$date) {
                    return null;
                }

                return $employeeId.'|'.$date;
            })
            ->filter()
            ->flip();

        $employees = Employee::query()
            ->with('user:id,first_name,middle_name,last_name,role,status')
            ->whereNotNull('employee_id')
            ->where('employee_id', '!=', '')
            ->whereHas('user', function ($query) {
                $query->where('role', 'Employee')
                    ->where('status', 'Approved');
            })
            ->orderBy('employee_id')
            ->get();

        if ($selectedJobType && $employeeJobTypeMap) {
            $employees = $employees
                ->filter(function ($employee) use ($employeeJobTypeMap, $selectedJobType) {
                    $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                    $employeeJobType = $this->normalizeJobType($employeeJobTypeMap->get($employeeId));
                    return $employeeJobType === $selectedJobType;
                })
                ->values();
        }

        $absences = collect();
        $current = Carbon::parse($startDate)->startOfDay();
        $last = Carbon::parse($endDate)->startOfDay();

        while ($current->lte($last)) {
            $date = $current->toDateString();

            foreach ($employees as $employee) {
                $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                if ($employeeId === '') {
                    continue;
                }

                $employeeDateKey = $employeeId.'|'.$date;
                if ($recordedEmployeeDateKeys->has($employeeDateKey)) {
                    continue;
                }

                $user = $employee->user;
                $name = $this->formatEmployeeDisplayName(
                    $user?->first_name,
                    $user?->middle_name,
                    $user?->last_name
                );
                $jobType = $this->normalizeJobType($employeeJobTypeMap?->get($employeeId));

                $absences->push((object) [
                    'employee_id' => (string) $employee->employee_id,
                    'employee_name' => $name,
                    'job_type' => $jobType,
                    'main_gate' => null,
                    'attendance_date' => Carbon::parse($date)->startOfDay(),
                    'morning_in' => null,
                    'morning_out' => null,
                    'afternoon_in' => null,
                    'afternoon_out' => null,
                    'late_minutes' => 0,
                    'computed_late_minutes' => 0,
                    'missing_time_logs' => ['morning_in', 'morning_out', 'afternoon_in', 'afternoon_out'],
                    'is_absent' => true,
                    'is_tardy_by_rule' => false,
                ]);
            }

            $current->addDay();
        }

        return $absences
            ->sortBy(function ($row) {
                $date = optional($row->attendance_date)->format('Y-m-d') ?? '';
                return $date.'|'.$this->normalizeEmployeeId($row->employee_id);
            })
            ->values();
    }

    private function expandRecordsForDateRange(
        $records,
        string $startDate,
        string $endDate,
        ?string $selectedJobType = null,
        $employeeJobTypeMap = null,
        $employeeDepartmentMap = null
    ) {
        $existingByEmployeeDate = collect($records)
            ->filter(function ($row) {
                return !empty($row->employee_id) && !empty($row->attendance_date);
            })
            ->sortByDesc('id')
            ->reduce(function ($carry, $row) {
                $employeeId = $this->normalizeEmployeeId($row->employee_id);
                if ($employeeId === '') {
                    return $carry;
                }

                $date = optional($row->attendance_date)->format('Y-m-d');
                if (!$date) {
                    try {
                        $date = Carbon::parse($row->attendance_date)->toDateString();
                    } catch (\Throwable $e) {
                        $date = null;
                    }
                }

                if (!$date) {
                    return $carry;
                }

                $key = $employeeId.'|'.$date;
                if (!$carry->has($key)) {
                    $carry->put($key, $row);
                }

                return $carry;
            }, collect());

        $employees = Employee::query()
            ->with('user:id,first_name,middle_name,last_name,role,status')
            ->whereNotNull('employee_id')
            ->where('employee_id', '!=', '')
            ->orderBy('employee_id')
            ->get();

        if ($selectedJobType && $employeeJobTypeMap) {
            $employees = $employees
                ->filter(function ($employee) use ($employeeJobTypeMap, $selectedJobType) {
                    $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                    $employeeJobType = $this->normalizeJobType($employeeJobTypeMap->get($employeeId));
                    return $employeeJobType === $selectedJobType;
                })
                ->values();
        }

        $expanded = collect();
        $current = Carbon::parse($startDate)->startOfDay();
        $last = Carbon::parse($endDate)->startOfDay();

        while ($current->lte($last)) {
            $date = $current->toDateString();

            foreach ($employees as $employee) {
                $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                if ($employeeId === '') {
                    continue;
                }

                $key = $employeeId.'|'.$date;
                $existing = $existingByEmployeeDate->get($key);

                if ($existing) {
                    $expanded->push($existing);
                    continue;
                }

                $user = $employee->user;
                $name = $this->formatEmployeeDisplayName(
                    $user?->first_name,
                    $user?->middle_name,
                    $user?->last_name
                );

                $expanded->push((object) [
                    'employee_id' => (string) $employee->employee_id,
                    'employee_name' => $name,
                    'department' => $employeeDepartmentMap?->get($employeeId),
                    'job_type' => $this->normalizeJobType($employeeJobTypeMap?->get($employeeId)),
                    'main_gate' => null,
                    'attendance_date' => Carbon::parse($date)->startOfDay(),
                    'morning_in' => null,
                    'morning_out' => null,
                    'afternoon_in' => null,
                    'afternoon_out' => null,
                    'late_minutes' => 0,
                    'computed_late_minutes' => 0,
                    'missing_time_logs' => ['morning_in', 'morning_out', 'afternoon_in', 'afternoon_out'],
                    'is_absent' => true,
                    'is_tardy_by_rule' => false,
                    'is_holiday_present' => false,
                ]);
            }

            $current->addDay();
        }

        return $expanded
            ->sortBy(function ($row) {
                $date = optional($row->attendance_date)->format('Y-m-d') ?? '';
                return $date.'|'.$this->normalizeEmployeeId($row->employee_id);
            })
            ->values();
    }

    private function appendHolidayPresentRowsForRange(
        $records,
        string $startDate,
        string $endDate,
        ?string $selectedJobType = null,
        $employeeJobTypeMap = null
    ) {
        $datesWithAnyRecord = collect($records)
            ->map(function ($row) {
                try {
                    return $row->attendance_date ? Carbon::parse($row->attendance_date)->toDateString() : null;
                } catch (\Throwable $e) {
                    return null;
                }
            })
            ->filter()
            ->unique()
            ->flip();

        $existingKeys = collect($records)
            ->filter(function ($row) {
                return !empty($row->employee_id) && !empty($row->attendance_date);
            })
            ->map(function ($row) {
                $employeeId = $this->normalizeEmployeeId($row->employee_id);
                if ($employeeId === '') {
                    return null;
                }

                try {
                    $date = Carbon::parse($row->attendance_date)->toDateString();
                } catch (\Throwable $e) {
                    return null;
                }

                return $date ? ($employeeId.'|'.$date) : null;
            })
            ->filter()
            ->flip();

        $current = Carbon::parse($startDate)->startOfDay();
        $last = Carbon::parse($endDate)->startOfDay();
        $holidayRows = collect();

        while ($current->lte($last)) {
            $date = $current->toDateString();
            if (!$this->isSundayDate($date) && $this->isHolidayDate($date)) {
                // If the date already has any attendance records, do not auto-fill missing
                // employees as present for that holiday date.
                if ($datesWithAnyRecord->has($date)) {
                    $current->addDay();
                    continue;
                }

                $dailyHolidayRows = $this->buildHolidayPresentEmployees($date, $selectedJobType, $employeeJobTypeMap)
                    ->filter(function ($row) use ($existingKeys) {
                        $employeeId = $this->normalizeEmployeeId($row->employee_id);
                        if ($employeeId === '' || empty($row->attendance_date)) {
                            return false;
                        }

                        try {
                            $date = Carbon::parse($row->attendance_date)->toDateString();
                        } catch (\Throwable $e) {
                            return false;
                        }

                        $key = $employeeId.'|'.$date;
                        if ($existingKeys->has($key)) {
                            return false;
                        }

                        $existingKeys->put($key, true);
                        return true;
                    })
                    ->values();

                $holidayRows = $holidayRows->concat($dailyHolidayRows);
                $datesWithAnyRecord->put($date, true);
            }

            $current->addDay();
        }

        if ($holidayRows->isEmpty()) {
            return collect($records);
        }

        return collect($records)
            ->concat($holidayRows)
            ->sortBy(function ($row) {
                $date = '';
                try {
                    $date = $row->attendance_date ? Carbon::parse($row->attendance_date)->toDateString() : '';
                } catch (\Throwable $e) {
                    $date = '';
                }

                return $date.'|'.$this->normalizeEmployeeId($row->employee_id);
            })
            ->values();
    }

    private function filterAttendanceRowsByEmployeeName($rows, string $searchName)
    {
        $needle = strtolower(trim($searchName));
        if ($needle === '') {
            return collect($rows)->values();
        }

        return collect($rows)
            ->filter(function ($row) use ($needle) {
                $name = strtolower(trim((string) ($row->employee_name ?? '')));
                return $name !== '' && str_contains($name, $needle);
            })
            ->values();
    }

    private function formatEmployeeDisplayName($firstName, $middleName, $lastName): ?string
    {
        $first = trim((string) ($firstName ?? ''));
        $middle = trim((string) ($middleName ?? ''));
        $last = trim((string) ($lastName ?? ''));

        $firstMiddle = trim(implode(' ', array_filter([$first, $middle], fn ($part) => $part !== '')));

        if ($last !== '' && $firstMiddle !== '') {
            return "{$last}, {$firstMiddle}";
        }

        if ($last !== '') {
            return $last;
        }

        if ($firstMiddle !== '') {
            return $firstMiddle;
        }

        return null;
    }

    private function normalizeLooseDisplayName($name): ?string
    {
        $value = trim((string) ($name ?? ''));
        if ($value === '') {
            return null;
        }

        // Preserve any existing delimiter style when source parts are unavailable.
        return preg_replace('/\s+/', ' ', $value);
    }

    private function normalizeJobType($value): ?string // Normalizes various user inputs for job type into consistent values used in the system. Returns null for empty or unrecognized inputs.
    {
        $normalized = strtolower(trim((string) $value));
        if ($normalized === '') {
            return null;
        }

        if (in_array($normalized, ['teaching', 't'], true)) {
            return 'Teaching';
        }

        if (in_array($normalized, ['non-teaching', 'non teaching', 'nonteaching', 'nt'], true)) {
            return 'Non-Teaching';
        }

        return ucwords($normalized);
    }

    private function normalizeEmployeeId($value): string
    {
        $normalized = trim((string) $value);
        if ($normalized === '') {
            return '';
        }

        // Excel often exports numeric IDs as "123.0"; map these back to the base ID.
        if (preg_match('/^(\d+)\.0+$/', $normalized, $matches)) {
            return $matches[1];
        }

        return $normalized;
    }

    private function normalizeFilterDate(?string $fromDate): ?string
    {
        if (!$fromDate) {
            return null;
        }

        try {
            return Carbon::parse($fromDate)->toDateString();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function display_leave(Request $request){
        $selectedMonth = trim((string) $request->query('month', now()->format('Y-m')));
        try {
            $monthCursor = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Throwable $e) {
            $monthCursor = now()->startOfMonth();
            $selectedMonth = $monthCursor->format('Y-m');
        }

        $monthApplications = LeaveApplication::query()
            ->where(function ($query) use ($monthCursor) {
                $query
                    ->where(function ($filingDateQuery) use ($monthCursor) {
                        $filingDateQuery
                            ->whereNotNull('filing_date')
                            ->whereYear('filing_date', $monthCursor->year)
                            ->whereMonth('filing_date', $monthCursor->month);
                    })
                    ->orWhere(function ($createdAtQuery) use ($monthCursor) {
                        $createdAtQuery
                            ->whereNull('filing_date')
                            ->whereYear('created_at', $monthCursor->year)
                            ->whereMonth('created_at', $monthCursor->month);
                    });
            })
            ->orderByDesc('created_at')
            ->get();

        $approvedMonthApplications = $monthApplications
            ->filter(function ($application) {
                return strcasecmp((string) ($application->status ?? ''), 'Approved') === 0;
            })
            ->values();

        $monthRecords = $approvedMonthApplications
            ->map(function ($application) {
                $baseDate = $application->filing_date
                    ? Carbon::parse($application->filing_date)->startOfDay()
                    : Carbon::parse($application->created_at)->startOfDay();
                $days = (float) ($application->number_of_working_days ?? 0);
                if ($days <= 0) {
                    $days = max(
                        (float) ($application->days_with_pay ?? 0),
                        (float) ($application->applied_total ?? 0)
                    );
                }
                $rangeDays = max((int) ceil($days), 1);

                return [
                    'employee_name' => $application->employee_name ?? '-',
                    'leave_type' => $application->leave_type ?: 'Leave',
                    'start_date_carbon' => $baseDate->copy(),
                    'end_date_carbon' => $baseDate->copy()->addDays($rangeDays - 1),
                    'days' => $days,
                    'reason' => $application->inclusive_dates ?: '-',
                ];
            })
            ->values();

        $totalLeaveUsedDays = (int) $monthRecords->sum('days');
        $sickLeaveUsedDays = (int) $monthRecords
            ->filter(fn ($record) => strcasecmp((string) $record['leave_type'], 'Sick Leave') === 0)
            ->sum('days');

        $leaveTypeCounts = $monthRecords
            ->groupBy(fn ($record) => (string) ($record['leave_type'] ?? 'Leave'))
            ->map(fn ($records) => (int) $records->sum('days'));

        $pendingLeaveRequests = $monthApplications
            ->filter(function ($application) {
                $status = trim((string) ($application->status ?? ''));
                return $status === '' || strcasecmp($status, 'Pending') === 0;
            })
            ->sortByDesc('created_at')
            ->values();

        $pendingLeaveDays = (float) $pendingLeaveRequests->sum(function ($row) {
            return (float) ($row->number_of_working_days ?? 0);
        });

        return view('admin.adminLeaveManagement', compact(
            'selectedMonth',
            'totalLeaveUsedDays',
            'sickLeaveUsedDays',
            'monthRecords',
            'leaveTypeCounts',
            'pendingLeaveRequests',
            'pendingLeaveDays'
        ));
    }

    public function display_payslip(){
        $payslipFiles = PayslipUpload::query()
            ->orderByDesc('uploaded_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.adminPayslip', compact('payslipFiles'));
    }

    public function display_payslip_view(Request $request){
        $uploadId = (int) $request->query('upload_id', 0);
        $recordId = (int) $request->query('record_id', 0);

        $recordsQuery = PayslipRecord::query()
            ->with('upload:id,original_name,uploaded_at')
            ->orderByDesc('scanned_at')
            ->orderByDesc('id');

        if ($uploadId > 0) {
            $recordsQuery->where('payslip_upload_id', $uploadId);
        }

        // Show one container per employee (latest scanned row for that employee).
        $records = $recordsQuery->get()
            ->filter(function ($record) {
                return trim((string) ($record->employee_id ?? '')) !== '';
            })
            ->unique(function ($record) {
                return strtolower(trim((string) $record->employee_id));
            })
            ->values();
        $selectedRecord = null;

        if ($recordId > 0) {
            $selectedRecord = $records->firstWhere('id', $recordId);
        }



        return view('admin.adminPaySlipView', compact('records', 'selectedRecord', 'uploadId'));
    }

    public function display_resignations(Request $request){
        $selectedStatus = trim((string) $request->query('status', 'All'));
        $search = trim((string) $request->query('search', ''));

        $resignationsQuery = Resignation::query()
            ->with([
                'user:id,first_name,middle_name,last_name,email',
                'processor:id,first_name,last_name',
            ])
            ->orderByDesc('submitted_at')
            ->orderByDesc('id');

        if ($selectedStatus !== '' && strcasecmp($selectedStatus, 'All') !== 0) {
            $resignationsQuery->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", [strtolower($selectedStatus)]);
        }

        if ($search !== '') {
            $needle = strtolower($search);
            $resignationsQuery->where(function ($query) use ($needle) {
                $query
                    ->orWhereRaw("LOWER(COALESCE(employee_name, '')) LIKE ?", ['%'.$needle.'%'])
                    ->orWhereRaw("LOWER(COALESCE(employee_id, '')) LIKE ?", ['%'.$needle.'%'])
                    ->orWhereRaw("LOWER(COALESCE(department, '')) LIKE ?", ['%'.$needle.'%'])
                    ->orWhereRaw("LOWER(COALESCE(position, '')) LIKE ?", ['%'.$needle.'%']);
            });
        }

        $resignations = $resignationsQuery->get();

        $pendingResignations = Resignation::query()
            ->with([
                'user:id,first_name,middle_name,last_name,email',
                'processor:id,first_name,last_name',
            ])
            ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['pending'])
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->get();

        $employees = User::query()
            ->with('employee:user_id,employee_id,department,position')
            ->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
            ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['approved'])
            ->orderBy('first_name')
            ->get();

        $statusCounts = [
            'Pending' => (int) Resignation::query()->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['pending'])->count(),
            'Approved' => (int) Resignation::query()->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['approved'])->count(),
            'Completed' => (int) Resignation::query()->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['completed'])->count(),
            'Rejected' => (int) Resignation::query()->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['rejected'])->count(),
            'Cancelled' => (int) Resignation::query()->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['cancelled'])->count(),
        ];

        return view('admin.adminResignations', compact(
            'resignations',
            'pendingResignations',
            'employees',
            'statusCounts',
            'selectedStatus',
            'search'
        ));
    }

    public function display_reports(){
        return view('admin.adminReports');
    }

    public function display_compare(){
        return view('admin.compareCode');
    }

    public function display_applicant(){
        $applicant = Applicant::with(
            'position:id,title,department,employment,collage_name,work_mode,job_description,responsibilities,requirements,experience_level,location,skills,benifits,job_type,one,two,passionate'
        )->latest('created_at')->get();
        $count_applicant = Applicant::count();
        $count_under_review = $applicant->where('application_status','Under Review')->count();
        $count_final_interview = $applicant
            ->whereIn('application_status', ['Initial Interview', 'Final Interview'])
            ->count();
        $hired = Applicant::where('application_status', 'Hired')->whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->count();

        return view('admin.adminApplicant', compact('applicant', 'hired',
                                            'count_applicant','count_under_review'
                                            ,'count_final_interview'));
    }

    public function display_applicant_ID($id){
        $app = Applicant::with(
            'documents:id,filename,applicant_id',
            'position:id,title,department,employment,collage_name,work_mode,job_description,responsibilities,requirements,experience_level,location,skills,benifits,job_type,one,two,passionate'
            )->findOrFail($id);

        return response()->json([
            'id' => $app->id,
            'name' => $app->first_name.' '.$app->last_name,
            'email' => $app->email,
            'title' => $app->position->title,
            'job_type' => $app->position->job_type,
            'status' => $app->application_status,
            'location' => $app->address,
            'one' => $app->created_at->format('F d, Y'),
            'passionate' => $app->position->passionate,
            'work_position' => $app->work_position,
            'work_employer' => $app->work_employer,
            'work_location' => $app->work_location,
            'work_duration' => $app->work_duration,
            'university_name' => $app->university_name,
            'university_address' => $app->university_address,
            'university_year' => $app->year_complete,
            'skills' => $app->skills_n_expertise,
            'number' => $app->phone,
            'star' => $app->starRatings,
            'documents' => $app->documents->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'name' => $doc->filename,
                    'type' => $doc->type,
                ];
            }),
        ]);
    }

    public function display_edit_position($id){
        $open = OpenPosition::findOrFail($id);
        return view('admin.adminEditPosition', compact('open'));
    }

    public function display_interview(){/////sync interview status to applicant status if interview is completed
        $this->syncFinishedInterviewApplicantStatuses();

        $allInterviews = Interviewer::with(['applicant.position'])
            ->whereHas('applicant')
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        // Show all scheduled interviews in the list; card state is handled in the view.
        $interview = $allInterviews->values();
        $upcomingInterviews = $allInterviews
            ->filter(function ($item) {
                $start = \Carbon\Carbon::parse($item->date->format('Y-m-d').' '.$item->time);
                $end = (clone $start)->addMinutes($this->durationToMinutes($item->duration));
                return now()->lt($end);
            })
            ->values();
        $completedInterviews = $allInterviews
            ->filter(function ($item) {
                $start = \Carbon\Carbon::parse($item->date->format('Y-m-d').' '.$item->time);
                $end = (clone $start)->addMinutes($this->durationToMinutes($item->duration));
                return now()->gte($end);
            })
            ->values();

        $count_daily = $allInterviews
            ->filter(function ($item) {
                $start = \Carbon\Carbon::parse($item->date->format('Y-m-d').' '.$item->time);
                $end = (clone $start)->addMinutes($this->durationToMinutes($item->duration));
                return $end->isToday() && now()->gte($end);
            })
            ->count();
        $count_month = $allInterviews
            ->filter(function ($item) {
                $start = \Carbon\Carbon::parse($item->date->format('Y-m-d').' '.$item->time);
                $end = (clone $start)->addMinutes($this->durationToMinutes($item->duration));
                return $end->isCurrentMonth() && $end->isCurrentYear() && now()->gte($end);
            })
            ->count();
        $count_year = $allInterviews
            ->filter(function ($item) {
                $start = \Carbon\Carbon::parse($item->date->format('Y-m-d').' '.$item->time);
                $end = (clone $start)->addMinutes($this->durationToMinutes($item->duration));
                return $end->isCurrentYear() && now()->gte($end);
            })
            ->count();
        $count_upcoming = $allInterviews
            ->filter(function ($item) {
                $start = \Carbon\Carbon::parse($item->date->format('Y-m-d').' '.$item->time);
                return now()->lt($start);
            })
            ->count();
        return view('admin.adminInterview', compact(
            'interview',
            'upcomingInterviews',
            'completedInterviews',
            'count_daily',
            'count_month',
            'count_year',
            'count_upcoming'
        ));
    }

    private function syncFinishedInterviewApplicantStatuses(): void
    {
        $allInterviews = Interviewer::query()
            ->select(['applicant_id', 'date', 'time', 'duration'])
            ->whereNotNull('applicant_id')
            ->get();

        if ($allInterviews->isEmpty()) {
            return;
        }

        $latestByApplicant = $allInterviews
            ->groupBy('applicant_id')
            ->map(function ($items) {
                return $items->sortBy(function ($item) {
                    $start = Carbon::parse($item->date->format('Y-m-d').' '.$item->time);
                    $end = (clone $start)->addMinutes($this->durationToMinutes($item->duration));
                    return $end->timestamp;
                })->last();
            })
            ->filter();

        $completedApplicantIds = $latestByApplicant
            ->filter(function ($item) {
                $start = Carbon::parse($item->date->format('Y-m-d').' '.$item->time);
                $end = (clone $start)->addMinutes($this->durationToMinutes($item->duration));
                return now()->gte($end);
            })
            ->keys()
            ->values()
            ->all();

        if (empty($completedApplicantIds)) {
            return;
        }

        Applicant::query()
            ->whereIn('id', $completedApplicantIds)
            ->whereIn('application_status', ['Initial Interview', 'Final Interview'])
            ->update(['application_status' => 'Completed']);
    }

    private function durationToMinutes(?string $duration): int
    {
        if (!$duration) {
            return 0;
        }

        if (preg_match('/(\d+)/', $duration, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    public function display_interview_ID($id){
        $app = Interviewer::with([
            'applicant:id,first_name,last_name,open_position_id',
            'applicant.position:id,title,department,employment,collage_name,work_mode,job_description,responsibilities,requirements,experience_level,location,skills,benifits,job_type,one,two,passionate'
        ])->where('applicant_id', $id)->firstOrFail();


        return response()->json([
            'id' => $app->id,
            'name' => $app->applicant->first_name.' '.$app->applicant->last_name,
            'email' => $app->email_link,
            'title' => $app->applicant->position->title,
            'status' => $app->application_status,
            'applicant_id' => $app->applicant_id,
            'interview_type' => $app->interview_type,
            'date' => $app->date->format('Y-m-d'),
            'time' => \Carbon\Carbon::parse($app->time)->format('H:i'),
            'duration' => $app->duration,
            'interviewers' => $app->interviewers,
            'email_link' => $app->email_link,
            'url' => $app->url,
            'notes' => $app->notes,
        ]);
    }

    public function display_meeting(){
        return view('admin.adminMeeting');
    }

    public function display_calendar(){
        return view('admin.adminCalendar');
    }

    public function display_position(){
        $openPosition = OpenPosition::withCount('applicants')->get();
        $openPositions = OpenPosition::all();
        $countApplication = Applicant::groupBy('open_position_id')->count();
        $logs = GuestLog::count();
        $positionCounts = $openPositions->count();
        $applicantCounts = Applicant::count();
        return view('admin.adminPosition', compact('openPosition',
        'logs', 'positionCounts', 'applicantCounts','countApplication'));
    }

    public function display_show_position($id){
        $open = OpenPosition::findOrFail($id);
        $titles = OpenPosition::pluck('id');
        $admin = User::admins()->get();
        $countApplication = Applicant::whereIn('open_position_id', $titles)->count();
        return view('admin.adminShowPosition', compact('open','countApplication','admin'));
    }

    public function display_overview(){
        return view('admin.adminEmployeeOverview');
    }

    public function employee_documents($id){
        $requiredPrefix = '__REQUIRED__::';
        $noticeType = '__NOTICE__';
        $employee = User::with([
            'applicant.documents' => function ($query) use ($requiredPrefix, $noticeType) {
                $query->select([
                    'id',
                    'applicant_id',
                    'filename',
                    'filepath',
                    'type',
                    'mime_type',
                    'size',
                    'created_at',
                ])
                ->where('type', 'not like', $requiredPrefix.'%')
                ->where('type', '!=', $noticeType)
                ->orderByDesc('created_at');
            },
        ])->where('role', 'Employee')->findOrFail($id);

        $documents = $employee->applicant?->documents?->values() ?? collect();
        $applicantId = (int) ($employee->applicant?->id ?? 0);
        $requiredConfig = $this->getRequiredDocumentConfigForApplicant($applicantId);
        $requiredDocuments = collect($requiredConfig['required_documents'] ?? [])
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();

        $uploadedDocumentTypesNormalized = $documents
            ->map(function ($doc) {
                return $this->normalizeDocumentLabel((string) ($doc->type ?: $doc->filename));
            })
            ->filter()
            ->unique()
            ->values();

        $missingDocuments = collect($requiredDocuments)
            ->filter(function ($required) use ($uploadedDocumentTypesNormalized) {
                return !$uploadedDocumentTypesNormalized->contains(
                    $this->normalizeDocumentLabel((string) $required)
                );
            })
            ->values()
            ->all();

        return response()->json([
            'documents' => $documents,
            'required_documents' => $requiredDocuments,
            'required_documents_text' => implode("\n", $requiredDocuments),
            'document_notice' => (string) ($requiredConfig['document_notice'] ?? ''),
            'missing_documents' => $missingDocuments,
        ]);
    }

    //Personal Detail
    public function display_documents(){
        return view('admin.PersonalDetail.adminEmployeeDocuments');
    }

    public function display_pd(){
        return view('admin.PersonalDetail.adminEmployeePD');
    }

    public function display_personal_detail_overview(){
        return view('admin.PersonalDetail.adminEmployeeOverview');
    }

    public function display_performance(){
        return view('admin.PersonalDetail.adminEmployeePerformance');
    }

    public function display_edit(){
        return view('admin.PersonalDetail.editProfile');
    }

    public function display_create_position(){
        return view('admin.adminCreatePosition');
    }

    private function getRequiredDocumentConfigForApplicant(int $applicantId): array
    {
        if ($applicantId <= 0) {
            return [];
        }

        $requiredPrefix = '__REQUIRED__::';
        $noticeType = '__NOTICE__';
        $metaDocuments = ApplicantDocument::query()
            ->where('applicant_id', $applicantId)
            ->where(function ($query) use ($requiredPrefix, $noticeType) {
                $query
                    ->where('type', 'like', $requiredPrefix.'%')
                    ->orWhere('type', $noticeType);
            })
            ->orderByDesc('id')
            ->get();

        if ($metaDocuments->isNotEmpty()) {
            $requiredDocuments = $metaDocuments
                ->filter(fn ($doc) => str_starts_with((string) ($doc->type ?? ''), $requiredPrefix))
                ->map(function ($doc) use ($requiredPrefix) {
                    return trim((string) substr((string) $doc->type, strlen($requiredPrefix)));
                })
                ->filter()
                ->unique(function ($value) {
                    return strtolower($value);
                })
                ->values()
                ->all();

            $notice = (string) optional($metaDocuments->firstWhere('type', $noticeType))->filename;

            return [
                'required_documents' => $requiredDocuments,
                'document_notice' => $notice,
            ];
        }

        $disk = Storage::disk('local');
        $path = 'required_employee_documents.json';
        if (!$disk->exists($path)) {
            return [];
        }

        $payload = json_decode((string) $disk->get($path), true);
        if (!is_array($payload)) {
            return [];
        }

        $applicants = is_array($payload['applicants'] ?? null) ? $payload['applicants'] : [];
        $entry = $applicants[(string) $applicantId] ?? null;
        if (!is_array($entry)) {
            return [];
        }

        return $entry;
    }

    private function normalizeDocumentLabel(string $value): string
    {
        $normalized = strtolower(trim($value));
        if ($normalized === '') {
            return '';
        }

        return preg_replace('/\s+/', ' ', $normalized);
    }

}


