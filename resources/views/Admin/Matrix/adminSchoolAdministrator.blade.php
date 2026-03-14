<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>School Administrators Matrix</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    .matrix-name-button {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      border: 0;
      background: transparent;
      padding: 0;
      color: #111827;
      font-weight: 600;
      text-align: left;
      cursor: pointer;
      text-decoration: none;
    }

    .matrix-name-button:hover {
      color: #111827;
      text-decoration: none;
    }

    .matrix-name-button:focus-visible {
      outline: 2px solid #93c5fd;
      outline-offset: 2px;
      border-radius: 0.25rem;
    }

    .matrix-row-active {
      background: #eff6ff !important;
    }

    .matrix-employee-modal {
      position: fixed;
      inset: 0;
      z-index: 90;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      background: rgba(15, 23, 42, 0.62);
    }

    .matrix-employee-modal.is-open {
      display: flex;
    }

    .matrix-employee-card {
      width: min(720px, 100%);
      overflow: hidden;
      border: 1px solid #d6d3d1;
      border-radius: 1rem;
      background: #ffffff;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.28);
    }

    .matrix-employee-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      padding: 1.25rem 1.5rem;
      background: #1c1917;
      color: #ffffff;
    }

    .matrix-employee-card-title {
      margin: 0;
      font-size: 1.7rem;
      font-weight: 700;
      letter-spacing: -0.03em;
    }

    .matrix-employee-close {
      border: 0;
      background: transparent;
      color: inherit;
      font-size: 1.6rem;
      cursor: pointer;
    }

    .matrix-employee-card-body {
      display: grid;
      grid-template-columns: 170px minmax(0, 1fr);
      gap: 1.25rem;
      padding: 1.5rem;
    }

    .matrix-employee-avatar {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 210px;
      overflow: hidden;
      border: 1px solid #d6d3d1;
      background: linear-gradient(135deg, #f5f5f4, #e7e5e4);
      color: #111827;
      font-size: 3rem;
      font-weight: 700;
    }

    .matrix-employee-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .matrix-employee-fields {
      display: grid;
      gap: 0.7rem;
    }

    .matrix-employee-field {
      display: grid;
      grid-template-columns: 130px minmax(0, 1fr);
      gap: 0.75rem;
      align-items: center;
    }

    .matrix-employee-label {
      font-size: 0.84rem;
      font-weight: 700;
      color: #292524;
    }

    .matrix-employee-value {
      min-height: 2.3rem;
      border: 1px solid #a8a29e;
      background: #fafaf9;
      padding: 0.55rem 0.7rem;
      color: #111827;
      font-size: 0.92rem;
    }

    .matrix-export-modal {
      position: fixed;
      inset: 0;
      z-index: 95;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      background: rgba(15, 23, 42, 0.55);
    }

    .matrix-export-modal.is-open {
      display: flex;
    }

    .matrix-export-card {
      width: min(420px, 100%);
      border: 1px solid #d6d3d1;
      border-radius: 1rem;
      background: #ffffff;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.24);
      overflow: hidden;
    }

    .matrix-export-card-header {
      padding: 1.1rem 1.4rem 0.4rem;
    }

    .matrix-export-card-body {
      padding: 0 1.4rem 1.4rem;
      display: grid;
      gap: 0.75rem;
    }

    @media (max-width: 640px) {
      .matrix-employee-card-body {
        grid-template-columns: 1fr;
      }

      .matrix-employee-field {
        grid-template-columns: 1fr;
        gap: 0.35rem;
      }
    }

    .matrix-main-content {
      width: calc(100vw - 4rem);
      min-width: 0;
      max-width: calc(100vw - 4rem);
    }

    .matrix-content-section,
    .matrix-print-wrapper {
      min-width: 0;
      max-width: 100%;
    }

    @media (min-width: 1024px) {
      .matrix-admin-shell > .matrix-print-hide:hover + .matrix-main-content {
        margin-left: 18rem;
        width: calc(100vw - 18rem);
        max-width: calc(100vw - 18rem);
      }
    }

    @media (max-width: 1023px) {
      .matrix-main-content {
        width: 100%;
        max-width: 100%;
      }
    }

    @media print {
      @page {
        size: 13in 8.5in;
        margin: 0.35in;
      }

      html,
      body {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
      }

      body * {
        visibility: hidden;
      }

      .matrix-print-section,
      .matrix-print-section * {
        visibility: visible;
      }

      .matrix-print-section {
        position: static !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
      }

      .matrix-print-shell,
      .matrix-print-shell > main {
        display: block !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
      }

      .matrix-print-wrapper {
        overflow: visible !important;
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: #ffffff !important;
      }

      .matrix-print-wrapper table {
        min-width: 100% !important;
        width: auto !important;
        table-layout: auto !important;
      }

      .matrix-print-wrapper th,
      .matrix-print-wrapper td {
        font-size: 8.75pt !important;
        padding: 4pt 4pt !important;
        word-break: normal !important;
        overflow-wrap: break-word !important;
        white-space: normal !important;
      }

      .matrix-print-wrapper th:first-child,
      .matrix-print-wrapper td:first-child {
        width: 1.95in !important;
        min-width: 1.95in !important;
        white-space: nowrap !important;
      }

      .matrix-print-wrapper th:nth-child(2),
      .matrix-print-wrapper td:nth-child(2) {
        width: 2.7in !important;
        min-width: 2.7in !important;
      }

      .matrix-print-hide {
        display: none !important;
      }

      .matrix-export-actions {
        display: none !important;
      }
    }
  </style>
</head>
<body class="bg-gradient-to-br from-amber-50 via-stone-100 to-zinc-200">

<div class="matrix-admin-shell matrix-print-shell flex min-h-screen">
  <div class="matrix-print-hide">
    @include('components.adminSideBar')
  </div>

  <main class="matrix-main-content flex-1 ml-16 transition-all duration-300">
    <section class="matrix-content-section matrix-print-hide px-4 md:px-8 pt-8 pb-6">
      <div class="rounded-2xl border border-stone-300 bg-white/80 backdrop-blur-sm shadow-sm p-5 md:p-7">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <p class="text-xs uppercase tracking-[0.2em] text-stone-600">Matrix 14</p>
            <h1 class="mt-1 text-xl md:text-2xl font-semibold text-stone-900">
              Matrix List of School Administrators
            </h1>
            <p class="mt-1 text-sm text-stone-600">
              President, Vice-President(s), Deans, and Department Heads
            </p>
          </div>
          <div class="matrix-export-actions flex flex-wrap items-center gap-2">
            <button
              type="button"
              onclick="downloadMatrixWord('school-administrator-matrix', 'School Administrators Matrix')"
              class="inline-flex items-center gap-2 rounded-lg border border-blue-300 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100"
            >
              <i class="fa-solid fa-file-word"></i>
              Word
            </button>
            <button
              type="button"
              onclick="openMatrixExcelExportModal('school-administrator-matrix', 'School Administrators Matrix')"
              class="inline-flex items-center gap-2 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-100"
            >
              <i class="fa-solid fa-file-excel"></i>
              Excel
            </button>
            <button
              type="button"
              onclick="window.print()"
              class="inline-flex items-center gap-2 rounded-lg border border-rose-300 bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 hover:bg-rose-100"
            >
              <i class="fa-solid fa-file-pdf"></i>
              PDF
            </button>
          </div>
        </div>
      </div>
    </section>

    <section class="matrix-content-section matrix-print-section px-4 md:px-8 pb-10">
      <div class="matrix-print-wrapper w-full overflow-x-auto rounded-2xl border border-stone-300 bg-white shadow-sm">
        <table id="school-administrator-matrix" class="min-w-[1300px] w-full text-sm text-stone-800 border-collapse">
          <thead class="bg-stone-100">
            <tr class="hidden print:table-row">
              <th colspan="7" class="border border-stone-900 bg-white px-3 py-2 text-left text-sm font-bold text-stone-900">
                14. Matrix list of school administrators, i.e., President, Vice-President(s), Deans, Department Heads, etc, including:
              </th>
            </tr>
            <tr>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[20%]">Name of Dean/Program Head</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[350px]">Educational Qualifications (school, degree, and when obtained)</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[240px]">Position/Designation</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[140px]">Status of Employment</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[150px]">Rate of Salary per month</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[210px]">Other Employment Benefits</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[320px]">Relevant Experience/s</th>
            </tr>
          </thead>
          <tbody class="align-top">
            @forelse ($administrators as $admin)
              @php
                $fullName = trim(implode(' ', array_filter([
                  trim((string) ($admin->first_name ?? '')),
                  trim((string) ($admin->middle_name ?? '')),
                  trim((string) ($admin->last_name ?? '')),
                ])));

                $degreeRows = collect(optional($admin->applicant)->degrees ?? [])->values();
                $fallbackDegrees = collect([
                  ['degree_name' => trim((string) optional($admin->education)->doctorate), 'school_name' => trim((string) (optional($admin->applicant)->doctoral_school_name ?? '')), 'year_finished' => trim((string) (optional($admin->applicant)->doctoral_year_finished ?? ''))],
                  ['degree_name' => trim((string) optional($admin->education)->master), 'school_name' => trim((string) (optional($admin->applicant)->master_school_name ?? '')), 'year_finished' => trim((string) (optional($admin->applicant)->master_year_finished ?? ''))],
                  ['degree_name' => trim((string) optional($admin->education)->bachelor), 'school_name' => trim((string) (optional($admin->applicant)->bachelor_school_name ?? '')), 'year_finished' => trim((string) (optional($admin->applicant)->bachelor_year_finished ?? ''))],
                ])->filter(fn ($row) => $row['degree_name'] !== '');

                $jobRole = trim((string) ($admin->job_role ?? ''));
                $positionValue = collect([
                  trim((string) optional($admin->employee)->position),
                  trim((string) ($admin->position ?? '')),
                ])->first(fn ($value) => $value !== '');
                $departmentValue = collect([
                  trim((string) optional($admin->employee)->department),
                  trim((string) (optional(optional($admin->applicant)->position)->department ?? '')),
                  trim((string) ($admin->department ?? '')),
                ])->first(fn ($value) => $value !== '');

                $jobType = strtolower(trim((string) (optional($admin->employee)->job_type ?? optional(optional($admin->applicant)->position)->job_type ?? '')));
                $rawJoinDate = optional($admin->applicant)->date_hired ?? optional($admin->employee)->employement_date;
                $employmentStatus = 'N/A';
                if ($jobType === 'non-teaching' || $jobType === 'teaching') {
                  $employmentStatus = 'Probationary';
                  if (!empty($rawJoinDate)) {
                    try {
                      $joinDate = \Carbon\Carbon::parse($rawJoinDate);
                      $threshold = $jobType === 'non-teaching'
                        ? $joinDate->copy()->addMonths(6)
                        : $joinDate->copy()->addYears(3);
                      $employmentStatus = now()->lt($threshold) ? 'Probationary' : 'Permanent';
                    } catch (\Throwable $e) {
                      $employmentStatus = 'Probationary';
                    }
                  }
                }
                $regularizationDateDisplay = '';
                if (!empty($rawJoinDate)) {
                  try {
                    $joinDate = \Carbon\Carbon::parse($rawJoinDate);
                    $regularizationDateDisplay = ($jobType === 'non-teaching'
                      ? $joinDate->copy()->addMonths(6)
                      : $joinDate->copy()->addYears(3))->format('m/d/Y');
                  } catch (\Throwable $e) {
                    $regularizationDateDisplay = '';
                  }
                }
                $salaryValue = trim((string) (optional($admin->salary)->salary ?? ''));
                $benefits = collect([
                  trim((string) (optional($admin->applicant)->benefit ?? '')),
                  trim((string) (optional(optional($admin->applicant)->position)->benifits ?? '')),
                ])->filter()->values();

                $workPosition = trim((string) (optional($admin->applicant)->work_position ?? ''));
                $workDuration = trim((string) (optional($admin->applicant)->work_duration ?? ''));
                if ($workDuration === '' && !empty($rawJoinDate)) {
                  try {
                    $joinYear = (int) \Carbon\Carbon::parse($rawJoinDate)->format('Y');
                    $workDuration = $joinYear > 0 ? ($joinYear.'-'.now()->format('Y')) : '';
                  } catch (\Throwable $e) {
                    $workDuration = '';
                  }
                }
                $currentYear = (int) now()->format('Y');
                $displayWorkDuration = $workDuration !== ''
                  ? preg_replace_callback('/(\b\d{4}\b)(\s*[-–]\s*)(\d{4})/', function ($matches) use ($currentYear) {
                      $endYear = (int) ($matches[3] ?? 0);
                      if ($endYear === $currentYear) {
                          return ($matches[1] ?? '').($matches[2] ?? '-').'Present';
                      }
                      return $matches[0] ?? '';
                    }, $workDuration)
                  : '';
                $experienceLines = collect(preg_split('/\s*(?:\||;|\/|\r?\n)\s*/', $workPosition) ?: [])
                  ->map(fn ($value) => trim((string) preg_replace('/\s*\(?\d{4}\s*[-–]\s*(?:\d{4}|Present)\)?\s*$/i', '', (string) $value)))
                  ->filter(fn ($value) => $value !== '')
                  ->reverse()
                  ->values();
                if ($displayWorkDuration === '' && $experienceLines->count() > 1) {
                  $fallbackYear = $currentYear;
                  if (!empty($rawJoinDate)) {
                    try {
                      $fallbackYear = (int) \Carbon\Carbon::parse($rawJoinDate)->format('Y');
                    } catch (\Throwable $e) {
                      $fallbackYear = $currentYear;
                    }
                  }
                  $displayWorkDuration = ($fallbackYear > 0 ? $fallbackYear : $currentYear).'-Present';
                }
                $employeeIdDisplay = trim((string) (optional($admin->employee)->employee_id ?? $admin->employee_id ?? $admin->id ?? ''));
                $employeeIdDisplay = $employeeIdDisplay !== '' ? $employeeIdDisplay : 'N/A';
                $ageDisplay = 'N/A';
                if (!empty(optional($admin->employee)->birthday)) {
                  try {
                    $ageDisplay = (string) \Carbon\Carbon::parse(optional($admin->employee)->birthday)->age;
                  } catch (\Throwable $e) {
                    $ageDisplay = 'N/A';
                  }
                }
                $genderDisplay = collect([
                  trim((string) ($admin->gender ?? '')),
                  trim((string) (optional($admin->employee)->sex ?? '')),
                  trim((string) (optional($admin->applicant)->gender ?? '')),
                ])->first(fn ($value) => $value !== '') ?? 'N/A';
                $positionDisplay = collect([
                  $jobRole,
                  $positionValue,
                ])->first(fn ($value) => trim((string) $value) !== '') ?? 'N/A';
                $departmentDisplay = $departmentValue !== '' ? $departmentValue : 'N/A';
                $hireDateDisplay = 'N/A';
                if (!empty($rawJoinDate)) {
                  try {
                    $hireDateDisplay = \Carbon\Carbon::parse($rawJoinDate)->format('m/d/Y');
                  } catch (\Throwable $e) {
                    $hireDateDisplay = trim((string) $rawJoinDate) !== '' ? trim((string) $rawJoinDate) : 'N/A';
                  }
                }
                $initials = collect([
                  mb_substr(trim((string) ($admin->first_name ?? '')), 0, 1),
                  mb_substr(trim((string) ($admin->last_name ?? '')), 0, 1),
                ])->filter()->implode('');
                $initials = $initials !== '' ? strtoupper($initials) : 'NA';
                $profilePhotoDocument = optional($admin->applicant)->documents
                  ?->first(function ($doc) {
                    return strtoupper(trim((string) ($doc->type ?? ''))) === 'PROFILE_PHOTO' && !empty($doc->filepath);
                  });
                if (!$profilePhotoDocument) {
                  $profilePhotoDocument = optional($admin->applicant)->documents
                    ?->first(function ($doc) {
                      $mime = strtolower(trim((string) ($doc->mime_type ?? '')));
                      $filename = strtolower(trim((string) ($doc->filename ?? '')));
                      return !empty($doc->filepath) && (str_starts_with($mime, 'image/') || preg_match('/\.(png|jpe?g|gif|webp)$/i', $filename));
                    });
                }
                $profilePhotoUrl = $profilePhotoDocument?->filepath ? asset('storage/'.$profilePhotoDocument->filepath) : null;
                $matrixEmployeeModalData = [
                  'employee_id' => $employeeIdDisplay,
                  'first_name' => trim((string) ($admin->first_name ?? '')) !== '' ? trim((string) ($admin->first_name ?? '')) : 'N/A',
                  'last_name' => trim((string) ($admin->last_name ?? '')) !== '' ? trim((string) ($admin->last_name ?? '')) : 'N/A',
                  'gender' => $genderDisplay,
                  'hired_date' => $hireDateDisplay,
                  'position' => $positionDisplay,
                  'department' => $departmentDisplay,
                  'full_name' => $fullName !== '' ? $fullName : 'N/A',
                  'age' => $ageDisplay,
                  'rate' => $salaryText !== '' ? $salaryText : 'N/A',
                  'employment_status' => $employmentStatus,
                  'regularization_date' => $regularizationDateDisplay,
                  'initials' => $initials,
                  'profile_photo_url' => $profilePhotoUrl,
                ];
              @endphp
              <tr class="odd:bg-white even:bg-stone-50/40">
                <td class="border border-stone-300 px-3 py-3 font-medium">
                  <span
                    role="button"
                    tabindex="0"
                    class="matrix-name-button"
                    data-matrix-employee="{{ e(json_encode($matrixEmployeeModalData)) }}"
                    onclick="openMatrixEmployeeModal({{ \Illuminate\Support\Js::from($matrixEmployeeModalData) }})"
                    onkeydown="handleMatrixNameKeydown(event, this)"
                  >
                    {{ $fullName !== '' ? $fullName : '-' }}
                  </span>
                </td>
                <td class="border border-stone-300 px-3 py-3">
                  @if ($degreeRows->isNotEmpty())
                    <div class="space-y-1">
                      @foreach ($degreeRows as $row)
                        <div>
                          - <strong>{{ trim((string) ($row->degree_name ?? '-')) }}</strong>
                          @if (trim((string) ($row->school_name ?? '')) !== '')
                            , <span class="italic">{{ trim((string) $row->school_name) }}</span>
                          @endif
                          @if (trim((string) ($row->year_finished ?? '')) !== '')
                            , {{ trim((string) $row->year_finished) }}
                          @endif
                        </div>
                      @endforeach
                    </div>
                  @elseif ($fallbackDegrees->isNotEmpty())
                    <div class="space-y-1">
                      @foreach ($fallbackDegrees as $row)
                        <div>
                          - <strong>{{ $row['degree_name'] }}</strong>
                          @if ($row['school_name'] !== '')
                            , <span class="italic">{{ $row['school_name'] }}</span>
                          @endif
                          @if ($row['year_finished'] !== '')
                            , {{ $row['year_finished'] }}
                          @endif
                        </div>
                      @endforeach
                    </div>
                  @else
                    -
                  @endif
                </td>
                <td class="border border-stone-300 px-3 py-3">
                  @if ($jobRole !== '' || $positionValue !== '')
                    <div class="space-y-1">
                      @if ($jobRole !== '')
                        <div>{{ $jobRole }}</div>
                      @endif
                      @if ($positionValue !== '')
                        <div class="text-stone-700">{{ $positionValue }}{{ $departmentValue ? ' - '.$departmentValue : '' }}</div>
                      @endif
                    </div>
                  @else
                    -
                  @endif
                </td>
                <td class="border border-stone-300 px-3 py-3">{{ $employmentStatus !== '' ? $employmentStatus : '-' }}</td>
                <td class="border border-stone-300 px-3 py-3">{{ $salaryValue !== '' ? $salaryValue : '-' }}</td>
                <td class="border border-stone-300 px-3 py-3">
                  @if ($benefits->isNotEmpty())
                    {{ $benefits->implode(', ') }}
                  @else
                    -
                  @endif
                </td>
                <td class="border border-stone-300 px-3 py-3">
                  @if ($experienceLines->isNotEmpty() || $displayWorkDuration !== '')
                    <div class="space-y-1">
                      @foreach ($experienceLines as $line)
                        <div>&bull; {{ $line }}</div>
                        @if ($displayWorkDuration !== '')
                          <div class="pl-4 text-stone-700">({{ $displayWorkDuration }})</div>
                        @endif
                      @endforeach
                    </div>
                  @else
                    -
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="border border-stone-300 px-3 py-6 text-center text-stone-500">
                  No approved department-head employees found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>

<div id="matrix-employee-modal" class="matrix-employee-modal" onclick="closeMatrixEmployeeModalOnBackdrop(event)">
  <div class="matrix-employee-card" role="dialog" aria-modal="true" aria-labelledby="matrix-employee-modal-title">
    <div class="matrix-employee-card-header">
      <h3 id="matrix-employee-modal-title" class="matrix-employee-card-title">Employee Information</h3>
      <button type="button" class="matrix-employee-close" onclick="closeMatrixEmployeeModal()" aria-label="Close modal">&times;</button>
    </div>
    <div class="matrix-employee-card-body">
      <div id="matrix-employee-avatar" class="matrix-employee-avatar">NA</div>
      <div class="matrix-employee-fields">
        <div class="matrix-employee-field"><div class="matrix-employee-label">Employee ID:</div><div id="matrix-employee-id" class="matrix-employee-value">-</div></div>
        <div class="matrix-employee-field"><div class="matrix-employee-label">First Name:</div><div id="matrix-employee-first-name" class="matrix-employee-value">-</div></div>
        <div class="matrix-employee-field"><div class="matrix-employee-label">Last Name:</div><div id="matrix-employee-last-name" class="matrix-employee-value">-</div></div>
        <div class="matrix-employee-field"><div class="matrix-employee-label">Gender:</div><div id="matrix-employee-gender" class="matrix-employee-value">-</div></div>
        <div class="matrix-employee-field"><div class="matrix-employee-label">Hired Date:</div><div id="matrix-employee-hired-date" class="matrix-employee-value">-</div></div>
        <div class="matrix-employee-field"><div class="matrix-employee-label">Position:</div><div id="matrix-employee-position" class="matrix-employee-value">-</div></div>
        <div class="matrix-employee-field"><div class="matrix-employee-label">Department:</div><div id="matrix-employee-department" class="matrix-employee-value">-</div></div>
      </div>
    </div>
  </div>
</div>

<div id="matrix-excel-export-modal" class="matrix-export-modal" onclick="closeMatrixExcelExportModalOnBackdrop(event)">
  <div class="matrix-export-card" role="dialog" aria-modal="true" aria-labelledby="matrix-excel-export-title">
    <div class="matrix-export-card-header">
      <h3 id="matrix-excel-export-title" class="text-lg font-semibold text-stone-900">Choose Excel Format</h3>
      <p class="mt-1 text-sm text-stone-600">Select which version you want to export.</p>
    </div>
    <div class="matrix-export-card-body">
      <button type="button" onclick="confirmMatrixExcelExport('CHED')" class="inline-flex items-center justify-center rounded-lg border border-blue-300 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 hover:bg-blue-100">CHED</button>
      <button type="button" onclick="confirmMatrixExcelExport('DOLE')" class="inline-flex items-center justify-center rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 hover:bg-emerald-100">DOLE</button>
      <button type="button" onclick="closeMatrixExcelExportModal()" class="inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-100">Cancel</button>
    </div>
  </div>
</div>

<script>
  let matrixExcelExportState = { tableId: null, title: null };

  function openMatrixEmployeeModal(employee) {
    const modal = document.getElementById('matrix-employee-modal');
    if (!modal || !employee) {
      return;
    }

    const map = {
      'matrix-employee-id': employee.employee_id,
      'matrix-employee-first-name': employee.first_name,
      'matrix-employee-last-name': employee.last_name,
      'matrix-employee-gender': employee.gender,
      'matrix-employee-hired-date': employee.hired_date,
      'matrix-employee-position': employee.position,
      'matrix-employee-department': employee.department,
    };

    Object.entries(map).forEach(([id, value]) => {
      const element = document.getElementById(id);
      if (element) {
        element.textContent = value || 'N/A';
      }
    });

    const avatar = document.getElementById('matrix-employee-avatar');
    if (avatar) {
      if (employee.profile_photo_url) {
        avatar.innerHTML = `<img src="${employee.profile_photo_url}" alt="Employee Photo">`;
      } else {
        avatar.textContent = employee.initials || 'NA';
      }
    }

    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function handleMatrixNameKeydown(event, trigger) {
    if (event.key !== 'Enter' && event.key !== ' ') {
      return;
    }

    event.preventDefault();
    trigger.click();
  }

  function closeMatrixEmployeeModal() {
    const modal = document.getElementById('matrix-employee-modal');
    if (!modal) {
      return;
    }

    modal.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  function closeMatrixEmployeeModalOnBackdrop(event) {
    if (event.target?.id !== 'matrix-employee-modal') {
      return;
    }

    closeMatrixEmployeeModal();
  }

  function openMatrixExcelExportModal(tableId, title) {
    const modal = document.getElementById('matrix-excel-export-modal');
    if (!modal) {
      downloadMatrixExcel(tableId, title, 'CHED');
      return;
    }

    matrixExcelExportState = { tableId, title };
    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function closeMatrixExcelExportModal() {
    const modal = document.getElementById('matrix-excel-export-modal');
    if (!modal) {
      return;
    }

    modal.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  function closeMatrixExcelExportModalOnBackdrop(event) {
    if (event.target?.id !== 'matrix-excel-export-modal') {
      return;
    }

    closeMatrixExcelExportModal();
  }

  function confirmMatrixExcelExport(formatLabel) {
    const { tableId, title } = matrixExcelExportState;
    closeMatrixExcelExportModal();
    if (!tableId || !title) {
      return;
    }
    downloadMatrixExcel(tableId, title, formatLabel);
  }

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      closeMatrixEmployeeModal();
    }
  });

  function createExportColGroup(widths) {
    const colGroup = document.createElement('colgroup');
    widths.forEach((width) => {
      const col = document.createElement('col');
      col.style.width = width;
      const numericWidth = parseInt(width, 10);
      if (Number.isFinite(numericWidth)) {
        col.setAttribute('width', String(numericWidth));
      }
      colGroup.appendChild(col);
    });
    return colGroup;
  }

  function applyExportColumnWidths(table, widths) {
    Array.from(table.rows).forEach((row) => {
      Array.from(row.cells).forEach((cell, index) => {
        if (cell.colSpan === widths.length) {
          return;
        }
        const width = widths[index];
        if (!width) {
          return;
        }
        cell.style.width = width;
        cell.style.minWidth = width;
        cell.style.maxWidth = width;
        const numericWidth = parseInt(width, 10);
        if (Number.isFinite(numericWidth)) {
          cell.setAttribute('width', String(numericWidth));
        }
      });
    });
  }

  function buildExcelExportTable(sourceTable, titleText, widths) {
    const exportTable = document.createElement('table');
    exportTable.appendChild(createExportColGroup(widths));

    const exportHead = document.createElement('thead');
    const titleRow = document.createElement('tr');
    const titleCell = document.createElement('th');
    titleCell.colSpan = widths.length;
    titleCell.textContent = titleText;
    titleRow.appendChild(titleCell);
    exportHead.appendChild(titleRow);

    const sourceHeaderRow = sourceTable.querySelector('thead tr');
    if (sourceHeaderRow) {
      exportHead.appendChild(sourceHeaderRow.cloneNode(true));
    }

    exportTable.appendChild(exportHead);

    const exportBody = document.createElement('tbody');
    sourceTable.querySelectorAll('tbody tr').forEach((row) => {
      exportBody.appendChild(row.cloneNode(true));
    });
    exportTable.appendChild(exportBody);

    return exportTable;
  }

  function downloadMatrixFile(content, fileName, mimeType) {
    const blob = new Blob([content], { type: mimeType });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = fileName;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  }

  function escapeExcelXml(value) {
    return String(value ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&apos;');
  }

  function escapeExcelFormulaString(value) {
    return String(value ?? '').replace(/"/g, '""');
  }

  function normalizeExcelCellText(cell) {
    return (cell.innerText || cell.textContent || '')
      .replace(/\r\n/g, '\n')
      .replace(/\n{3,}/g, '\n\n')
      .trim();
  }

  function collectMatrixEmployeeDetails(sourceTable) {
    return Array.from(sourceTable.querySelectorAll('tbody tr')).map((row, rowIndex) => {
      const trigger = row.querySelector('.matrix-name-button');
      if (!trigger) {
        return null;
      }

      let employee = null;
      try {
        employee = JSON.parse(trigger.dataset.matrixEmployeeEmployee || trigger.dataset.matrixEmployee || '{}');
      } catch (error) {
        employee = null;
      }

      if (!employee) {
        return null;
      }

      const fullName = [employee.first_name, employee.last_name]
        .map((value) => String(value || '').trim())
        .filter(Boolean)
        .join(' ') || `Employee ${rowIndex + 1}`;

      const details = [
        ['Employee Name', fullName],
        ['Employee ID', employee.employee_id || 'N/A'],
        ['First Name', employee.first_name || 'N/A'],
        ['Last Name', employee.last_name || 'N/A'],
        ['Age', employee.age || 'N/A'],
        ['Gender', employee.gender || 'N/A'],
        ['Hired Date', employee.hired_date || 'N/A'],
        ['Position', employee.position || 'N/A'],
        ['Department', employee.department || 'N/A'],
        ['Rate', employee.rate || 'N/A'],
        ['Status of Employment', employee.employment_status || 'N/A'],
      ];

      return {
        rowIndex,
        fullName,
        age: employee.age || 'N/A',
        gender: employee.gender || 'N/A',
        hiredDate: employee.hired_date || 'N/A',
        position: employee.position || 'N/A',
        rate: employee.rate || 'N/A',
        employmentStatus: employee.employment_status || 'N/A',
        regularizationDate: employee.regularization_date || '',
        details,
      };
    }).filter(Boolean);
  }

  function getMatrixHeaderRow(sourceTable, widths) {
    const rows = Array.from(sourceTable.querySelectorAll('thead tr'));
    return rows.find((row) => row.cells.length === widths.length) || rows[rows.length - 1] || null;
  }

  function escapeExcelWorksheetName(value) {
    const sanitized = String(value ?? '')
      .replace(/[\\\/\?\*\[\]:]/g, ' ')
      .replace(/\s+/g, ' ')
      .trim();
    return (sanitized || 'Sheet').slice(0, 31);
  }

  function buildMatrixSheetNameMap(employeeDetails) {
    const usedNames = new Set();
    return new Map(employeeDetails.map((employee) => {
      const baseName = escapeExcelWorksheetName(`${employee.fullName} Details`);
      let sheetName = baseName;
      let counter = 2;
      while (usedNames.has(sheetName)) {
        const suffix = ` ${counter}`;
        sheetName = `${baseName.slice(0, Math.max(1, 31 - suffix.length))}${suffix}`;
        counter += 1;
      }
      usedNames.add(sheetName);
      return [employee.rowIndex, sheetName];
    }));
  }

  function buildMatrixDetailSheetXml(employee, sheetName) {
    const detailRows = employee.details.map(([label, value]) => `
      <Row ss:AutoFitHeight="1">
        <Cell ss:StyleID="detailLabel"><Data ss:Type="String">${escapeExcelXml(label)}</Data></Cell>
        <Cell ss:StyleID="detailValue"><Data ss:Type="String">${escapeExcelXml(value)}</Data></Cell>
      </Row>
    `).join('');

    return `
      <Worksheet ss:Name="${escapeExcelXml(sheetName)}">
        <Table>
          <Column ss:AutoFitWidth="0" ss:Width="150"/>
          <Column ss:AutoFitWidth="0" ss:Width="260"/>
          <Row ss:AutoFitHeight="0" ss:Height="26">
            <Cell ss:MergeAcross="1" ss:StyleID="detailTitle">
              <Data ss:Type="String">${escapeExcelXml(employee.fullName)}</Data>
            </Cell>
          </Row>
          ${detailRows}
        </Table>
      </Worksheet>
    `;
  }

  function getMatrixDoleProfile() {
    return {
      establishmentName: 'NORTHEASTERN COLLEGE',
      headOfficeAddress: 'VILLASIS, SANTIAGO CITY',
      branchOfficeAddress: '',
      principalActivity: '',
      ownerPresident: 'TOMAS C. BAUTISTA, PhD',
      contactNumber: '',
      emailAddress: 'ncpresidentsoffice@gmail.com',
      ownership: 'Corporation',
      noOfShifts: '',
      schedule: '',
    };
  }

  function getMatrixDoleSummary(employeeDetails) {
    const parseAge = (value) => {
      const numeric = parseInt(String(value ?? '').replace(/[^\d]/g, ''), 10);
      return Number.isFinite(numeric) ? numeric : null;
    };
    const normalizeGender = (value) => String(value ?? '').trim().toUpperCase();
    const summary = {
      below15: 0,
      age15to17: 0,
      age18to24: 0,
      age25to59: 0,
      age60to65: 0,
      males: 0,
      females: 0,
      total: employeeDetails.length,
    };

    employeeDetails.forEach((employee) => {
      const age = parseAge(employee.age);
      const gender = normalizeGender(employee.gender);
      if (gender.startsWith('M')) summary.males += 1;
      if (gender.startsWith('F')) summary.females += 1;
      if (age === null) return;
      if (age < 15) summary.below15 += 1;
      else if (age <= 17) summary.age15to17 += 1;
      else if (age <= 24) summary.age18to24 += 1;
      else if (age <= 59) summary.age25to59 += 1;
      else if (age <= 65) summary.age60to65 += 1;
    });

    return summary;
  }

  function buildDoleExcelXml(employeeDetails) {
    const profile = getMatrixDoleProfile();
    const summary = getMatrixDoleSummary(employeeDetails);
    const checkbox = (active) => active ? 'X' : '';
    const statusValue = (employee, label) => employee.employmentStatus === label ? '/' : '';
    const contractorRows = [1, 2, 3].map((index) => `
      <Row><Cell ss:Index="2" ss:StyleID="doleSectionCell"><Data ss:Type="Number">${index}</Data></Cell><Cell ss:MergeAcross="3" ss:StyleID="doleSectionCell"></Cell><Cell ss:MergeAcross="2" ss:StyleID="doleSectionCell"></Cell><Cell ss:MergeAcross="1" ss:StyleID="doleSectionCell"></Cell><Cell ss:MergeAcross="2" ss:StyleID="doleSectionCell"></Cell></Row>
    `).join('');
    const principalRows = [1, 2].map((index, rowIndex) => `
      <Row><Cell ss:Index="2" ss:StyleID="${rowIndex === 1 ? 'doleNote' : 'doleSectionCell'}"><Data ss:Type="String">${rowIndex === 1 ? 'add separate sheet if needed' : ''}</Data></Cell><Cell ss:StyleID="doleSectionCell"><Data ss:Type="Number">${index}</Data></Cell><Cell ss:MergeAcross="3" ss:StyleID="doleSectionCell"></Cell><Cell ss:MergeAcross="2" ss:StyleID="doleSectionCell"></Cell><Cell ss:MergeAcross="1" ss:StyleID="doleSectionCell"></Cell></Row>
    `).join('');
    const rows = [
      '<Row ss:AutoFitHeight="0" ss:Height="24"><Cell ss:MergeAcross="19" ss:StyleID="doleTitle"><Data ss:Type="String">ESTABLISHMENT WORKER&apos;S PROFILE</Data></Cell></Row>',
      `<Row><Cell ss:Index="2" ss:StyleID="doleLabel"><Data ss:Type="String">Name of Establishment:</Data></Cell><Cell ss:MergeAcross="5" ss:StyleID="doleValueBold"><Data ss:Type="String">${escapeExcelXml(profile.establishmentName)}</Data></Cell><Cell ss:Index="10" ss:StyleID="doleLabel"><Data ss:Type="String">Kind of Ownership</Data></Cell><Cell ss:Index="15" ss:StyleID="doleMiniHeader"><Data ss:Type="String">Age Group</Data></Cell><Cell ss:StyleID="doleMiniHeader"><Data ss:Type="String">Male</Data></Cell><Cell ss:StyleID="doleMiniHeader"><Data ss:Type="String">Female</Data></Cell><Cell ss:StyleID="doleMiniHeader"><Data ss:Type="String">Total</Data></Cell><Cell ss:StyleID="doleMiniHeader"><Data ss:Type="String">No of Shifts</Data></Cell><Cell ss:StyleID="doleMiniHeader"><Data ss:Type="String">No.of Workers /shifts</Data></Cell></Row>`,
      `<Row><Cell ss:Index="2" ss:StyleID="doleLabel"><Data ss:Type="String">Head Office Address:</Data></Cell><Cell ss:MergeAcross="5" ss:StyleID="doleValueBold"><Data ss:Type="String">${escapeExcelXml(profile.headOfficeAddress)}</Data></Cell><Cell ss:Index="10" ss:StyleID="doleCheck"><Data ss:Type="String">${checkbox(profile.ownership === 'Sole Proprietorship')}</Data></Cell><Cell ss:MergeAcross="3" ss:StyleID="doleValue"><Data ss:Type="String">Sole Proprietorship</Data></Cell><Cell ss:Index="15" ss:StyleID="doleSectionCell"><Data ss:Type="String">below 15</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="Number">0</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="Number">0</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="Number">0</Data></Cell><Cell ss:StyleID="doleMiniHeader"><Data ss:Type="String">Schedule</Data></Cell><Cell ss:StyleID="doleSectionCell"></Cell></Row>`,
      `<Row><Cell ss:Index="2" ss:StyleID="doleLabel"><Data ss:Type="String">Brach Office Address:</Data></Cell><Cell ss:MergeAcross="5" ss:StyleID="doleValue"><Data ss:Type="String">${escapeExcelXml(profile.branchOfficeAddress)}</Data></Cell><Cell ss:Index="10" ss:StyleID="doleCheck"><Data ss:Type="String">${checkbox(profile.ownership === 'Partnership')}</Data></Cell><Cell ss:MergeAcross="3" ss:StyleID="doleValue"><Data ss:Type="String">Partnership</Data></Cell><Cell ss:Index="15" ss:StyleID="doleSectionCell"><Data ss:Type="String">15-17 yrs.</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="Number">0</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="Number">0</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="Number">0</Data></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell></Row>`,
      `<Row><Cell ss:Index="10" ss:StyleID="doleCheck"><Data ss:Type="String">${checkbox(profile.ownership === 'Corporation')}</Data></Cell><Cell ss:MergeAcross="3" ss:StyleID="doleValue"><Data ss:Type="String">Corporation</Data></Cell><Cell ss:Index="15" ss:StyleID="doleSectionCell"><Data ss:Type="String">18-24 yrs.</Data></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell></Row>`,
      `<Row><Cell ss:Index="2" ss:StyleID="doleLabel"><Data ss:Type="String">Principal Product/Main Activity:</Data></Cell><Cell ss:MergeAcross="5" ss:StyleID="doleValue"><Data ss:Type="String">${escapeExcelXml(profile.principalActivity)}</Data></Cell><Cell ss:Index="10" ss:StyleID="doleCheck"><Data ss:Type="String">${checkbox(profile.ownership === 'Cooperative')}</Data></Cell><Cell ss:MergeAcross="3" ss:StyleID="doleValue"><Data ss:Type="String">Cooperative</Data></Cell><Cell ss:Index="15" ss:StyleID="doleSectionCell"><Data ss:Type="String">25-59 yrs.</Data></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell></Row>`,
      `<Row><Cell ss:Index="2" ss:StyleID="doleLabel"><Data ss:Type="String">Owner/President/CEO:</Data></Cell><Cell ss:MergeAcross="5" ss:StyleID="doleValueBold"><Data ss:Type="String">${escapeExcelXml(profile.ownerPresident)}</Data></Cell><Cell ss:Index="15" ss:StyleID="doleSectionCell"><Data ss:Type="String">60-65 yrs.</Data></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell></Row>`,
      `<Row><Cell ss:Index="2" ss:StyleID="doleLabel"><Data ss:Type="String">Contact Number:</Data></Cell><Cell ss:MergeAcross="5" ss:StyleID="doleValue"><Data ss:Type="String">${escapeExcelXml(profile.contactNumber)}</Data></Cell><Cell ss:Index="15" ss:StyleID="doleSectionCell"><Data ss:Type="String">Total</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="Number">${summary.males}</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="Number">${summary.females}</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="Number">${summary.total}</Data></Cell><Cell ss:StyleID="doleSectionCell"></Cell><Cell ss:StyleID="doleSectionCell"></Cell></Row>`,
      `<Row><Cell ss:Index="2" ss:StyleID="doleLabel"><Data ss:Type="String">Email Address:</Data></Cell><Cell ss:MergeAcross="5" ss:StyleID="doleValueLink"><Data ss:Type="String">${escapeExcelXml(profile.emailAddress)}</Data></Cell></Row>`,
      '<Row><Cell ss:Index="2" ss:MergeAcross="1" ss:StyleID="doleLabel"><Data ss:Type="String">Contractors with existing agreements:</Data></Cell><Cell ss:MergeAcross="3" ss:StyleID="doleHeader"><Data ss:Type="String">Name of Contractor/Subcontractor</Data></Cell><Cell ss:MergeAcross="2" ss:StyleID="doleHeader"><Data ss:Type="String">Regstrered Address</Data></Cell><Cell ss:MergeAcross="1" ss:StyleID="doleHeader"><Data ss:Type="String">No. of employees Deployed</Data></Cell><Cell ss:MergeAcross="2" ss:StyleID="doleHeader"><Data ss:Type="String">Service/s being provided by the</Data></Cell></Row>',
      contractorRows,
      '<Row/>',
      '<Row><Cell ss:Index="3" ss:MergeAcross="3" ss:StyleID="doleHeader"><Data ss:Type="String">Name of Principal Employers</Data></Cell><Cell ss:MergeAcross="2" ss:StyleID="doleHeader"><Data ss:Type="String">Office Address</Data></Cell><Cell ss:MergeAcross="1" ss:StyleID="doleHeader"><Data ss:Type="String">No. of employees Deployed</Data></Cell></Row>',
      principalRows,
      '<Row/>',
      '<Row><Cell ss:StyleID="doleHeader" ss:MergeDown="1"><Data ss:Type="String">No.</Data></Cell><Cell ss:StyleID="doleHeader" ss:MergeDown="1"><Data ss:Type="String">NAME OF EMPLYEE</Data></Cell><Cell ss:StyleID="doleHeader" ss:MergeDown="1"><Data ss:Type="String">GENDER</Data></Cell><Cell ss:StyleID="doleHeader" ss:MergeDown="1"><Data ss:Type="String">AGE</Data></Cell><Cell ss:StyleID="doleHeader" ss:MergeDown="1"><Data ss:Type="String">RATE</Data></Cell><Cell ss:StyleID="doleHeader" ss:MergeDown="1"><Data ss:Type="String">DATE HIRED</Data></Cell><Cell ss:StyleID="doleHeader" ss:MergeDown="1"><Data ss:Type="String">DATE REGULARIZED</Data></Cell><Cell ss:StyleID="doleHeader" ss:MergeDown="1"><Data ss:Type="String">POSITION</Data></Cell><Cell ss:MergeAcross="11" ss:StyleID="doleHeader"><Data ss:Type="String">STATUS OF EMPLOYMENT</Data></Cell></Row>',
      '<Row><Cell ss:Index="9" ss:StyleID="doleStatusHeader"><Data ss:Type="String">Regular</Data></Cell><Cell ss:StyleID="doleStatusHeader"><Data ss:Type="String">Probationary</Data></Cell><Cell ss:StyleID="doleStatusHeader"><Data ss:Type="String">Fixed term</Data></Cell><Cell ss:StyleID="doleStatusHeader"><Data ss:Type="String">Casual</Data></Cell><Cell ss:StyleID="doleStatusHeader"><Data ss:Type="String">Regular-Seasonal</Data></Cell><Cell ss:StyleID="doleStatusHeader"><Data ss:Type="String">Contract workers</Data></Cell><Cell ss:StyleID="doleStatusHeader"><Data ss:Type="String">PWD</Data></Cell><Cell ss:StyleID="doleStatusHeader"><Data ss:Type="String">Foreign National</Data></Cell><Cell ss:StyleID="doleStatusHeader"><Data ss:Type="String">Apprent</Data></Cell><Cell ss:StyleID="doleStatusHeader"><Data ss:Type="String">Learners</Data></Cell><Cell ss:StyleID="doleStatusHeader"><Data ss:Type="String">Trainee (DTS)</Data></Cell><Cell ss:StyleID="doleStatusHeader"><Data ss:Type="String">Trainee (DTP)</Data></Cell></Row>',
    ];

    employeeDetails.forEach((employee, index) => {
      rows.push(`
        <Row><Cell ss:StyleID="doleCellCenter"><Data ss:Type="Number">${index + 1}</Data></Cell><Cell ss:StyleID="doleCell"><Data ss:Type="String">${escapeExcelXml(employee.fullName)}</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="String">${escapeExcelXml(employee.gender)}</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="String">${escapeExcelXml(employee.age)}</Data></Cell><Cell ss:StyleID="doleCell"><Data ss:Type="String">${escapeExcelXml(employee.rate)}</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="String">${escapeExcelXml(employee.hiredDate)}</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="String">${escapeExcelXml(employee.regularizationDate)}</Data></Cell><Cell ss:StyleID="doleCell"><Data ss:Type="String">${escapeExcelXml(employee.position)}</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="String">${statusValue(employee, 'Permanent')}</Data></Cell><Cell ss:StyleID="doleCellCenter"><Data ss:Type="String">${statusValue(employee, 'Probationary')}</Data></Cell><Cell ss:StyleID="doleCellCenter"></Cell><Cell ss:StyleID="doleCellCenter"></Cell><Cell ss:StyleID="doleCellCenter"></Cell><Cell ss:StyleID="doleCellCenter"></Cell><Cell ss:StyleID="doleCellCenter"></Cell><Cell ss:StyleID="doleCellCenter"></Cell><Cell ss:StyleID="doleCellCenter"></Cell><Cell ss:StyleID="doleCellCenter"></Cell><Cell ss:StyleID="doleCellCenter"></Cell><Cell ss:StyleID="doleCellCenter"></Cell></Row>
      `);
    });

    return [
      '<?xml version="1.0"?>',
      '<?mso-application progid="Excel.Sheet"?>',
      '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"',
      ' xmlns:o="urn:schemas-microsoft-com:office:office"',
      ' xmlns:x="urn:schemas-microsoft-com:office:excel"',
      ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"',
      ' xmlns:html="http://www.w3.org/TR/REC-html40">',
      '<Styles>',
      '<Style ss:ID="Default" ss:Name="Normal"><Alignment ss:Vertical="Center" ss:WrapText="1"/><Borders/><Font ss:FontName="Calibri" ss:Size="10" ss:Color="#111827"/><Interior/><NumberFormat/><Protection/></Style>',
      '<Style ss:ID="doleTitle"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Font ss:FontName="Calibri" ss:Size="14" ss:Bold="1"/></Style>',
      '<Style ss:ID="doleLabel"><Alignment ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#A8A29E"/></Borders><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1"/></Style>',
      '<Style ss:ID="doleValue"><Alignment ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6D3D1"/></Borders></Style>',
      '<Style ss:ID="doleValueBold"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/></Borders><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1"/></Style>',
      '<Style ss:ID="doleValueLink"><Alignment ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6D3D1"/></Borders><Font ss:FontName="Calibri" ss:Size="10" ss:Underline="Single" ss:Color="#1D4ED8"/></Style>',
      '<Style ss:ID="doleCheck"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/></Borders><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1"/></Style>',
      '<Style ss:ID="doleMiniHeader"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/></Borders><Font ss:FontName="Calibri" ss:Size="9" ss:Bold="1"/></Style>',
      '<Style ss:ID="doleHeader"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/></Borders><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1"/><Interior ss:Color="#E7E5E4" ss:Pattern="Solid"/></Style>',
      '<Style ss:ID="doleStatusHeader"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/></Borders><Font ss:FontName="Calibri" ss:Size="8"/></Style>',
      '<Style ss:ID="doleSectionCell"><Alignment ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#111827"/></Borders></Style>',
      '<Style ss:ID="doleNote"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Font ss:FontName="Calibri" ss:Size="10" ss:Italic="1"/></Style>',
      '<Style ss:ID="doleCell"><Alignment ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6D3D1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6D3D1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6D3D1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6D3D1"/></Borders></Style>',
      '<Style ss:ID="doleCellCenter"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6D3D1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6D3D1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6D3D1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6D3D1"/></Borders></Style>',
      '</Styles>',
      '<Worksheet ss:Name="Sheet1"><Table>',
      '<Column ss:AutoFitWidth="0" ss:Width="38"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="240"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="58"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="50"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="92"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="88"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="92"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="128"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="45"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="45"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="45"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="45"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="45"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="45"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="45"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="45"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="45"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="45"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="52"/>',
      '<Column ss:AutoFitWidth="0" ss:Width="52"/>',
      rows.join(''),
      '</Table><WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel"><DisplayGridlines/></WorksheetOptions></Worksheet>',
      '</Workbook>',
    ].join('');
  }

  function buildMatrixExcelXml(sourceTable, worksheetName, titleText, widths, employeeDetails = []) {
    const rows = [];
    const detailSheetNameMap = buildMatrixSheetNameMap(employeeDetails);
    rows.push(`
      <Row ss:AutoFitHeight="0" ss:Height="28">
        <Cell ss:MergeAcross="${widths.length - 1}" ss:StyleID="title">
          <Data ss:Type="String">${escapeExcelXml(titleText)}</Data>
        </Cell>
      </Row>
    `);

    const headerRow = getMatrixHeaderRow(sourceTable, widths);
    if (headerRow) {
      const headerCells = Array.from(headerRow.cells).map((cell) => `
        <Cell ss:StyleID="header">
          <Data ss:Type="String">${escapeExcelXml(normalizeExcelCellText(cell))}</Data>
        </Cell>
      `).join('');
      rows.push(`<Row ss:AutoFitHeight="1">${headerCells}</Row>`);
    }

    Array.from(sourceTable.querySelectorAll('tbody tr')).forEach((row, rowIndex) => {
      const cells = Array.from(row.cells).map((cell, cellIndex) => {
        const employee = employeeDetails.find((item) => item.rowIndex === rowIndex);
        const detailSheetName = employee ? detailSheetNameMap.get(rowIndex) : '';
        const cellText = normalizeExcelCellText(cell);
        const styleId = cellIndex === 0
          ? (employee ? 'nameLink' : 'name')
          : (rowIndex % 2 === 0 ? 'bodyAlt' : 'body');
        const formulaAttr = cellIndex === 0 && employee
          ? ` ss:Formula="${escapeExcelXml(`=HYPERLINK("#'${escapeExcelFormulaString(detailSheetName)}'!A1","${escapeExcelFormulaString(cellText)}")`)}"`
          : '';
        return `
          <Cell ss:StyleID="${styleId}"${formulaAttr}>
            <Data ss:Type="String">${escapeExcelXml(cellText)}</Data>
          </Cell>
        `;
      }).join('');

      rows.push(`<Row ss:AutoFitHeight="1">${cells}</Row>`);
    });

    const columns = widths.map((width) => {
      const numericWidth = parseInt(width, 10) || 120;
      return `<Column ss:AutoFitWidth="0" ss:Width="${numericWidth}"/>`;
    }).join('');

    const detailWorksheets = employeeDetails.map((employee) =>
      buildMatrixDetailSheetXml(employee, detailSheetNameMap.get(employee.rowIndex))
    ).join('');

    const xmlParts = [
      '<?xml version="1.0"?>',
      '<?mso-application progid="Excel.Sheet"?>',
      '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"',
      ' xmlns:o="urn:schemas-microsoft-com:office:office"',
      ' xmlns:x="urn:schemas-microsoft-com:office:excel"',
      ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"',
      ' xmlns:html="http://www.w3.org/TR/REC-html40">',
      '<Styles>',
      '<Style ss:ID="Default" ss:Name="Normal"><Alignment ss:Vertical="Top" ss:WrapText="1"/><Borders/>',
      '<Font ss:FontName="Calibri" ss:Size="11" ss:Color="#1F2937"/><Interior/><NumberFormat/><Protection/></Style>',
      '<Style ss:ID="title"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>',
      '<Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1E3A8A"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1E3A8A"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1E3A8A"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1E3A8A"/></Borders>',
      '<Font ss:FontName="Calibri" ss:Size="14" ss:Bold="1" ss:Color="#FFFFFF"/>',
      '<Interior ss:Color="#1D4ED8" ss:Pattern="Solid"/></Style>',
      '<Style ss:ID="header"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>',
      '<Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#93C5FD"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#93C5FD"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#93C5FD"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#93C5FD"/></Borders>',
      '<Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#0F172A"/>',
      '<Interior ss:Color="#DBEAFE" ss:Pattern="Solid"/></Style>',
      '<Style ss:ID="body"><Alignment ss:Vertical="Top" ss:WrapText="1"/>',
      '<Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders>',
      '<Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>',
      '<Style ss:ID="bodyAlt"><Alignment ss:Vertical="Top" ss:WrapText="1"/>',
      '<Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders>',
      '<Interior ss:Color="#EEF6FF" ss:Pattern="Solid"/></Style>',
      '<Style ss:ID="name"><Alignment ss:Vertical="Top" ss:WrapText="0"/>',
      '<Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#94A3B8"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders>',
      '<Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#111827"/>',
      '<Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>',
      '<Style ss:ID="nameLink"><Alignment ss:Vertical="Top" ss:WrapText="0"/>',
      '<Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#94A3B8"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders>',
      '<Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Underline="Single" ss:Color="#1D4ED8"/>',
      '<Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>',
      '<Style ss:ID="detailTitle"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>',
      '<Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1E3A8A"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1E3A8A"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1E3A8A"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1E3A8A"/></Borders>',
      '<Font ss:FontName="Calibri" ss:Size="13" ss:Bold="1" ss:Color="#FFFFFF"/>',
      '<Interior ss:Color="#1D4ED8" ss:Pattern="Solid"/></Style>',
      '<Style ss:ID="detailLabel"><Alignment ss:Vertical="Center" ss:WrapText="1"/>',
      '<Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders>',
      '<Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#111827"/>',
      '<Interior ss:Color="#F5F7FA" ss:Pattern="Solid"/></Style>',
      '<Style ss:ID="detailValue"><Alignment ss:Vertical="Center" ss:WrapText="1"/>',
      '<Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders>',
      '<Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>',
      '</Styles>',
      `<Worksheet ss:Name="${escapeExcelXml(worksheetName).slice(0, 31)}">`,
      '<Table>',
      columns,
      rows.join(''),
      '</Table>',
      '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel"><DisplayGridlines/><FreezePanes/><FrozenNoSplit/><SplitHorizontal>2</SplitHorizontal><TopRowBottomPane>2</TopRowBottomPane></WorksheetOptions>',
      '</Worksheet>',
      detailWorksheets,
      '</Workbook>',
    ];

    return xmlParts.join('');
  }

  function styleMatrixExcelTable(table) {
    table.style.width = '100%';
    table.style.borderCollapse = 'collapse';
    table.style.tableLayout = 'fixed';
    table.style.fontFamily = 'Calibri, Arial, sans-serif';
    table.style.fontSize = '11pt';
    table.style.color = '#1f2937';
    table.style.backgroundColor = '#ffffff';

    table.querySelectorAll('ul').forEach((list) => {
      list.style.margin = '0';
      list.style.paddingLeft = '0';
      list.style.marginLeft = '2pt';
      list.style.listStylePosition = 'inside';
    });

    table.querySelectorAll('li').forEach((item) => {
      item.style.margin = '0 0 2pt 0';
      item.style.padding = '0';
    });

    table.querySelectorAll('td div, td span, td strong, th div, th span').forEach((node) => {
      node.style.wordBreak = 'break-word';
      node.style.overflowWrap = 'anywhere';
    });

    const rows = table.querySelectorAll('tr');
    rows.forEach((row, rowIndex) => {
      row.querySelectorAll('th, td').forEach((cell) => {
        cell.style.border = '1px solid #cbd5e1';
        cell.style.padding = '7pt 6pt';
        cell.style.verticalAlign = 'top';
        cell.style.wordWrap = 'break-word';
        cell.style.overflowWrap = 'anywhere';
        cell.style.lineHeight = '1.35';
        cell.style.whiteSpace = 'normal';
      });

      if (rowIndex === 0) {
        row.querySelectorAll('th').forEach((cell) => {
          cell.style.backgroundColor = '#1d4ed8';
          cell.style.color = '#ffffff';
          cell.style.fontWeight = '700';
          cell.style.textAlign = 'center';
          cell.style.verticalAlign = 'middle';
          cell.style.border = '1px solid #1e3a8a';
          cell.style.fontSize = '14pt';
          cell.style.padding = '12pt 10pt';
        });
      }

      if (rowIndex === 1) {
        row.querySelectorAll('th').forEach((cell) => {
          cell.style.backgroundColor = '#dbeafe';
          cell.style.color = '#0f172a';
          cell.style.fontWeight = '700';
          cell.style.textAlign = 'center';
          cell.style.verticalAlign = 'middle';
          cell.style.border = '1px solid #93c5fd';
        });
      }

      if (rowIndex > 1) {
        const fillColor = rowIndex % 2 === 0 ? '#f8fafc' : '#eef6ff';
        row.querySelectorAll('td').forEach((cell) => {
          cell.style.backgroundColor = fillColor;
        });
      }

      if (row.cells[0]) {
        row.cells[0].style.borderRight = '2px solid #94a3b8';
        row.cells[0].style.width = '260px';
        row.cells[0].style.minWidth = '260px';
        row.cells[0].style.maxWidth = '260px';
        row.cells[0].style.whiteSpace = rowIndex === 0 ? 'normal' : 'nowrap';
        row.cells[0].style.wordBreak = 'normal';
        row.cells[0].style.overflowWrap = 'normal';
        row.cells[0].setAttribute('width', '260');
      }

      if (row.cells[1]) {
        row.cells[1].style.borderRight = '2px solid #cbd5e1';
        row.cells[1].style.width = '360px';
        row.cells[1].style.minWidth = '360px';
        row.cells[1].style.maxWidth = '360px';
        row.cells[1].setAttribute('width', '360');
      }
    });
  }

  function buildMatrixExcelHtml(tableMarkup, title) {
    return `
      <html xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:x="urn:schemas-microsoft-com:office:excel"
            xmlns="http://www.w3.org/TR/REC-html40">
      <head>
        <meta charset="utf-8">
        <meta name="ProgId" content="Excel.Sheet">
        <meta name="Generator" content="Microsoft Excel 15">
        <title>${title}</title>
        <style>
          body {
            margin: 0;
            padding: 18px;
            background: #e2e8f0;
            font-family: Calibri, Arial, sans-serif;
          }

          .sheet-wrap {
            background: #ffffff;
            padding: 10px;
            border: 1px solid #bfdbfe;
          }

          table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            mso-table-layout-alt: fixed;
          }

          br {
            mso-data-placement: same-cell;
          }

          col {
            mso-width-source: userset;
          }

          td, th {
            mso-number-format: "\\@";
          }
        </style>
      </head>
      <body>
        <div class="sheet-wrap">
          ${tableMarkup}
        </div>
      </body>
      </html>
    `;
  }

  function downloadMatrixWord(tableId, title) {
    const table = document.getElementById(tableId);
    if (!table) {
      return;
    }

    const wordTable = table.cloneNode(true);
    const wordTitleRow = wordTable.createTHead().insertRow(0);
    const wordTitleCell = document.createElement('th');
    wordTitleCell.colSpan = 7;
    wordTitleCell.textContent = '14. Matrix list of school administrators, i.e., President, Vice-President(s), Deans, Department Heads, etc, including:';
    wordTitleCell.style.border = '1px solid #000';
    wordTitleCell.style.padding = '6pt 5pt';
    wordTitleCell.style.textAlign = 'left';
    wordTitleCell.style.fontWeight = '700';
    wordTitleRow.appendChild(wordTitleCell);
    const exportWidths = ['13%', '32%', '10%', '10%', '10%', '10%', '15%'];
    wordTable.insertBefore(createExportColGroup(exportWidths), wordTable.firstChild);
    applyExportColumnWidths(wordTable, exportWidths);
    wordTable.style.width = '100%';
    wordTable.style.borderCollapse = 'collapse';
    wordTable.style.tableLayout = 'fixed';
    wordTable.querySelectorAll('ul').forEach((list) => {
      list.style.margin = '0';
      list.style.paddingLeft = '0';
      list.style.marginLeft = '2pt';
      list.style.listStylePosition = 'inside';
    });
    wordTable.querySelectorAll('li').forEach((item) => {
      item.style.margin = '0 0 2pt 0';
      item.style.padding = '0';
    });

    const html = `
      <html xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:w="urn:schemas-microsoft-com:office:word"
            xmlns="http://www.w3.org/TR/REC-html40">
      <head>
        <meta charset="utf-8">
        <title>${title}</title>
        <!--[if gte mso 9]>
        <xml>
          <w:WordDocument>
            <w:View>Print</w:View>
            <w:Zoom>90</w:Zoom>
            <w:DoNotOptimizeForBrowser/>
          </w:WordDocument>
        </xml>
        <![endif]-->
        <style>
          @page Section1 {
            size: 13in 8.5in;
            mso-page-orientation: landscape;
            margin: 0.35in;
          }
          body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
          }
          .word-section {
            page: Section1;
          }
          .matrix-title {
            font-size: 12pt;
            font-weight: 700;
            margin: 0 0 8pt 0;
          }
          table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
          }
          th, td {
            border: 1px solid #000;
            padding: 6pt 5pt;
            vertical-align: top;
            word-wrap: break-word;
          }
          th {
            text-align: center;
            font-weight: 700;
          }
          ul {
            margin: 0;
            padding-left: 0;
            margin-left: 2pt;
            list-style-position: inside;
          }
          li {
            margin: 0 0 2pt 0;
            padding: 0;
          }
        </style>
      </head>
      <body>
        <div class="word-section">
          <p class="matrix-title">${title}</p>
          ${wordTable.outerHTML}
        </div>
      </body>
      </html>
    `;

    downloadMatrixFile(html, `${tableId}.doc`, 'application/msword');
  }


  function downloadMatrixExcel(tableId, title, formatLabel = 'CHED') {
    const table = document.getElementById(tableId);
    if (!table) {
      return;
    }

    const exportWidths = ['260px', '360px', '200px', '150px', '150px', '150px', '210px'];
    const employeeDetails = collectMatrixEmployeeDetails(table);
    if (String(formatLabel).toUpperCase() === 'DOLE') {
      const doleXml = buildDoleExcelXml(employeeDetails);
      downloadMatrixFile(doleXml, `${tableId}-dole.xls`, 'application/vnd.ms-excel');
      return;
    }
    const xml = buildMatrixExcelXml(
      table,
      `${title} - ${formatLabel}`,
      '14. Matrix list of school administrators, i.e., President, Vice-President(s), Deans, Department Heads, etc, including:',
      exportWidths,
      employeeDetails
    );

    const safeFormat = String(formatLabel || 'CHED').trim() || 'CHED';
    downloadMatrixFile(xml, `${tableId}-${safeFormat.toLowerCase()}.xls`, 'application/vnd.ms-excel');
  }
</script>

</body>
</html>



