<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PeopleHub - HR Dashboard</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    body { font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif; transition: margin-left 0.3s ease; }
    main { transition: margin-left 0.3s ease; }
    aside ~ main { margin-left: 16rem; }
    .attendance-file-active {
      border-color: rgb(125 211 252);
      background: linear-gradient(180deg, rgba(224, 242, 254, 0.85), rgba(255, 255, 255, 0.95));
      box-shadow: 0 18px 38px rgba(14, 165, 233, 0.12);
    }
  </style>
</head>
<body class="min-h-screen bg-[linear-gradient(180deg,#f8fbff_0%,#eef4ff_45%,#f8fafc_100%)] text-slate-800">

<div class="flex min-h-screen">
  @include('components.adminSideBar')

  <main class="flex-1 ml-16 transition-all duration-300">
    @include('components.adminHeader.attendanceHeader')

    <div class="p-4 md:p-8 space-y-6 pt-20">
      @php
        $currentAttendanceRoute = match ($activeAttendanceTab) {
          'present' => 'admin.attendance.present',
          'absent' => 'admin.attendance.absent',
          'tardiness' => 'admin.attendance.tardiness',
          'total_employee' => 'admin.attendance.totalEmployee',
          default => 'admin.adminAttendance',
        };

        $activeAttendanceLabel = match ($activeAttendanceTab) {
          'present' => 'Present Workforce',
          'absent' => 'Absence Monitor',
          'tardiness' => 'Tardiness Review',
          'total_employee' => 'Total Attendance Records',
          default => 'Attendance Operations',
        };

        $attendanceQuery = array_filter([
          'from_date' => $fromDate,
          'to_date' => $toDate ?? null,
          'upload_id' => $selectedUploadId,
          'job_type' => $selectedJobType ?? null,
          'search_name' => $searchName ?? null,
        ], fn ($value) => !is_null($value) && $value !== '');

        $baseCardClasses = 'group relative overflow-hidden rounded-[1.75rem] border border-white/80 bg-white/88 p-6 shadow-[0_20px_45px_rgba(15,23,42,0.08)] backdrop-blur transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_55px_rgba(15,23,42,0.12)]';
      @endphp

      <section class="relative overflow-hidden rounded-[2rem] border border-sky-100/80 bg-[linear-gradient(135deg,rgba(14,165,233,0.12),rgba(99,102,241,0.08),rgba(255,255,255,0.96))] px-6 py-6 shadow-[0_28px_60px_rgba(37,99,235,0.10)] md:px-8">
        <div class="absolute -left-8 top-6 h-24 w-24 rounded-full bg-sky-300/25 blur-3xl"></div>
        <div class="absolute right-0 top-0 h-32 w-32 translate-x-10 -translate-y-8 rounded-full bg-indigo-300/25 blur-3xl"></div>
        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
          <div class="max-w-3xl">
            <p class="max-w-2xl text-sm leading-6 text-slate-600 md:text-base">
              Track daily workforce movement, review uploads, and move from raw logs to attendance insights in one cleaner dashboard.
            </p>
          </div>

          <div class="grid gap-3 sm:grid-cols-3 xl:min-w-[520px]">
            <div class="rounded-2xl border border-white/80 bg-white/80 px-4 py-4 shadow-sm">
              <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Date Range</p>
              <p class="mt-2 text-sm font-semibold text-slate-800">
                {{ $fromDate }}
                @if (!empty($toDate))
                  <span class="text-slate-400">to</span> {{ $toDate }}
                @endif
              </p>
            </div>
            <div class="rounded-2xl border border-white/80 bg-white/80 px-4 py-4 shadow-sm">
              <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Search</p>
              <p class="mt-2 truncate text-sm font-semibold text-slate-800">{{ !empty($searchName) ? $searchName : 'All employees' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-900/90 bg-slate-900 px-4 py-4 shadow-sm">
              <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Current View</p>
              <p class="mt-2 text-sm font-semibold text-white">{{ $activeAttendanceLabel }}</p>
            </div>
          </div>
        </div>
      </section>

      <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('admin.attendance.present', $attendanceQuery) }}" class="{{ $baseCardClasses }} {{ $activeAttendanceTab === 'present' ? 'border-emerald-300 bg-emerald-50/80' : '' }}">
          <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-emerald-200/40 blur-2xl"></div>
          <div class="relative flex items-start justify-between gap-4">
            <div>
              <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 shadow-sm">
                <i class="fa-solid fa-user-check text-lg"></i>
              </span>
              <p class="mt-4 text-4xl font-black tracking-tight text-slate-900">{{ $presentCount }}</p>
              <p class="mt-1 text-sm font-semibold text-slate-700">Present</p>
              <p class="mt-2 text-xs leading-5 text-slate-500">Employees with valid logs in the selected attendance range.</p>
            </div>
            <span class="rounded-full border border-emerald-200 bg-white/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-700">Healthy</span>
          </div>
        </a>

        <a href="{{ route('admin.attendance.absent', $attendanceQuery) }}" class="{{ $baseCardClasses }} {{ $activeAttendanceTab === 'absent' ? 'border-rose-300 bg-rose-50/80' : '' }}">
          <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-rose-200/40 blur-2xl"></div>
          <div class="relative flex items-start justify-between gap-4">
            <div>
              <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 shadow-sm">
                <i class="fa-solid fa-user-xmark text-lg"></i>
              </span>
              <p class="mt-4 text-4xl font-black tracking-tight text-slate-900">{{ $absentCount }}</p>
              <p class="mt-1 text-sm font-semibold text-slate-700">Absent</p>
              <p class="mt-2 text-xs leading-5 text-slate-500">Team members without attendance records for the filtered dates.</p>
            </div>
            <span class="rounded-full border border-rose-200 bg-white/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-rose-700">Watchlist</span>
          </div>
        </a>

        <a href="{{ route('admin.attendance.tardiness', $attendanceQuery) }}" class="{{ $baseCardClasses }} {{ $activeAttendanceTab === 'tardiness' ? 'border-amber-300 bg-amber-50/80' : '' }}">
          <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-amber-200/40 blur-2xl"></div>
          <div class="relative flex items-start justify-between gap-4">
            <div>
              <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-600 shadow-sm">
                <i class="fa-solid fa-clock text-lg"></i>
              </span>
              <p class="mt-4 text-4xl font-black tracking-tight text-slate-900">{{ $tardyCount }}</p>
              <p class="mt-1 text-sm font-semibold text-slate-700">Tardiness</p>
              <p class="mt-2 text-xs leading-5 text-slate-500">Late arrivals that need review for schedule or compliance follow-up.</p>
            </div>
            <span class="rounded-full border border-amber-200 bg-white/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-amber-700">Review</span>
          </div>
        </a>

        <a href="{{ route('admin.attendance.totalEmployee', $attendanceQuery) }}" class="{{ $baseCardClasses }} {{ $activeAttendanceTab === 'total_employee' ? 'border-sky-300 bg-sky-50/80' : '' }}">
          <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-sky-200/40 blur-2xl"></div>
          <div class="relative flex items-start justify-between gap-4">
            <div>
              <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-600 shadow-sm">
                <i class="fa-solid fa-users text-lg"></i>
              </span>
              <p class="mt-4 text-4xl font-black tracking-tight text-slate-900">{{ $totalCount }}</p>
              <p class="mt-1 text-sm font-semibold text-slate-700">Employee Records</p>
              <p class="mt-2 text-xs leading-5 text-slate-500">Complete attendance entries available for overview and export.</p>
            </div>
            <span class="rounded-full border border-sky-200 bg-white/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-sky-700">Overview</span>
          </div>
        </a>
      </div>

      @if ($activeAttendanceTab === 'all')
      <div class="grid gap-6 xl:grid-cols-[minmax(0,0.95fr)_minmax(0,1.35fr)]">
        <section class="space-y-6">
          <div class="overflow-hidden rounded-[1.75rem] border border-white/80 bg-white/90 p-6 shadow-[0_20px_50px_rgba(15,23,42,0.08)] backdrop-blur">
            <div class="flex items-start justify-between gap-4">
              <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-sky-700">
                  Upload Center
                </div>
                <h3 class="mt-4 text-2xl font-black tracking-tight text-slate-900">Import attendance sheet</h3>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                  Upload the latest biometric export, confirm the file, then scan it into your attendance records.
                </p>
              </div>
              <div class="hidden h-14 w-14 items-center justify-center rounded-2xl bg-sky-100 text-sky-600 sm:flex">
                <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
              </div>
            </div>

            @if (session('success'))
              <div class="mt-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
              </div>
            @endif

            @if ($errors->has('excel_file'))
              <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first('excel_file') }}
              </div>
            @endif

            <form action="{{ route('admin.uploadAttendanceExcel') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
              @csrf

              <label for="excel_file" class="group relative flex cursor-pointer flex-col items-center justify-center overflow-hidden rounded-[1.5rem] border-2 border-dashed border-sky-200 bg-[linear-gradient(180deg,rgba(239,246,255,0.9),rgba(255,255,255,0.95))] px-6 py-10 text-center transition hover:border-sky-300 hover:bg-sky-50">
                <div class="absolute inset-x-6 top-0 h-px bg-gradient-to-r from-transparent via-sky-300 to-transparent"></div>
                <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-sky-600 shadow-sm">
                  <i class="fa-solid fa-file-arrow-up text-2xl"></i>
                </span>
                <p class="mt-5 text-base font-semibold text-slate-800">Browse Excel file to upload</p>
                <p class="mt-2 text-sm text-slate-500">Drop the latest `.xlsx` export here or click to choose a file.</p>
                <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs text-slate-500">
                  <span class="rounded-full border border-slate-200 bg-white px-3 py-1">.xlsx only</span>
                  <span class="rounded-full border border-slate-200 bg-white px-3 py-1">employee_id</span>
                  <span class="rounded-full border border-slate-200 bg-white px-3 py-1">am_time</span>
                  <span class="rounded-full border border-slate-200 bg-white px-3 py-1">pm_time</span>
                </div>
                <p id="selected_excel_name" class="mt-5 text-sm font-medium text-sky-700">No file selected</p>
              </label>

              <input id="excel_file" name="excel_file" type="file" accept=".xlsx" class="hidden" required />

              <div class="grid gap-3 rounded-[1.5rem] border border-slate-200 bg-slate-50/80 p-4 sm:grid-cols-3">
                <div class="rounded-2xl bg-white px-4 py-3 shadow-sm">
                  <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Format</p>
                  <p class="mt-2 text-sm font-semibold text-slate-700">Excel Workbook</p>
                </div>
                <div class="rounded-2xl bg-white px-4 py-3 shadow-sm">
                  <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Scan Flow</p>
                  <p class="mt-2 text-sm font-semibold text-slate-700">Upload, select, scan</p>
                </div>
                <div class="rounded-2xl bg-white px-4 py-3 shadow-sm">
                  <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Range</p>
                  <p class="mt-2 text-sm font-semibold text-slate-700">{{ $fromDate }}{{ !empty($toDate) ? ' to '.$toDate : '' }}</p>
                </div>
              </div>

              <div class="flex justify-end">
                <button id="upload_excel_btn" type="submit" disabled class="inline-flex items-center gap-2 rounded-full bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700 disabled:cursor-not-allowed disabled:bg-sky-300">
                  <i class="fa-solid fa-upload"></i>
                  Upload Excel
                </button>
              </div>
            </form>
          </div>

          <div class="rounded-[1.75rem] border border-white/80 bg-slate-900 p-6 text-white shadow-[0_20px_50px_rgba(15,23,42,0.18)]">
            <div class="flex items-start justify-between gap-4">
              <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Workflow Tips</p>
                <h3 class="mt-3 text-xl font-black tracking-tight">Keep scans consistent</h3>
              </div>
              <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-sky-300">
                <i class="fa-solid fa-wave-square"></i>
              </span>
            </div>
            <div class="mt-5 space-y-3 text-sm text-slate-300">
              <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">Upload one verified biometric export at a time to avoid duplicate status updates.</div>
              <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">Use the date range filter before scanning so imported rows are immediately aligned to the target period.</div>
              <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">Select the file from the queue first, then run scan to update its progress and redirect into the processed view.</div>
            </div>
          </div>
        </section>

        <section class="overflow-hidden rounded-[1.75rem] border border-white/80 bg-white/90 p-6 shadow-[0_20px_50px_rgba(15,23,42,0.08)] backdrop-blur">
          <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-700">
                Processing Queue
              </div>
              <h3 class="mt-4 text-2xl font-black tracking-tight text-slate-900">File status and scan control</h3>
              <p class="mt-2 text-sm leading-6 text-slate-500">
                Review uploaded files, filter by attendance dates, and run scan on the selected workbook.
              </p>
            </div>

            <form method="GET" action="{{ route($currentAttendanceRoute) }}" class="grid gap-3 rounded-[1.5rem] border border-slate-200 bg-slate-50/80 p-4 lg:min-w-[520px] lg:grid-cols-[auto_minmax(0,1fr)_auto_minmax(0,1fr)_auto_auto] lg:items-center" style="margin-top: -7px;">
              @if ($selectedUploadId)
                <input type="hidden" name="upload_id" value="{{ $selectedUploadId }}">
              @endif
              @if (!empty($selectedJobType))
                <input type="hidden" name="job_type" value="{{ $selectedJobType }}">
              @endif
              @if (!empty($searchName))
                <input type="hidden" name="search_name" value="{{ $searchName }}">
              @endif
              <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">From</label>
              <input
                name="from_date"
                value="{{ $fromDate }}"
                type="date"
                class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none"
              />
              <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">To</label>
              <input
                name="to_date"
                value="{{ $toDate ?? '' }}"
                type="date"
                class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none"
              />
              <button type="submit" class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-800">
                Filter
              </button>
              <button type="button" id="scan_btn" class="rounded-full bg-emerald-500 px-4 py-2 text-xs font-semibold text-white transition hover:bg-emerald-600">
                <i class="fa-solid fa-barcode mr-1"></i>Scan
              </button>
            </form>
          </div>

          <div class="mt-6 space-y-4">
            @forelse ($attendanceFiles as $file)
              @php
                $progress = 0;
                if ($file->status === 'Processed') {
                  $progress = 100;
                } elseif ($file->status === 'Processing' && $file->processed_rows) {
                  $progress = min(75, $file->processed_rows);
                } elseif ($file->status === 'Pending') {
                  $progress = 0;
                }
              @endphp
              <div class="file-item group rounded-[1.5rem] border border-slate-200 bg-[linear-gradient(180deg,#ffffff,#f8fbff)] p-4 transition hover:border-sky-200 hover:shadow-md" data-file-id="{{ $file->id }}">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center">
                  <div class="flex items-center gap-4">
                    <input type="radio" name="selected_file" value="{{ $file->id }}" class="file-checkbox h-4 w-4 cursor-pointer border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 shadow-sm">
                      <i class="fa-solid fa-file-excel text-xl"></i>
                    </span>

                    <div>
                      <div class="flex flex-wrap items-center gap-2">
                        <p class="text-sm font-semibold text-slate-800">{{ $file->original_name }}</p>
                        <span class="file-status rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">{{ $file->status }}</span>
                      </div>
                      <p class="mt-1 text-xs text-slate-500">
                        {{ number_format((float) $file->file_size / 1024, 2) }} KB
                        @if (!is_null($file->processed_rows))
                          | {{ $file->processed_rows }} rows processed
                        @endif
                      </p>
                    </div>
                  </div>

                  <div class="flex-1">
                    <div class="mb-2 flex items-center justify-between">
                      <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Processing Progress</p>
                      <div class="min-w-[40px] text-right text-xs font-semibold text-slate-700">{{ $progress }}%</div>
                    </div>
                    <div class="h-2.5 w-full rounded-full bg-slate-200">
                      <div class="h-2.5 rounded-full bg-gradient-to-r from-emerald-400 via-sky-500 to-indigo-500 transition-all duration-500" style="width: {{ $progress }}%"></div>
                    </div>
                  </div>

                  <div class="min-w-[120px] text-left text-xs text-slate-500 xl:text-right">
                    {{ optional($file->uploaded_at)->format('M d, Y') ?? '-' }}<br>
                    {{ optional($file->uploaded_at)->format('h:i A') ?? '-' }}
                  </div>

                  <button type="button" class="delete-btn inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 text-rose-600 transition hover:bg-rose-100" data-file-id="{{ $file->id }}" title="Delete file">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </div>
              </div>
            @empty
              <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50/80 p-8 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                  <i class="fa-solid fa-folder-open text-xl"></i>
                </div>
                <p class="mt-4 text-sm font-semibold text-slate-700">No uploaded files yet.</p>
                <p class="mt-1 text-sm text-slate-500">Once you upload an attendance workbook, it will appear here for scanning.</p>
              </div>
            @endforelse
          </div>
        </section>
      </div>
      @else
      <div class="overflow-hidden rounded-[1.75rem] border border-white/80 bg-white/90 p-6 shadow-[0_20px_50px_rgba(15,23,42,0.08)] backdrop-blur">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
          <div>
            <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-indigo-700">
              Attendance Table
            </div>
            <h3 class="mt-4 text-2xl font-black tracking-tight text-slate-900">Attendance list</h3>
            <p class="mt-2 text-sm leading-6 text-slate-500">
              Narrow the view by job type and date range, then review attendance rows with a cleaner data panel.
            </p>
          </div>
          <form method="GET" action="{{ route($currentAttendanceRoute) }}" class="grid gap-3 rounded-[1.5rem] border border-slate-200 bg-slate-50/80 p-4 xl:min-w-[760px] xl:grid-cols-[auto_minmax(0,1fr)_auto_minmax(0,1fr)_auto_minmax(0,1fr)_auto_auto] xl:items-center">
            @if ($selectedUploadId)
              <input type="hidden" name="upload_id" value="{{ $selectedUploadId }}">
            @endif
            @if (!empty($searchName))
              <input type="hidden" name="search_name" value="{{ $searchName }}">
            @endif
            <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Job Type</label>
            <select
              name="job_type"
              class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none"
            >
              <option value="">All Job Types</option>
              @foreach (($jobTypeOptions ?? collect()) as $jobTypeOption)
                <option value="{{ $jobTypeOption }}" {{ ($selectedJobType ?? null) === $jobTypeOption ? 'selected' : '' }}>
                  {{ $jobTypeOption }}
                </option>
              @endforeach
            </select>
            <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">From</label>
            <input
              name="from_date"
              value="{{ $fromDate }}"
              type="date"
              class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none"
            />
            <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">To</label>
            <input
              name="to_date"
              value="{{ $toDate ?? '' }}"
              type="date"
              class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none"
            />
            <button type="submit" class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-800">
              Filter
            </button>
            <a href="{{ route($currentAttendanceRoute, array_filter(['upload_id' => $selectedUploadId ?? null, 'search_name' => $searchName ?? null], fn ($value) => !is_null($value) && $value !== '')) }}" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-center text-xs font-semibold text-slate-600 transition hover:border-slate-300 hover:text-slate-900">
              Reset
            </a>
          </form>
        </div>

        <div class="mt-6 rounded-[1.5rem] border border-slate-200 bg-slate-50/60 p-3">
          @if ($activeAttendanceTab === 'present')
            @include('Admin.attendanceTable.presentEmployee', ['rows' => $presentEmployees])
          @elseif ($activeAttendanceTab === 'absent')
            @include('Admin.attendanceTable.absentEmployee', ['rows' => $absentEmployees])
          @elseif ($activeAttendanceTab === 'tardiness')
            @include('Admin.attendanceTable.tardinessEmployee', ['rows' => $tardyEmployees])
          @elseif ($activeAttendanceTab === 'total_employee')
            @include('Admin.attendanceTable.totalEmployee', ['rows' => $allEmployees])
          @endif
        </div>
      </div>
      @endif
    </div>
  </main>
