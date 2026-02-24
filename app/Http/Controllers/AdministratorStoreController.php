<?php

namespace App\Http\Controllers;

use App\Models\AttendanceUpload;
use App\Models\AttendanceRecord;
use App\Models\Applicant;
use App\Models\ApplicantDocument;
use App\Models\Education;
use App\Models\Employee;
use App\Models\Government;
use App\Models\Interviewer;
use App\Models\License;
use App\Models\LeaveApplication;
use App\Models\OpenPosition;
use App\Models\Resignation;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Mail\ApplicationUpdatedMail;
use App\Mail\ApplicationInterviewMail;
use Illuminate\Support\Facades\Mail;


class AdministratorStoreController extends Controller
{
    public function sync_hidden_official_holidays(Request $request)
    {
        $attrs = $request->validate([
            'hidden_official_holidays' => 'nullable|array',
            'custom_holidays' => 'nullable|array',
            'recurring_holidays' => 'nullable|array',
        ]);

        $hiddenMap = $attrs['hidden_official_holidays'] ?? [];
        $customHolidayMap = $attrs['custom_holidays'] ?? [];
        $recurringHolidayMap = $attrs['recurring_holidays'] ?? [];
        $hiddenDates = collect($hiddenMap)
            ->filter(function ($names, $date) {
                return is_string($date)
                    && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
                    && is_array($names)
                    && !empty($names);
            })
            ->keys()
            ->values()
            ->all();

        $normalizedCustomHolidays = collect($customHolidayMap)
            ->filter(function ($names, $date) {
                return is_string($date)
                    && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
                    && is_array($names)
                    && !empty($names);
            })
            ->map(function ($names) {
                return array_values(array_filter(array_map(function ($name) {
                    return is_string($name) ? trim($name) : '';
                }, $names), fn ($name) => $name !== ''));
            })
            ->filter(fn ($names) => !empty($names))
            ->all();

        $normalizedRecurringHolidays = collect($recurringHolidayMap)
            ->filter(function ($names, $monthDay) {
                return is_string($monthDay)
                    && preg_match('/^\d{2}-\d{2}$/', $monthDay)
                    && is_array($names)
                    && !empty($names);
            })
            ->map(function ($names) {
                return array_values(array_filter(array_map(function ($name) {
                    return is_string($name) ? trim($name) : '';
                }, $names), fn ($name) => $name !== ''));
            })
            ->filter(fn ($names) => !empty($names))
            ->all();

        Storage::disk('local')->put('calendar_hidden_holidays.json', json_encode([
            'dates' => $hiddenDates,
            'updated_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT));

        Storage::disk('local')->put('calendar_holiday_config.json', json_encode([
            'hidden_official_holidays' => $hiddenMap,
            'custom_holidays' => $normalizedCustomHolidays,
            'recurring_holidays' => $normalizedRecurringHolidays,
            'updated_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT));

        if (!empty($hiddenDates)) {
            $holidayUploadNames = array_map(
                fn ($date) => "System Holiday Attendance {$date}",
                $hiddenDates
            );

            $holidayUploadIds = AttendanceUpload::query()
                ->whereIn('original_name', $holidayUploadNames)
                ->pluck('id');

            if ($holidayUploadIds->isNotEmpty()) {
                AttendanceRecord::query()
                    ->whereIn('attendance_upload_id', $holidayUploadIds)
                    ->delete();

                AttendanceUpload::query()
                    ->whereIn('id', $holidayUploadIds)
                    ->delete();
            }
        }

        return response()->json([
            'success' => true,
            'hidden_dates' => $hiddenDates,
        ]);
    }


    //STORE
    public function store_new_position(Request $request){
        Log::info($request);
        $attrs = $request->validate([
            'title' => 'required',
            'department' => 'required',
            'employment' => 'required',
            'collage_name' => 'required',
            'mode' => 'required',
            'description' => 'required',
            'responsibilities' => 'required',
            'requirements' => 'required',
            // 'min' => 'required',
            // 'max' => 'required',
            'level' => 'required',
            'location' => 'required',
            'skills' => 'required',
            'benefits' => 'required',
            'job_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'passionate' => 'required',
        ]);

        $store = OpenPosition::create([
            'title' => $attrs['title'],
            'department' => $attrs['department'],
            'employment' => $attrs['employment'],
            'work_mode' => $attrs['mode'],
            'collage_name' => $attrs['collage_name'],
            'job_description' => $attrs['description'],
            'responsibilities' => $attrs['responsibilities'],
            'requirements' => $attrs['requirements'],
            // 'min_salary' => $attrs['min'],
            // 'max_salary' => $attrs['max'],
            'experience_level' => $attrs['level'],
            'location' => $attrs['location'],
            'skills' => $attrs['skills'],
            'benifits' => $attrs['benefits'],
            'job_type' => $attrs['job_type'],
            'one' => $attrs['start_date'],
            'two' => $attrs['end_date'],
            'passionate' => $attrs['passionate'],
        ]);

        return redirect()->back()->with('success','Success Added Position');
    }

    public function store_interview(Request $request){ /// Update applicant status to "For Interview" when interview is scheduled
        Log::info($request);
        $attrs = $request->validate([
            'applicants_id' => 'required',
            'interview_type' => 'required',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'duration' => 'required',
            'interviewers' => 'required',
            'email_link' => 'required',
            'url' => 'nullable',
            'notes' => 'nullable',
        ]);

        $store = Interviewer::create([
            'applicant_id' => $attrs['applicants_id'],
            'interview_type' => $attrs['interview_type'],
            'date' => $attrs['date'],
            'time' => $attrs['time'],
            'duration' => $attrs['duration'],
            'interviewers' => $attrs['interviewers'],
            'email_link' => $attrs['email_link'],
            'url' => $attrs['url'],
            'notes' => $attrs['notes'],
        ]);

        // === APPLICANT STATUS UPDATE #1 === Store Interview Method
        // Updates applicant status based on interview type (Initial Interview or Final Interview)
        Applicant::where('id', $attrs['applicants_id'])->update([
            'application_status' => $this->resolveApplicantStatusFromInterviewType($attrs['interview_type']),
        ]);

        Mail::to($store->applicant->email)
                ->send(new ApplicationInterviewMail($store));

        return redirect()->back()->with('success','Success Added Interview');
    }

    public function store_star_ratings(Request $request){
        $attrs = $request->validate([
            'ratingId' => 'required',
            'rating' => 'required|string',
        ]);

        $review = Applicant::findOrFail($attrs['ratingId']);

        $review->update([
            'starRatings' => $attrs['rating'],
        ]);

        return redirect()->back()->with('success','Success Rating Store');
    }

    public function store_document(Request $request){
        Log::info($request);
        $attrs = $request->validate([
            'applicant_id' => 'nullable|exists:applicants,id',
            'user_id' => 'nullable|exists:users,id',
            'document_name' => 'required|string|max:255',
            'documents' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $applicant = null;
        if (!empty($attrs['applicant_id'])) {
            $applicant = Applicant::find((int) $attrs['applicant_id']);
        }
        if (!$applicant && !empty($attrs['user_id'])) {
            $applicant = Applicant::query()
                ->where('user_id', (int) $attrs['user_id'])
                ->orderByDesc('id')
                ->first();
        }
        if (!$applicant) {
            return back()->withErrors(['documents' => 'Applicant record not found for this employee.']);
        }

        $file = $request->file('documents');

        if (!$file || !$file->isValid()) {
            return back()->withErrors(['documents' => 'Invalid file upload.']);
        }

        $originalName = $file->getClientOriginalName();
        $mimeType     = $file->getMimeType();
        $size         = $file->getSize();

        $fileName = time() . '_' . $originalName;

        // Store file
        $filePath = $file->storeAs('uploads', $fileName, 'public');

        $saved = ApplicantDocument::create([
            'applicant_id' => $applicant->id,
            'type'         => $attrs['document_name'],
            'filename'     => $originalName,
            'filepath'     => $filePath, // already "uploads/filename"
            'mime_type'    => $mimeType,
            'size'         => $size,
        ]);
        if (!$saved || !$saved->id) {
            return back()->withErrors(['documents' => 'Document upload failed to save in database.']);
        }

        return back()->with('success', 'Document uploaded successfully.');

    }

    public function store_required_documents(Request $request)
    {
        $attrs = $request->validate([
            'applicant_id' => 'nullable|exists:applicants,id',
            'user_id' => 'nullable|exists:users,id',
            'required_documents' => 'nullable|string',
            'document_notice' => 'nullable|string|max:1000',
        ]);

        $requiredDocuments = collect(
            preg_split('/[\r\n,]+/', (string) ($attrs['required_documents'] ?? ''))
        )
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->unique(function ($item) {
                return strtolower($item);
            })
            ->values()
            ->all();

        $notice = trim((string) ($attrs['document_notice'] ?? ''));
        $applicant = null;
        if (!empty($attrs['applicant_id'])) {
            $applicant = Applicant::find((int) $attrs['applicant_id']);
        }
        if (!$applicant && !empty($attrs['user_id'])) {
            $applicant = Applicant::query()
                ->where('user_id', (int) $attrs['user_id'])
                ->orderByDesc('id')
                ->first();
        }
        if (!$applicant) {
            return back()->withErrors(['documents' => 'Applicant record not found for this employee.']);
        }
        $applicantId = (int) $applicant->id;

        $requiredPrefix = '__REQUIRED__::';
        $noticeType = '__NOTICE__';

        ApplicantDocument::query()
            ->where('applicant_id', $applicantId)
            ->where(function ($query) use ($requiredPrefix, $noticeType) {
                $query
                    ->where('type', 'like', $requiredPrefix.'%')
                    ->orWhere('type', $noticeType);
            })
            ->delete();

        foreach ($requiredDocuments as $requiredDocument) {
            ApplicantDocument::create([
                'applicant_id' => $applicantId,
                'filename' => 'Required Document',
                'filepath' => 'system/meta/required-document',
                'size' => 0,
                'mime_type' => 'text/plain',
                'type' => $requiredPrefix.$requiredDocument,
            ]);
        }

        if ($notice !== '') {
            ApplicantDocument::create([
                'applicant_id' => $applicantId,
                'filename' => $notice,
                'filepath' => 'system/meta/document-notice',
                'size' => 0,
                'mime_type' => 'text/plain',
                'type' => $noticeType,
            ]);
        }

        return back()->with('success', 'Required document notice saved.');
    }

    public function store_attendance_excel(Request $request){
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx|max:10240',
        ]);

        $file = $request->file('excel_file');

        if (!$file || !$file->isValid()) {
            return back()->withErrors(['excel_file' => 'Invalid file upload.']);
        }

        $originalName = $file->getClientOriginalName();
        $fileName = time().'_'.$originalName;
        $filePath = $file->storeAs('attendance_excels', $fileName, 'public');

        $attendanceUpload = AttendanceUpload::create([
            'original_name' => $originalName,
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'status' => 'Uploaded',
            'processed_rows' => 0,
            'uploaded_at' => Carbon::now('Asia/Manila'),
        ]);

        try {
            $absolutePath = Storage::disk('public')->path($attendanceUpload->file_path);
            $extension = pathinfo($attendanceUpload->file_path, PATHINFO_EXTENSION);
            $rows = $this->extractRowsFromExcel($absolutePath, $extension);
            $fallbackAttendanceDate = optional($attendanceUpload->uploaded_at)->format('Y-m-d');
            $records = $this->buildAttendanceRecords($rows, $attendanceUpload->id, $fallbackAttendanceDate);

            $processedRows = 0;
            DB::transaction(function () use ($attendanceUpload, $records, &$processedRows) {
                AttendanceRecord::where('attendance_upload_id', $attendanceUpload->id)->delete();

                if (!empty($records)) {
                    AttendanceRecord::insert($records);
                    $this->syncAttendanceRecordJobTypesForUpload($attendanceUpload->id);
                }

                $processedRows = count($records);
                $attendanceUpload->update([
                    'status' => 'Processed',
                    'processed_rows' => $processedRows,
                ]);
            });

            return back()->with('success', "Excel uploaded and scanned successfully. {$processedRows} attendance row(s) saved.");
        } catch (\Throwable $e) {
            Log::error('Attendance upload auto-scan failed: '.$e->getMessage());

            return back()->with('success', 'Excel file uploaded successfully. Click Scan to process and save to database.');
        }
    }

    private function extractRowsFromExcel(string $absolutePath, string $extension): array
    {
        $extension = strtolower($extension);

        if ($extension !== 'xlsx') {
            throw new \RuntimeException('Only .xlsx files are currently supported for attendance analysis.');
        }

        return $this->extractRowsFromXlsx($absolutePath);
    }

    private function extractRowsFromXlsx(string $absolutePath): array
    {
        if (!class_exists(\ZipArchive::class) && !class_exists(\PharData::class)) {
            throw new \RuntimeException('XLSX parsing requires ZipArchive or PharData support in PHP.');
        }

        $sharedStrings = [];
        $sharedStringsXml = $this->readXlsxEntry($absolutePath, 'xl/sharedStrings.xml');
        if ($sharedStringsXml !== false) {
            $xml = simplexml_load_string($sharedStringsXml);
            if ($xml && isset($xml->si)) {
                foreach ($xml->si as $item) {
                    if (isset($item->t)) {
                        $sharedStrings[] = trim((string) $item->t);
                        continue;
                    }

                    // Rich text values are split under r/t nodes.
                    $richText = '';
                    if (isset($item->r)) {
                        foreach ($item->r as $run) {
                            $richText .= (string) ($run->t ?? '');
                        }
                    }
                    $sharedStrings[] = trim($richText);
                }
            }
        }

        $sheetXml = $this->readXlsxEntry($absolutePath, 'xl/worksheets/sheet1.xml');
        if ($sheetXml === false) {
            foreach ($this->listXlsxWorksheetEntries($absolutePath) as $worksheetEntry) {
                $sheetXml = $this->readXlsxEntry($absolutePath, $worksheetEntry);
                if ($sheetXml !== false) {
                    break;
                }
            }
        }

        if ($sheetXml === false) {
            throw new \RuntimeException('No worksheet found in xlsx.');
        }

        $sheet = simplexml_load_string($sheetXml);
        $rowsNode = $sheet ? $sheet->xpath("//*[local-name()='sheetData']/*[local-name()='row']") : false;
        if (!$sheet || $rowsNode === false) {
            throw new \RuntimeException('Invalid worksheet data.');
        }

        $rows = [];
        foreach ($rowsNode as $row) {
            $rowData = [];
            $cells = $row->xpath("./*[local-name()='c']") ?: [];
            foreach ($cells as $cell) {
                $reference = (string) $cell['r'];
                $column = preg_replace('/\d+/', '', $reference);
                $type = (string) $cell['t'];
                $value = null;

                if ($type === 's') {
                    $index = (int) ($cell->v ?? 0);
                    $value = $sharedStrings[$index] ?? null;
                } elseif ($type === 'inlineStr') {
                    $value = trim((string) ($cell->is->t ?? ''));
                } else {
                    $value = isset($cell->v) ? trim((string) $cell->v) : null;
                }

                if ($column !== '' && $value !== null && $value !== '') {
                    $rowData[$column] = $value;
                }
            }

            if (!empty($rowData)) {
                $rows[] = $rowData;
            }
        }

        if (count($rows) < 2) {
            return [];
        }

        $headerIndex = $this->detectHeaderRowIndex($rows);
        if ($headerIndex === null) {
            return [];
        }

        $headerRow = $rows[$headerIndex];
        $rows = array_slice($rows, $headerIndex + 1);
        $headers = [];
        foreach ($headerRow as $column => $headerText) {
            $headers[$column] = $this->normalizeHeader((string) $headerText);
        }

        $mapped = [];
        foreach ($rows as $row) {
            $item = [];
            foreach ($headers as $column => $header) {
                if ($header === '') {
                    continue;
                }
                $item[$header] = $row[$column] ?? null;
            }

            if (!empty(array_filter($item, fn ($value) => $value !== null && $value !== ''))) {
                $mapped[] = $item;
            }
        }

        return $mapped;
    }

    private function buildAttendanceRecords(array $rows, int $uploadId, ?string $fallbackAttendanceDate = null): array  // Accepts rows with either separate morning/afternoon columns or raw punch logs; returns normalized attendance record data ready for database insertion.
    {
        $rows = $this->expandRawPunchRows($rows);

        $records = [];
        $now = now();
        $recordColumns = $this->getAttendanceRecordColumnLookup();
        $knownEmployeeIdLookup = $this->buildKnownEmployeeIdLookupFromRows($rows);
        $employeeJobTypeMap = $this->buildEmployeeJobTypeMapFromRows($rows);
        $employeeDepartmentMap = $this->buildEmployeeDepartmentMapFromRows($rows);
        $availableKeys = $this->collectAvailableKeys($rows);
        $hasMorningOutColumn = $this->hasAnyKey($availableKeys, ['morning_out', 'am_out', 'time_out_am', 'morning_time_out', 'out_am']);
        $hasAfternoonOutColumn = $this->hasAnyKey($availableKeys, ['afternoon_out', 'pm_out', 'time_out_pm', 'afternoon_time_out', 'out_pm']);

        foreach ($rows as $row) {
            $employeeId = $this->pickValue($row, [
                'employee_id', 'employeeid', 'id_no', 'idno', 'emp_id', 'empid',
            ]);
            $employeeName = $this->pickValue($row, [
                'name', 'employee_name', 'full_name', 'employee',
            ]);
            $mainGate = $this->pickValue($row, [
                'main_gate', 'gate', 'entry_point', 'entrance',
            ]);

            if (!$employeeId) {
                continue;
            }
            $normalizedEmployeeId = $this->normalizeEmployeeId($employeeId);
            if ($normalizedEmployeeId === '' || !isset($knownEmployeeIdLookup[$normalizedEmployeeId])) {
                continue;
            }

            $attendanceDateRaw = $this->pickValue($row, ['date', 'attendance_date']);
            $morningInRaw = $this->pickValue($row, ['morning_in', 'am_in', 'time_in_am', 'morning_time_in', 'in_am', 'am_time', 'am']);
            $morningOutRaw = $this->pickValue($row, ['morning_out', 'am_out', 'time_out_am', 'morning_time_out', 'out_am']);
            $afternoonInRaw = $this->pickValue($row, ['afternoon_in', 'pm_in', 'time_in_pm', 'afternoon_time_in', 'in_pm', 'pm_time', 'pm']);
            $afternoonOutRaw = $this->pickValue($row, ['afternoon_out', 'pm_out', 'time_out_pm', 'afternoon_time_out', 'out_pm']);

            $attendanceDate = $this->normalizeDate($attendanceDateRaw) ?: $fallbackAttendanceDate;
            $morningIn = $this->normalizeTime($morningInRaw);
            $morningOut = $this->normalizeTime($morningOutRaw);
            $afternoonIn = $this->normalizeTime($afternoonInRaw);
            $afternoonOut = $this->normalizeTime($afternoonOutRaw);

            $missing = [];
            if (!$morningIn) {
                $missing[] = 'morning_in';
            }
            if ($hasMorningOutColumn && !$morningOut) {
                $missing[] = 'morning_out';
            }
            if (!$afternoonIn) {
                $missing[] = 'afternoon_in';
            }
            if ($hasAfternoonOutColumn && !$afternoonOut) {
                $missing[] = 'afternoon_out';
            }

            $lateMinutes = $this->calculateLateMinutes($morningIn, $afternoonIn);
            $actualTimeLogs = array_filter([
                'morning_in' => $morningIn,
                'morning_out' => $morningOut,
                'afternoon_in' => $afternoonIn,
                'afternoon_out' => $afternoonOut,
            ], fn ($value) => !empty($value));

            // Mark absent only when all four time logs are missing.
            $isAbsent = count($actualTimeLogs) === 0;
            $isTardy = !$isAbsent && $lateMinutes > 0;

            $record = [
                'attendance_upload_id' => $uploadId,
                'employee_id' => $normalizedEmployeeId,
                'attendance_date' => $attendanceDate,
                'morning_in' => $morningIn,
                'morning_out' => $morningOut,
                'afternoon_in' => $afternoonIn,
                'afternoon_out' => $afternoonOut,
                'late_minutes' => $lateMinutes,
                'missing_time_logs' => !empty($missing) ? json_encode($missing) : null,
                'is_absent' => $isAbsent,
                'is_tardy' => $isTardy,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Keep compatibility with databases that have not yet run the add_name/main_gate migration.
            if (isset($recordColumns['employee_name'])) {
                $record['employee_name'] = $employeeName ? (string) $employeeName : null;
            }
            if (isset($recordColumns['main_gate'])) {
                $record['main_gate'] = $mainGate ? (string) $mainGate : null;
            }
            if (isset($recordColumns['job_type'])) {
                $record['job_type'] = $employeeJobTypeMap[$normalizedEmployeeId] ?? null;
            }
            if (isset($recordColumns['department'])) {
                $record['department'] = $employeeDepartmentMap[$normalizedEmployeeId] ?? null;
            }

            $records[] = $record;
        }

        return $records;
    }

    private function buildKnownEmployeeIdLookupFromRows(array $rows): array
    {
        $employeeIds = collect($rows)
            ->map(function ($row) {
                $employeeId = $this->pickValue($row, [
                    'employee_id', 'employeeid', 'id_no', 'idno', 'emp_id', 'empid',
                ]);

                return $this->normalizeEmployeeId($employeeId);
            })
            ->filter()
            ->unique()
            ->values();

        if ($employeeIds->isEmpty()) {
            return [];
        }

        return Employee::query()
            ->select(['employee_id'])
            ->whereIn('employee_id', $employeeIds->all())
            ->get()
            ->map(function ($employee) {
                return $this->normalizeEmployeeId($employee->employee_id);
            })
            ->filter()
            ->flip()
            ->map(fn () => true)
            ->all();
    }

    private function buildEmployeeJobTypeMapFromRows(array $rows): array
    {
        $employeeIds = collect($rows)
            ->map(function ($row) {
                $employeeId = $this->pickValue($row, [
                    'employee_id', 'employeeid', 'id_no', 'idno', 'emp_id', 'empid',
                ]);

                return $this->normalizeEmployeeId($employeeId);
            })
            ->filter()
            ->unique()
            ->values();

        if ($employeeIds->isEmpty()) {
            return [];
        }

        if (!Schema::hasColumn('employees', 'job_type')) {
            return [];
        }

        $this->syncEmployeeJobTypesFromOpenPositions($employeeIds->all());

        return Employee::query()
            ->select(['employee_id', 'job_type'])
            ->whereIn('employee_id', $employeeIds->all())
            ->get()
            ->mapWithKeys(function ($employee) {
                $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                if ($employeeId === '') {
                    return [];
                }

                $jobType = $this->normalizeEmployeeJobType($employee->job_type);

                return [$employeeId => $jobType];
            })
            ->all();
    }

    private function syncEmployeeJobTypesFromOpenPositions(array $employeeIds = []): void
    {
        if (!Schema::hasColumn('employees', 'job_type')) {
            return;
        }

        $employees = Employee::query()
            ->select(['id', 'user_id', 'employee_id', 'job_type'])
            ->whereNotNull('user_id')
            ->when(!empty($employeeIds), function ($query) use ($employeeIds) {
                $query->whereIn('employee_id', $employeeIds);
            })
            ->get();

        if ($employees->isEmpty()) {
            return;
        }

        $userIds = $employees->pluck('user_id')->filter()->unique()->values();
        if ($userIds->isEmpty()) {
            return;
        }

        $latestApplicantsByUser = Applicant::query()
            ->select(['id', 'user_id', 'open_position_id'])
            ->whereIn('user_id', $userIds->all())
            ->whereNotNull('open_position_id')
            ->orderByDesc('id')
            ->get()
            ->unique('user_id')
            ->keyBy('user_id');

        if ($latestApplicantsByUser->isEmpty()) {
            return;
        }

        $openPositionIds = $latestApplicantsByUser
            ->pluck('open_position_id')
            ->filter()
            ->unique()
            ->values();

        if ($openPositionIds->isEmpty()) {
            return;
        }

        $openPositionJobTypeMap = OpenPosition::query()
            ->whereIn('id', $openPositionIds->all())
            ->pluck('job_type', 'id');

        foreach ($employees as $employee) {
            $openPositionId = optional($latestApplicantsByUser->get($employee->user_id))->open_position_id;
            if (!$openPositionId) {
                continue;
            }

            $jobTypeFromOpenPosition = $this->normalizeEmployeeJobType($openPositionJobTypeMap->get($openPositionId));
            if (!$jobTypeFromOpenPosition) {
                continue;
            }

            if ($this->normalizeEmployeeJobType($employee->job_type) === $jobTypeFromOpenPosition) {
                continue;
            }

            Employee::query()
                ->whereKey($employee->id)
                ->update(['job_type' => $jobTypeFromOpenPosition]);
        }
    }

    private function resolveJobTypeFromOpenPositionForUser($userId): ?string
    {
        if (!$userId) {
            return null;
        }

        $applicant = Applicant::query()
            ->select(['open_position_id'])
            ->where('user_id', $userId)
            ->whereNotNull('open_position_id')
            ->orderByDesc('id')
            ->first();

        if (!$applicant || !$applicant->open_position_id) {
            return null;
        }

        $jobType = OpenPosition::query()
            ->whereKey($applicant->open_position_id)
            ->value('job_type');

        return $this->normalizeEmployeeJobType($jobType);
    }

    private function syncAttendanceRecordJobTypesForUpload(int $uploadId): void
    {
        if (!Schema::hasColumn('attendance_records', 'job_type') || !Schema::hasColumn('employees', 'job_type')) {
            return;
        }

        $records = AttendanceRecord::query()
            ->select(['id', 'employee_id', 'job_type'])
            ->where('attendance_upload_id', $uploadId)
            ->get();

        if ($records->isEmpty()) {
            return;
        }

        $employeeIds = $records
            ->pluck('employee_id')
            ->map(fn ($value) => $this->normalizeEmployeeId($value))
            ->filter()
            ->unique()
            ->values();

        if ($employeeIds->isEmpty()) {
            return;
        }

        $employeeJobTypeMap = Employee::query()
            ->select(['employee_id', 'job_type'])
            ->whereIn('employee_id', $employeeIds->all())
            ->get()
            ->mapWithKeys(function ($employee) {
                $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                if ($employeeId === '') {
                    return [];
                }

                return [$employeeId => $this->normalizeEmployeeJobType($employee->job_type)];
            });

        foreach ($records as $record) {
            $employeeId = $this->normalizeEmployeeId($record->employee_id);
            if ($employeeId === '') {
                continue;
            }

            $targetJobType = $employeeJobTypeMap->get($employeeId);
            if (!$targetJobType) {
                continue;
            }

            if ($this->normalizeEmployeeJobType($record->job_type) === $targetJobType) {
                continue;
            }

            AttendanceRecord::query()
                ->whereKey($record->id)
                ->update(['job_type' => $targetJobType]);
        }
    }

    private function getAttendanceRecordColumnLookup(): array
    {
        static $columns = null;

        if ($columns === null) {
            $columns = array_flip(Schema::getColumnListing('attendance_records'));
        }

        return $columns;
    }

    private function buildEmployeeDepartmentMapFromRows(array $rows): array
    {
        $employeeIds = collect($rows)
            ->map(function ($row) {
                $employeeId = $this->pickValue($row, [
                    'employee_id', 'employeeid', 'id_no', 'idno', 'emp_id', 'empid',
                ]);

                return $this->normalizeEmployeeId($employeeId);
            })
            ->filter()
            ->unique()
            ->values();

        if ($employeeIds->isEmpty()) {
            return [];
        }

        if (!Schema::hasColumn('employees', 'department')) {
            return [];
        }

        return Employee::query()
            ->select(['employee_id', 'department'])
            ->whereIn('employee_id', $employeeIds->all())
            ->get()
            ->mapWithKeys(function ($employee) {
                $employeeId = $this->normalizeEmployeeId($employee->employee_id);
                if ($employeeId === '') {
                    return [];
                }

                return [$employeeId => $employee->department ? (string) $employee->department : null];
            })
            ->all();
    }

    private function readXlsxEntry(string $absolutePath, string $entry): string|false
    {
        if (class_exists(\ZipArchive::class)) {
            $zip = new \ZipArchive();
            if ($zip->open($absolutePath) === true) {
                $contents = $zip->getFromName($entry);
                $zip->close();
                if ($contents !== false) {
                    return $contents;
                }
            }
        }

        if (class_exists(\PharData::class)) {
            $pharEntry = 'phar://'.$absolutePath.'/'.$entry;
            if (is_file($pharEntry)) {
                $contents = @file_get_contents($pharEntry);
                if ($contents !== false) {
                    return $contents;
                }
            }
        }

        return false;
    }

    private function listXlsxWorksheetEntries(string $absolutePath): array
    {
        $entries = [];

        if (class_exists(\ZipArchive::class)) {
            $zip = new \ZipArchive();
            if ($zip->open($absolutePath) === true) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $name = $zip->getNameIndex($i);
                    if ($name && str_starts_with($name, 'xl/worksheets/') && str_ends_with($name, '.xml')) {
                        $entries[] = $name;
                    }
                }
                $zip->close();
            }
        } elseif (class_exists(\PharData::class)) {
            try {
                $phar = new \PharData($absolutePath);
                $prefix = 'phar://'.$absolutePath.'/';
                foreach (new \RecursiveIteratorIterator($phar) as $filePath => $fileInfo) {
                    $entry = str_replace($prefix, '', str_replace('\\', '/', (string) $filePath));
                    if (str_starts_with($entry, 'xl/worksheets/') && str_ends_with($entry, '.xml')) {
                        $entries[] = $entry;
                    }
                }
            } catch (\Throwable $e) {
                // Keep empty result; caller handles missing worksheet.
            }
        }

        sort($entries);
        return $entries;
    }

    private function detectHeaderRowIndex(array $rows): ?int
    {
        $sample = array_slice($rows, 0, 25);
        foreach ($sample as $index => $row) {
            $headers = [];
            foreach ($row as $value) {
                $headers[] = $this->normalizeHeader((string) $value);
            }

            $hasEmployeeId = $this->hasAnyKey($headers, ['employee_id', 'employeeid', 'id_no', 'idno', 'emp_id', 'empid']);
            $hasAmPmColumns = $this->hasAnyKey($headers, ['am_time', 'am_in', 'morning_in', 'am'])
                && $this->hasAnyKey($headers, ['pm_time', 'pm_in', 'afternoon_in', 'pm']);
            $hasRawPunchColumns = $this->hasAnyKey($headers, ['date', 'attendance_date'])
                && $this->hasAnyKey($headers, ['time'])
                && $this->hasAnyKey($headers, ['type']);

            if ($hasEmployeeId && ($hasAmPmColumns || $hasRawPunchColumns)) {
                return $index;
            }
        }

        return null;
    }

    private function expandRawPunchRows(array $rows): array
    {
        $keys = $this->collectAvailableKeys($rows);
        $hasAmPmColumns = $this->hasAnyKey($keys, ['am_time', 'am_in', 'morning_in', 'am'])
            && $this->hasAnyKey($keys, ['pm_time', 'pm_in', 'afternoon_in', 'pm']);
        if ($hasAmPmColumns) {
            return $rows;
        }

        $hasRawPunchColumns = $this->hasAnyKey($keys, ['date', 'attendance_date'])
            && $this->hasAnyKey($keys, ['time'])
            && $this->hasAnyKey($keys, ['type']);
        if (!$hasRawPunchColumns) {
            return $rows;
        }

        $grouped = [];
        foreach ($rows as $row) {
            $employeeId = $this->pickValue($row, ['employee_id', 'employeeid', 'id_no', 'idno', 'emp_id', 'empid']);
            $date = $this->normalizeDate($this->pickValue($row, ['attendance_date', 'date']));
            $time = $this->normalizeTime($this->pickValue($row, ['time']));
            $type = strtoupper(trim((string) $this->pickValue($row, ['type', 'log_type', 'status'])));

            if (!$employeeId || !$date || !$time || !in_array($type, ['IN', 'OUT'], true)) {
                continue;
            }

            $key = $employeeId.'|'.$date;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'employee_id' => (string) $employeeId,
                    'employee_name' => $this->pickValue($row, ['name', 'employee_name', 'full_name', 'employee']),
                    'main_gate' => $this->pickValue($row, ['main_gate', 'gate', 'entry_point', 'entrance']),
                    'attendance_date' => $date,
                    'morning_in' => null,
                    'morning_out' => null,
                    'afternoon_in' => null,
                    'afternoon_out' => null,
                ];
            }

            if (!$grouped[$key]['employee_name']) {
                $grouped[$key]['employee_name'] = $this->pickValue($row, ['name', 'employee_name', 'full_name', 'employee']);
            }

            if (!$grouped[$key]['main_gate']) {
                $grouped[$key]['main_gate'] = $this->pickValue($row, ['main_gate', 'gate', 'entry_point', 'entrance']);
            }

            if ($type === 'IN') {
                if ($time < '12:00:00') {
                    if (!$grouped[$key]['morning_in'] || $time < $grouped[$key]['morning_in']) {
                        $grouped[$key]['morning_in'] = $time;
                    }
                } else {
                    if (!$grouped[$key]['afternoon_in'] || $time < $grouped[$key]['afternoon_in']) {
                        $grouped[$key]['afternoon_in'] = $time;
                    }
                }
            } else {
                if ($time <= '12:30:00') {
                    if (!$grouped[$key]['morning_out'] || $time > $grouped[$key]['morning_out']) {
                        $grouped[$key]['morning_out'] = $time;
                    }
                } else {
                    if (!$grouped[$key]['afternoon_out'] || $time > $grouped[$key]['afternoon_out']) {
                        $grouped[$key]['afternoon_out'] = $time;
                    }
                }
            }
        }

        return array_values($grouped);
    }

    private function collectAvailableKeys(array $rows): array
    {
        $keys = [];
        foreach ($rows as $row) {
            foreach (array_keys($row) as $key) {
                $keys[$key] = true;
            }
        }

        return array_keys($keys);
    }

    private function hasAnyKey(array $keys, array $candidates): bool
    {
        $lookup = array_fill_keys($keys, true);
        foreach ($candidates as $candidate) {
            if (isset($lookup[$candidate])) {
                return true;
            }
        }

        return false;
    }

    private function pickValue(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row) && $row[$key] !== null && $row[$key] !== '') {
                return (string) $row[$key];
            }
        }

        return null;
    }