</div>

</body>

<script>
  const sidebar = document.querySelector('aside');
  const main = document.querySelector('main');
  const excelInput = document.getElementById('excel_file');
  const excelName = document.getElementById('selected_excel_name');
  const uploadBtn = document.getElementById('upload_excel_btn');
  const scanBtn = document.getElementById('scan_btn');
  const fileItems = document.querySelectorAll('.file-item');
  const fileCheckboxes = document.querySelectorAll('.file-checkbox');
  const deleteButtons = document.querySelectorAll('.delete-btn');
  const fromDateInput = document.querySelector('input[name="from_date"]');
  const toDateInput = document.querySelector('input[name="to_date"]');

  function setActiveFileItem(activeItem) {
    fileItems.forEach(item => {
      item.classList.remove('attendance-file-active');
    });

    if (activeItem) {
      activeItem.classList.add('attendance-file-active');
    }
  }

  fileItems.forEach(item => {
    item.addEventListener('click', function(e) {
      if (e.target.type !== 'radio') {
        const checkbox = this.querySelector('.file-checkbox');
        if (checkbox) {
          checkbox.checked = true;
        }
      }
      setActiveFileItem(this);
    });
  });

  fileCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const currentItem = this.closest('.file-item');
      setActiveFileItem(currentItem);
    });
  });

  if (scanBtn) {
    scanBtn.addEventListener('click', function() {
      const selectedCheckbox = document.querySelector('.file-checkbox:checked');
      if (!selectedCheckbox) {
        alert('Please select a file first');
        return;
      }

      const fileId = selectedCheckbox.value;
      const fileItem = document.querySelector(`[data-file-id="${fileId}"]`);
      const statusElement = fileItem.querySelector('.file-status');
      const progressBar = fileItem.querySelector('.bg-gradient-to-r');
      const progressTextElement = fileItem.querySelector('.min-w-\\[40px\\]');

      scanBtn.disabled = true;
      scanBtn.classList.add('opacity-50', 'cursor-not-allowed');

      let progress = parseInt(progressTextElement.textContent);
      const targetProgress = 100;
      const animationDuration = 3000;
      const startTime = Date.now();

      const animateProgress = () => {
        const elapsed = Date.now() - startTime;
        const fraction = Math.min(elapsed / animationDuration, 1);
        const easeProgress = progress + (targetProgress - progress) * (fraction < 0.8 ? fraction * 1.25 : 0.8 + (fraction - 0.8) * 0.5);
        const currentProgress = Math.floor(easeProgress);

        if (progressBar) {
          progressBar.style.width = currentProgress + '%';
        }
        if (progressTextElement) {
          progressTextElement.textContent = currentProgress + '%';
        }

        if (fraction < 1) {
          requestAnimationFrame(animateProgress);
        }
      };

      animateProgress();

      setTimeout(() => {
        fetch(`/admin/attendance/update-status/${fileId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
          },
          body: JSON.stringify({
            status: 'Processed',
            from_date: fromDateInput?.value || null,
            to_date: toDateInput?.value || null
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            statusElement.textContent = 'Processed';
            if (progressBar) {
              progressBar.style.width = '100%';
            }
            if (progressTextElement) {
              progressTextElement.textContent = '100%';
            }
            alert('File scan completed successfully!');
            scanBtn.disabled = false;
            scanBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            if (data.redirect_url) {
              window.location.href = data.redirect_url;
            }
          } else {
            alert('Failed to update status');
            scanBtn.disabled = false;
            scanBtn.classList.remove('opacity-50', 'cursor-not-allowed');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error updating status');
          scanBtn.disabled = false;
          scanBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
      }, animationDuration);
    });
  }

  if (excelInput && excelName && uploadBtn) {
    excelInput.addEventListener('change', function () {
      const hasFile = this.files && this.files.length > 0;
      uploadBtn.disabled = !hasFile;
      excelName.textContent = hasFile ? this.files[0].name : 'No file selected';
    });
  }

  deleteButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      const fileId = this.dataset.fileId;

      if (confirm('Are you sure you want to delete this file?')) {
        fetch(`/admin/attendance/delete/${fileId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const fileItem = document.querySelector(`[data-file-id="${fileId}"]`);
            fileItem.closest('.file-item').remove();
            alert('File deleted successfully');
          } else {
            alert('Failed to delete file');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error deleting file');
        });
      }
    });
  });

  if (sidebar && main) {
    sidebar.addEventListener('mouseenter', function() {
      main.classList.remove('ml-16');
      main.classList.add('ml-64');
    });
    sidebar.addEventListener('mouseleave', function() {
      main.classList.remove('ml-64');
      main.classList.add('ml-16');
    });
  }
</script>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
</html>