    private function normalizeHeader(string $value): string
    {
        $normalized = strtolower(trim($value));
        $normalized = str_replace(['(', ')', '.', '-', '/'], ' ', $normalized);
        $normalized = preg_replace('/\s+/', '_', $normalized);
        $normalized = trim($normalized, '_ ');

        return $normalized;
    }

    private function normalizeDate(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        if (is_numeric($value)) {
            $serial = (float) $value;
            $datePart = (int) floor($serial);
            if ($datePart > 0) {
                return Carbon::create(1899, 12, 30)->addDays($datePart)->toDateString();
            }
        }

        $formats = ['Y-m-d', 'm/d/Y', 'd/m/Y', 'm-d-Y', 'd-m-Y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, trim($value))->toDateString();
            } catch (\Throwable $e) {
            }
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeTime(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        if (is_numeric($value)) {
            $numeric = (float) $value;
            $fraction = $numeric > 1 ? $numeric - floor($numeric) : $numeric;
            if ($fraction >= 0 && $fraction < 1) {
                $seconds = (int) round($fraction * 86400);
                $hours = intdiv($seconds, 3600);
                $minutes = intdiv($seconds % 3600, 60);
                return sprintf('%02d:%02d:00', $hours, $minutes);
            }
        }

        $formats = ['H:i', 'H:i:s', 'g:i A', 'g:iA', 'h:i A', 'h:iA'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, trim($value))->format('H:i:s');
            } catch (\Throwable $e) {
            }
        }

        try {
            return Carbon::parse($value)->format('H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function calculateLateMinutes(?string $morningIn, ?string $afternoonIn): int
    {
        $late = 0;

        if ($morningIn) {
            $morningActual = Carbon::createFromFormat('H:i:s', $morningIn);
            $morningExpected = Carbon::createFromFormat('H:i:s', '08:00:00');
            $morningGraceEnd = Carbon::createFromFormat('H:i:s', '08:15:00');
            if ($morningActual->greaterThan($morningGraceEnd)) {
                $late += $morningExpected->diffInMinutes($morningActual);
            }
        }

        if ($afternoonIn) {
            $afternoonActual = Carbon::createFromFormat('H:i:s', $afternoonIn);
            $afternoonExpected = Carbon::createFromFormat('H:i:s', '13:00:00');
            $afternoonGraceEnd = Carbon::createFromFormat('H:i:s', '13:15:00');
            if ($afternoonActual->greaterThan($afternoonGraceEnd)) {
                $late += $afternoonExpected->diffInMinutes($afternoonActual);
            }
        }

        return $late;
    }

    //UPDATE
    public function update_position(Request $request, $id){
        Log::info($request);
        $attrs = $request->validate([
            'title' => 'required',
            'department' => 'required',
            'employment' => 'required',
            'collage_name' => 'required',
            //'mode' => 'required',
            'job_description' => 'required',
            'responsibilities' => 'required',
            'requirements' => 'required',
            // 'min' => 'required',
            // 'max' => 'required',
            'experience_level' => 'required',
            'location' => 'required',
            'skills' => 'required',
            //'benefits' => 'required',
            'job_type' => 'required',
            'one' => 'required|date',
            'two' => 'required|date',
            'passionate' => 'required',
        ]);

        $open = OpenPosition::findOrFail($id);
        $normalizedJobType = $this->normalizeEmployeeJobType($attrs['job_type']);

        $open->update([
            'title' => $attrs['title'],
            'department' => $attrs['department'],
            'employment' => $attrs['employment'],
            //'work_mode' => $attrs['mode'],
            'collage_name' => $attrs['collage_name'],
            'job_description' => $attrs['job_description'],
            'responsibilities' => $attrs['responsibilities'],
            'requirements' => $attrs['requirements'],
            // 'min_salary' => $attrs['min'],
            // 'max_salary' => $attrs['max'],
            'experience_level' => $attrs['experience_level'],
            'location' => $attrs['location'],
            'skills' => $attrs['skills'],
            //'benifits' => $attrs['benefits'],
            'job_type' => $normalizedJobType,
            'one' => $attrs['one'],
            'two' => $attrs['two'],
            'passionate' => $attrs['passionate'],
        ]);

        // Keep employee records aligned with the updated open-position job type.
        if (Schema::hasColumn('employees', 'job_type')) {
            $relatedUserIds = Applicant::query()
                ->where('open_position_id', $open->id)
                ->whereNotNull('user_id')
                ->pluck('user_id');

            if ($relatedUserIds->isNotEmpty()) {
                Employee::query()
                    ->whereIn('user_id', $relatedUserIds)
                    ->update(['job_type' => $normalizedJobType]);
            }
        }

        return redirect()->route('admin.adminPosition')->with('success','Success Added Position');
    }

    // === APPLICANT STATUS UPDATE #2 === Direct Status Update Method
    // Allows direct manual update of applicant status from request
    public function update_application_status(Request $request){
        $attrs = $request->validate([
            'reviewId' => 'required',
            'status' => 'required|string',
        ]);

        $review = Applicant::findOrFail($attrs['reviewId']);

        $review->update([
            'application_status' => $attrs['status'],
        ]);

        Mail::to($review->email)
                ->send(new ApplicationUpdatedMail($review));

        return redirect()->back()->with('success','Success Update Application Status');
    }

    public function updated_interview(Request $request){
        Log::info($request);
        $attrs = $request->validate([
            'interviewId' => 'required',
            'applicantId' => 'required',
            'interview_type' => 'required',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i,H:i:s',
            'duration' => 'required',
            'interviewers' => 'required',
            'email_link' => 'required',
            'url' => 'nullable',
            'notes' => 'nullable',
        ]);

        $interview = Interviewer::findOrFail($attrs['interviewId']);

        $interview->update([
            'applicant_id' => $attrs['applicantId'],
            'interview_type' => $attrs['interview_type'],
            'date' => $attrs['date'],
            'time' => $attrs['time'],
            'duration' => $attrs['duration'],
            'interviewers' => $attrs['interviewers'],
            'email_link' => $attrs['email_link'],
            'url' => $attrs['url'],
            'notes' => $attrs['notes'],
        ]);

        // === APPLICANT STATUS UPDATE #3 === Updated Interview Method
        // Updates applicant status when an existing interview is modified
        Applicant::where('id', $attrs['applicantId'])->update([
            'application_status' => $this->resolveApplicantStatusFromInterviewType($attrs['interview_type']),
        ]);

        // Mail::to($store->applicant->email)
        //         ->send(new ApplicationInterviewMail($store));

        return redirect()->back()->with('success','Success Added Interview');
    }

    private function resolveApplicantStatusFromInterviewType(string $interviewType): string
    {
        return strcasecmp(trim($interviewType), 'Final Interview') === 0
            ? 'Final Interview'
            : 'Initial Interview';
    }

    public function update_employee($id){


        $open = User::findOrFail($id);

        $open->update([
            'status' => 'Approved',
        ]);

        return redirect()->back()->with('success','Employee can now login');
    }

    public function update_general_profile(Request $request){
        $attrs = $request->validate([
            'user_id' => 'required|exists:users,id',
            'first' => 'required|string|max:255',
            'middle' => 'nullable|string|max:255',
            'last' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,'.$request->input('user_id'),
            'employee_id' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:50',
            'contact_number' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|max:50',
            'barangay' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:255',
            'SSS' => 'nullable|string|max:255',
            'TIN' => 'nullable|string|max:255',
            'PhilHealth' => 'nullable|string|max:255',
            'MID' => 'nullable|string|max:255',
            'RTN' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($attrs['user_id']);

        $userPayload = [
            'first_name' => $attrs['first'],
            'middle_name' => $attrs['middle'] ?? null,
            'last_name' => $attrs['last'],
        ];

        if (!empty($attrs['email'])) {
            $userPayload['email'] = $attrs['email'];
        }

        $user->update($userPayload);

        $addressParts = array_filter([
            $attrs['barangay'] ?? null,
            $attrs['municipality'] ?? null,
            $attrs['province'] ?? null,
        ], function ($value) {
            return filled($value);
        });

        $employeePayload = [
            'employee_id' => $attrs['employee_id'] ?? null,
            'account_number' => $attrs['account_number'] ?? null,
            'sex' => $attrs['gender'] ?? null,
            'contact_number' => $attrs['contact_number'] ?? null,
            'birthday' => $attrs['birthday'] ?? null,
            'position' => $attrs['position'] ?? null,
            'department' => $attrs['department'] ?? null,
            'address' => count($addressParts) ? implode(', ', $addressParts) : null,
            'emergency_contact_name' => $attrs['emergency_contact_name'] ?? null,
            'emergency_contact_relationship' => $attrs['emergency_contact_relationship'] ?? null,
            'emergency_contact_number' => $attrs['emergency_contact_number'] ?? null,
        ];

        if (Schema::hasColumn('employees', 'job_type')) {
            $employeePayload['job_type'] = $this->resolveJobTypeFromOpenPositionForUser($attrs['user_id'])
                ?? (array_key_exists('job_type', $attrs)
                    ? $this->normalizeEmployeeJobType($attrs['job_type'])
                    : null);
        }

        Employee::updateOrCreate(
            ['user_id' => $attrs['user_id']],
            $employeePayload
        );

        Government::updateOrCreate(
            ['user_id' => $attrs['user_id']],
            [
                'SSS' => $attrs['SSS'] ?? null,
                'TIN' => $attrs['TIN'] ?? null,
                'PhilHealth' => $attrs['PhilHealth'] ?? null,
                'MID' => $attrs['MID'] ?? null,
                'RTN' => $attrs['RTN'] ?? null,
            ]
        );

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function update_leave_request_status($id, Request $request)
    {
        $attrs = $request->validate([
            'status' => 'required|string|in:Approved,Rejected',
            'month' => 'nullable|string',
            'redirect_back' => 'nullable|boolean',
        ]);

        $leaveApplication = LeaveApplication::findOrFail($id);
        $previousStatus = strtolower(trim((string) ($leaveApplication->status ?? '')));
        $newStatus = trim((string) $attrs['status']);

        DB::transaction(function () use ($leaveApplication, $newStatus, $previousStatus) {
            $leaveApplication->update([
                'status' => $newStatus,
            ]);

            if (strcasecmp($newStatus, 'Approved') === 0) {
                $this->syncAttendanceRecordsForApprovedLeave($leaveApplication->fresh());
            } elseif (strcasecmp($newStatus, 'Rejected') === 0 && $previousStatus === 'approved') {
                $this->deleteGeneratedLeaveAttendanceRecords($leaveApplication);
            }
        });

        $month = trim((string) ($attrs['month'] ?? ''));
        $query = [];
        if ($month !== '') {
            $query['month'] = $month;
        }

        if ((bool) ($attrs['redirect_back'] ?? false)) {
            return redirect()->back()->with('success', 'Leave request status updated.');
        }

        return redirect()->route('admin.adminLeaveManagement', $query)
            ->with('success', 'Leave request status updated.');
    }

    public function store_resignation(Request $request)
    {
        $attrs = $request->validate([
            'employee_user_id' => 'required|exists:users,id',
            'submitted_at' => 'required|date',
            'effective_date' => 'required|date|after_or_equal:submitted_at',
            'reason' => 'nullable|string|max:4000',
        ]);

        $employeeUser = User::query()
            ->with('employee')
            ->findOrFail((int) $attrs['employee_user_id']);

        if (strcasecmp((string) ($employeeUser->role ?? ''), 'Employee') !== 0) {
            return redirect()->back()->with('error', 'Selected account is not an employee.');
        }

        $employeeName = trim(implode(' ', array_filter([
            trim((string) ($employeeUser->first_name ?? '')),
            trim((string) ($employeeUser->middle_name ?? '')),
            trim((string) ($employeeUser->last_name ?? '')),
        ])));

        Resignation::create([
            'user_id' => $employeeUser->id,
            'employee_id' => (string) ($employeeUser->employee?->employee_id ?? ''),
            'employee_name' => $employeeName !== '' ? $employeeName : (string) ($employeeUser->email ?? 'Unknown Employee'),
            'department' => (string) ($employeeUser->employee?->department ?? ''),
            'position' => (string) ($employeeUser->employee?->position ?? ''),
            'submitted_at' => $attrs['submitted_at'],
            'effective_date' => $attrs['effective_date'],
            'reason' => trim((string) ($attrs['reason'] ?? '')),
            'status' => 'Pending',
        ]);

        return redirect()->route('admin.adminResignations')
            ->with('success', 'Resignation record saved.');
    }

    public function update_resignation_status($id, Request $request)
    {
        $attrs = $request->validate([
            'status' => 'required|string|in:Pending,Approved,Rejected,Completed,Cancelled',
            'admin_note' => 'nullable|string|max:4000',
        ]);

        $resignation = Resignation::findOrFail($id);
        $status = trim((string) $attrs['status']);

        $updatePayload = [
            'status' => $status,
            'admin_note' => trim((string) ($attrs['admin_note'] ?? '')),
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ];

        // On approval, store a fresh snapshot of employee identity fields
        // in the resignation record for audit/history purposes.
        if (strcasecmp($status, 'Approved') === 0 && !empty($resignation->user_id)) {
            $employeeUser = User::query()
                ->with('employee')
                ->find($resignation->user_id);

            if ($employeeUser) {
                $employeeName = trim(implode(' ', array_filter([
                    trim((string) ($employeeUser->first_name ?? '')),
                    trim((string) ($employeeUser->middle_name ?? '')),
                    trim((string) ($employeeUser->last_name ?? '')),
                ])));

                $updatePayload['employee_id'] = (string) ($employeeUser->employee?->employee_id ?? $resignation->employee_id ?? '');
                $updatePayload['employee_name'] = $employeeName !== ''
                    ? $employeeName
                    : (string) ($employeeUser->email ?? $resignation->employee_name ?? 'Unknown Employee');
                $updatePayload['department'] = (string) ($employeeUser->employee?->department ?? $resignation->department ?? '');
                $updatePayload['position'] = (string) ($employeeUser->employee?->position ?? $resignation->position ?? '');
            }
        }

        $resignation->update($updatePayload);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Resignation status updated.',
                'id' => (int) $resignation->id,
                'status' => $status,
                'statusCounts' => [
                    'Pending' => (int) Resignation::query()->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['pending'])->count(),
                    'Approved' => (int) Resignation::query()->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['approved'])->count(),
                    'Rejected' => (int) Resignation::query()->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['rejected'])->count(),
                    'Cancelled' => (int) Resignation::query()->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['cancelled'])->count(),
                ],
            ]);
        }

        return redirect()->route('admin.adminResignations')
            ->with('success', 'Resignation status updated.');
    }

    private function syncAttendanceRecordsForApprovedLeave(LeaveApplication $leaveApplication): void
    {
        $startDate = $leaveApplication->filing_date
            ? Carbon::parse($leaveApplication->filing_date)->startOfDay()
            : Carbon::parse($leaveApplication->created_at)->startOfDay();

        $totalRequestedDays = (float) ($leaveApplication->number_of_working_days ?? 0);
        if ($totalRequestedDays <= 0) {
            $totalRequestedDays = max(
                (float) ($leaveApplication->applied_total ?? 0),
                (float) ($leaveApplication->days_with_pay ?? 0),
                (float) ($leaveApplication->days_without_pay ?? 0)
            );
        }

        $withPayDays = max((int) ceil((float) ($leaveApplication->days_with_pay ?? 0)), 0);
        $withoutPayDays = max((int) ceil((float) ($leaveApplication->days_without_pay ?? 0)), 0);
        $requestedDaysCount = max((int) ceil($totalRequestedDays), 0);

        if ($withPayDays + $withoutPayDays === 0 && $requestedDaysCount > 0) {
            $withPayDays = $requestedDaysCount;
        }

        if ($requestedDaysCount > ($withPayDays + $withoutPayDays)) {
            $withoutPayDays += $requestedDaysCount - ($withPayDays + $withoutPayDays);
        }

        $totalDays = $withPayDays + $withoutPayDays;
        if ($totalDays <= 0) {
            return;
        }

        $employee = Employee::where('user_id', $leaveApplication->user_id)->first();
        $employeeId = $this->normalizeEmployeeId(
            $leaveApplication->employee_id ?: ($employee?->employee_id ?? '')
        );
        if ($employeeId === '') {
            return;
        }

        $upload = AttendanceUpload::firstOrCreate(
            ['file_path' => 'attendance_excels/system_leave_application_'.$leaveApplication->id.'.txt'],
            [
                'original_name' => 'system_leave_application_'.$leaveApplication->id.'.txt',
                'file_size' => 0,
                'status' => 'Processed',
                'processed_rows' => 0,
                'uploaded_at' => now(),
            ]
        );

        $employeeName = trim((string) ($leaveApplication->employee_name ?? ''));
        if ($employeeName === '') {
            $employeeName = trim((string) optional(optional($employee)->user)->first_name);
        }

        $department = trim((string) ($leaveApplication->office_department ?? ''));
        if ($department === '') {
            $department = trim((string) ($employee?->department ?? ''));
        }

        $jobType = $this->normalizeEmployeeJobType($employee?->job_type ?? null);

        for ($dayIndex = 0; $dayIndex < $totalDays; $dayIndex++) {
            $attendanceDate = $startDate->copy()->addDays($dayIndex)->toDateString();
            $isWithPay = $dayIndex < $withPayDays;
            $gateLabel = $isWithPay ? 'Leave - With Pay' : 'Leave - Without Pay';
            $isAbsent = !$isWithPay;

            $existing = AttendanceRecord::query()
                ->where('employee_id', $employeeId)
                ->whereDate('attendance_date', $attendanceDate)
                ->orderByDesc('id')
                ->first();

            if ($existing && !$this->canApplyLeaveAttendanceOverride($existing)) {
                continue;
            }

            $payload = [
                'attendance_upload_id' => $upload->id,
                'employee_id' => $employeeId,
                'employee_name' => $employeeName !== '' ? $employeeName : null,
                'department' => $department !== '' ? $department : null,
                'job_type' => $jobType,
                'main_gate' => $gateLabel,
                'attendance_date' => $attendanceDate,
                'morning_in' => null,
                'morning_out' => null,
                'afternoon_in' => null,
                'afternoon_out' => null,
                'late_minutes' => 0,
                'missing_time_logs' => ['morning_in', 'morning_out', 'afternoon_in', 'afternoon_out'],
                'is_absent' => $isAbsent,
                'is_tardy' => false,
            ];

            if ($existing) {
                $existing->update($payload);
            } else {
                AttendanceRecord::create($payload);
            }
        }

        $upload->update([
            'status' => 'Processed',
            'uploaded_at' => now(),
            'processed_rows' => AttendanceRecord::query()
                ->where('attendance_upload_id', $upload->id)
                ->count(),
        ]);
    }

    private function canApplyLeaveAttendanceOverride(AttendanceRecord $record): bool
    {
        $hasAnyTimeLog = !empty($record->morning_in)
            || !empty($record->morning_out)
            || !empty($record->afternoon_in)
            || !empty($record->afternoon_out);
        if ($hasAnyTimeLog) {
            return false;
        }

        $mainGate = strtolower(trim((string) ($record->main_gate ?? '')));
        return $mainGate === '' || str_starts_with($mainGate, 'leave -');
    }

    private function deleteGeneratedLeaveAttendanceRecords(LeaveApplication $leaveApplication): void
    {
        $upload = AttendanceUpload::query()
            ->where('file_path', 'attendance_excels/system_leave_application_'.$leaveApplication->id.'.txt')
            ->first();
        if (!$upload) {
            return;
        }

        AttendanceRecord::query()
            ->where('attendance_upload_id', $upload->id)
            ->delete();

        $upload->delete();
    }

    public function update_bio(Request $request){
        Log::info($request);
        $attrs = $request->validate([
            //User Model
            'user_id' => 'required|exists:users,id',
            'first' => 'required',
            'middle' => 'required',
            'last' => 'required',

            //Employee Model
            'employee_id' => 'required',
            'account_number' => 'required',
            'gender' => 'required',
            'civil_status' => 'required',
            'contact_number' => 'required',
            'birthday' => 'required|date',
            'address' => 'required',
            'employment_date' => 'required|date',
            'position' => 'required',
            'department' => 'required',
            'classification' => 'required',
            'job_type' => 'nullable|string|max:50',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:255',

            //Government Model
            'SSS' => 'required',
            'TIN' => 'required',
            'PhilHealth' => 'required',
            'MID' => 'required',
            'RTN' => 'required',

            //License Model
            'license' => 'required',
            'registration_number' => 'required',
            'registration_date' => 'required',
            'valid_until' => 'required',

            //Education Model
            'bachelor' => 'required',
            'master' => 'required',
            'doctorate' => 'required',

            //Salary Model
            'salary' => 'required',
            'rate_per_hour' => 'required',
            'cola' => 'required',
        ]);

        $user = User::findOrFail($attrs['user_id']);

        $user->update([
            //'' => $attrs[''],
            'first_name' => $attrs['first'],
            'middle_name' => $attrs['middle'],
            'last_name' => $attrs['last'],
        ]);

        Employee::updateOrCreate(
            // 1️⃣ Condition to find the record
            ['user_id' => $attrs['user_id']],

            // 2️⃣ Values to create or update
            [
                'user_id' => $attrs['user_id'],
                'employee_id' => $attrs['employee_id'],
                'employement_date' => $attrs['employment_date'],
                'birthday' => $attrs['birthday'],
                'account_number' => $attrs['account_number'],
                'sex' => $attrs['gender'],
                'civil_status' => $attrs['civil_status'],
                'contact_number' => $attrs['contact_number'],
                'address' => $attrs['address'],
                'department' => $attrs['department'],
                'position' => $attrs['position'],
                'classification' => $attrs['classification'],
                ...(Schema::hasColumn('employees', 'job_type')
                    ? ['job_type' => $this->resolveJobTypeFromOpenPositionForUser($attrs['user_id'])
                        ?? $this->normalizeEmployeeJobType($attrs['job_type'] ?? $attrs['classification'])]
                    : []),
                'emergency_contact_name' => $attrs['emergency_contact_name'] ?? null,
                'emergency_contact_relationship' => $attrs['emergency_contact_relationship'] ?? null,
                'emergency_contact_number' => $attrs['emergency_contact_number'] ?? null,
            ]
        );

        Government::updateOrCreate(
            // 1️⃣ Condition to find the record
            ['user_id' => $attrs['user_id']],

            // 2️⃣ Values to create or update
            [
                'SSS' => $attrs['SSS'],
                'TIN' => $attrs['TIN'],
                'PhilHealth' => $attrs['PhilHealth'],
                'RTN' => $attrs['RTN'],
                'MID' => $attrs['MID'],
            ]
        );

        License::updateOrCreate(
            // 1️⃣ Condition to find the record
            ['user_id' => $attrs['user_id']],

            // 2️⃣ Values to create or update
            [
                'license' => $attrs['license'],
                'registration_number' => $attrs['registration_number'],
                'registration_date' => $attrs['registration_date'],
                'valid_until' => $attrs['valid_until'],
            ]
        );

        Education::updateOrCreate(
            // 1️⃣ Condition to find the record
            ['user_id' => $attrs['user_id']],

            // 2️⃣ Values to create or update
            [
                'bachelor' => $attrs['bachelor'],
                'master' => $attrs['master'],
                'doctorate' => $attrs['doctorate'],
            ]
        );

        Salary::updateOrCreate(
            // 1️⃣ Condition to find the record
            ['user_id' => $attrs['user_id']],

            // 2️⃣ Values to create or update
            [
                'salary' => $attrs['salary'],
                'rate_per_hour' => $attrs['rate_per_hour'],
                'cola' => $attrs['cola'],
            ]
        );

        return redirect()->back()->with('success', 'Save Successfully');
    }

    private function normalizeEmployeeJobType($value): ?string
    {
        $normalized = strtolower(trim((string) $value));
        if ($normalized === '') {
            return null;
        }

        if (in_array($normalized, ['teaching', 't'], true)) {
            return 'Teaching';
        }

        if (in_array($normalized, [
            'non-teaching',
            'non teaching',
            'nonteaching',
            'nt',
            'full-time',
            'full time',
            'fulltime',
            'part-time',
            'part time',
            'parttime',
        ], true)) {
            return 'Non-Teaching';
        }

        return 'Non-Teaching';
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


    //DELETE
    public function destroy_position($id){
        $delete = OpenPosition::findOrFail($id);

        $delete->delete();

        return redirect()->route('admin.adminPosition')->with('success','Successfully deleted Position');

    }

    public function destroy_interview($id){
        $delete = Interviewer::where('applicant_id', $id)->first();
        $delete->delete();
        return redirect()->route('admin.adminPosition')->with('success','Successfully deleted Position');

    }

    public function destroy_employee($id){


        $open = User::findOrFail($id);

        $open->update([
            'status' => 'Not Approved',
        ]);

        return redirect()->back()->with('success','Employee not Approve');
    }

    public function update_attendance_status($id, Request $request){
        try {
            $attendanceFile = AttendanceUpload::findOrFail($id);

            $attrs = $request->validate([
                'status' => 'required|string',
                'from_date' => 'nullable|date',
                'to_date' => 'nullable|date',
            ]);
            $status = $attrs['status'];
            $fromDate = $attrs['from_date'] ?? null;
            $toDate = $attrs['to_date'] ?? null;
            $processedRows = $attendanceFile->processed_rows ?? 0;

            if (strtolower($status) === 'processed') {
                $absolutePath = Storage::disk('public')->path($attendanceFile->file_path);
                $extension = pathinfo($attendanceFile->file_path, PATHINFO_EXTENSION);
                $rows = $this->extractRowsFromExcel($absolutePath, $extension);
                $fallbackAttendanceDate = $fromDate ?: optional($attendanceFile->uploaded_at)->format('Y-m-d');
                $records = $this->buildAttendanceRecords($rows, $attendanceFile->id, $fallbackAttendanceDate);

                DB::transaction(function () use ($attendanceFile, $status, $records, &$processedRows) {
                    AttendanceRecord::where('attendance_upload_id', $attendanceFile->id)->delete();

                    if (!empty($records)) {
                        AttendanceRecord::insert($records);
                        $this->syncAttendanceRecordJobTypesForUpload($attendanceFile->id);
                    }

                    $processedRows = count($records);
                    $attendanceFile->update([
                        'status' => $status,
                        'processed_rows' => $processedRows,
                    ]);
                });
            } else {
                $attendanceFile->update([
                    'status' => $status
                ]);
            }

            $records = AttendanceRecord::query()
                ->where('attendance_upload_id', $attendanceFile->id)
                ->get();

            $presentCount = $records->where('is_absent', false)->where('late_minutes', 0)->count();
            $absentCount = $records->where('is_absent', true)->count();
            $tardyCount = $records->where('late_minutes', '>', 0)->count();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status' => $status,
                'processed_rows' => $processedRows,
                'upload_id' => $attendanceFile->id,
                'counts' => [
                    'present' => $presentCount,
                    'absent' => $absentCount,
                    'tardiness' => $tardyCount,
                ],
                'redirect_url' => route('admin.attendance.present', array_filter([
                    'upload_id' => $attendanceFile->id,
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                ])),
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating attendance status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating status'
            ], 500);
        }
    }

    public function delete_attendance_file($id){
        try {
            $attendanceFile = AttendanceUpload::findOrFail($id);

            // Delete the physical file if it exists
            if ($attendanceFile->file_path && Storage::disk('public')->exists($attendanceFile->file_path)) {
                Storage::disk('public')->delete($attendanceFile->file_path);
            }

            // Delete the database record
            $attendanceFile->delete();

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting attendance file: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting file'
            ], 500);
        }
    }


}
