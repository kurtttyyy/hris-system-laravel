<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Academic Non-Teaching Matrix</title>
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

    @media (max-width: 640px) {
      .matrix-employee-card-body {
        grid-template-columns: 1fr;
      }

      .matrix-employee-field {
        grid-template-columns: 1fr;
        gap: 0.35rem;
      }
    }

    @media (min-width: 1024px) {
      .matrix-admin-shell > aside:hover + main {
        margin-left: 18rem;
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
        width: 1.9in !important;
        min-width: 1.9in !important;
        white-space: nowrap !important;
      }

      .matrix-print-wrapper th:nth-child(2),
      .matrix-print-wrapper td:nth-child(2) {
        width: 2.6in !important;
        min-width: 2.6in !important;
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

  <main class="flex-1 ml-16 transition-all duration-300">
    <section class="matrix-print-hide px-4 md:px-8 pt-8 pb-6">
      <div class="rounded-2xl border border-stone-300 bg-white/80 backdrop-blur-sm shadow-sm p-5 md:p-7">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <p class="text-xs uppercase tracking-[0.2em] text-stone-600">Matrix 15</p>
            <h1 class="mt-1 text-xl md:text-2xl font-semibold text-stone-900">
              Matrix List of Academic Non-Teaching Personnel
            </h1>
            <p class="mt-1 text-sm text-stone-600">
                Matrix list of Academic Non-Teaching personnel including (Registrar, Librarian, Guidance Counselor, Researcher, etc.)
            </p>
          </div>
          <div class="matrix-export-actions flex flex-wrap items-center gap-2">
            <button
              type="button"
              onclick="downloadMatrixWord('non-teaching-matrix', 'Academic Non-Teaching Matrix')"
              class="inline-flex items-center gap-2 rounded-lg border border-blue-300 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100"
            >
              <i class="fa-solid fa-file-word"></i>
              Word
            </button>
            <button
              type="button"
              onclick="downloadMatrixExcel('non-teaching-matrix', 'Academic Non-Teaching Matrix')"
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

    <section class="matrix-print-section px-4 md:px-8 pb-10">
      @php
        $rows = collect($nonTeachingEmployees ?? ($administrators ?? []))
          ->filter(function ($staff) {
            $normalize = function ($value) {
              return strtolower(trim((string) ($value ?? '')));
            };
            $jobType = $normalize(optional($staff->employee)->job_type ?: optional(optional($staff->applicant)->position)->job_type);
            if (!in_array($jobType, ['non-teaching', 'non teaching', 'nt'], true)) {
              return false;
            }

            $hasJobRole = trim((string) ($staff->job_role ?? '')) !== '';
            $hasDepartmentHead = trim((string) ($staff->department_head ?? '')) !== '';
            return !($hasJobRole && $hasDepartmentHead);
          })
          ->values();
      @endphp
      <div class="matrix-print-wrapper overflow-x-auto rounded-2xl border border-stone-300 bg-white shadow-sm">
        <table id="non-teaching-matrix" class="min-w-[1320px] w-full text-sm text-stone-800 border-collapse">
          <thead class="bg-stone-100">
            <tr class="hidden print:table-row">
              <th colspan="7" class="border border-stone-900 bg-white px-3 py-2 text-left text-sm font-bold text-stone-900">
                15. Matrix list of academic non-teaching personnel including (Registrar, Librarian, Guidance Counselor, Researcher, etc.)
              </th>
            </tr>
            <tr>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[20%]">Names of Non-Teaching</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[340px]">Educational Qualifications</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[220px]">Feild/s of Specialization</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[150px]">Status of Employement</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[190px]">Rate of salary per hour/month</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[220px]">Other Employement benefits</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[320px]">Relevant Experience/s</th>
            </tr>
          </thead>
          <tbody class="align-top">
            @forelse ($rows as $staff)
              @php
                $fullName = trim(implode(' ', array_filter([
                  trim((string) ($staff->first_name ?? '')),
                  trim((string) ($staff->middle_name ?? '')),
                  trim((string) ($staff->last_name ?? '')),
                ])));

                $degreeRows = collect(optional($staff->applicant)->degrees ?? [])->values();
                $fallbackDegrees = collect([
                  ['degree_name' => trim((string) optional($staff->education)->doctorate), 'school_name' => trim((string) (optional($staff->applicant)->doctoral_school_name ?? '')), 'year_finished' => trim((string) (optional($staff->applicant)->doctoral_year_finished ?? ''))],
                  ['degree_name' => trim((string) optional($staff->education)->master), 'school_name' => trim((string) (optional($staff->applicant)->master_school_name ?? '')), 'year_finished' => trim((string) (optional($staff->applicant)->master_year_finished ?? ''))],
                  ['degree_name' => trim((string) optional($staff->education)->bachelor), 'school_name' => trim((string) (optional($staff->applicant)->bachelor_school_name ?? '')), 'year_finished' => trim((string) (optional($staff->applicant)->bachelor_year_finished ?? ''))],
                ])->filter(fn ($row) => $row['degree_name'] !== '');

                $specialization = collect([
                  trim((string) (optional($staff->applicant)->field_study ?? '')),
                  trim((string) (optional($staff->applicant)->skills_n_expertise ?? '')),
                  trim((string) (optional(optional($staff->applicant)->position)->skills ?? '')),
                ])->filter()->unique()->values();

                $jobType = strtolower(trim((string) (optional($staff->employee)->job_type ?? optional(optional($staff->applicant)->position)->job_type ?? '')));
                $rawJoinDate = optional($staff->applicant)->date_hired ?? optional($staff->employee)->employement_date;
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

                $salaryPerMonth = trim((string) (optional($staff->salary)->salary ?? ''));
                $salaryPerHour = trim((string) (optional($staff->salary)->rate_per_hour ?? ''));
                $salaryText = '-';
                if ($salaryPerHour !== '' && $salaryPerMonth !== '') {
                  $salaryText = 'Hr: '.$salaryPerHour.' / Mo: '.$salaryPerMonth;
                } elseif ($salaryPerHour !== '') {
                  $salaryText = $salaryPerHour.' per hour';
                } elseif ($salaryPerMonth !== '') {
                  $salaryText = $salaryPerMonth.' per month';
                }

                $benefits = collect([
                  trim((string) (optional($staff->applicant)->benefit ?? '')),
                  trim((string) (optional(optional($staff->applicant)->position)->benifits ?? '')),
                ])->filter()->values();

                $isPlaceholder = function ($value): bool {
                  $normalized = strtolower(trim((string) ($value ?? '')));
                  return $normalized === '' || $normalized === 'n/a' || $normalized === 'na' || $normalized === '-';
                };
                $workPositionRaw = trim((string) (optional($staff->applicant)->work_position ?? ''));
                $workDurationRaw = trim((string) (optional($staff->applicant)->work_duration ?? ''));
                $workPosition = $isPlaceholder($workPositionRaw) ? '' : $workPositionRaw;
                $workDuration = $isPlaceholder($workDurationRaw) ? '' : $workDurationRaw;
                if ($workPosition === '') {
                  $positionFallback = collect([
                    trim((string) (optional($staff->employee)->position ?? '')),
                    trim((string) (optional(optional($staff->applicant)->position)->title ?? '')),
                    trim((string) ($staff->position ?? '')),
                  ])->first(fn ($value) => !$isPlaceholder($value));
                  $workPosition = $positionFallback ?? '';
                }
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
                  ->filter(fn ($value) => $value !== '' && !$isPlaceholder($value))
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
                $employeeIdDisplay = trim((string) (optional($staff->employee)->employee_id ?? $staff->employee_id ?? $staff->id ?? ''));
                $employeeIdDisplay = $employeeIdDisplay !== '' ? $employeeIdDisplay : 'N/A';
                $genderDisplay = collect([
                  trim((string) ($staff->gender ?? '')),
                  trim((string) (optional($staff->employee)->sex ?? '')),
                  trim((string) (optional($staff->applicant)->gender ?? '')),
                ])->first(fn ($value) => $value !== '') ?? 'N/A';
                $positionDisplay = collect([
                  trim((string) ($staff->job_role ?? '')),
                  trim((string) (optional($staff->employee)->position ?? '')),
                  trim((string) (optional(optional($staff->applicant)->position)->title ?? '')),
                  trim((string) ($staff->position ?? '')),
                ])->first(fn ($value) => $value !== '') ?? 'N/A';
                $departmentDisplay = collect([
                  trim((string) (optional($staff->employee)->department ?? '')),
                  trim((string) (optional(optional($staff->applicant)->position)->department ?? '')),
                  trim((string) ($staff->department ?? '')),
                ])->first(fn ($value) => $value !== '') ?? 'N/A';
                $hireDateDisplay = 'N/A';
                if (!empty($rawJoinDate)) {
                  try {
                    $hireDateDisplay = \Carbon\Carbon::parse($rawJoinDate)->format('m/d/Y');
                  } catch (\Throwable $e) {
                    $hireDateDisplay = trim((string) $rawJoinDate) !== '' ? trim((string) $rawJoinDate) : 'N/A';
                  }
                }
                $initials = collect([
                  mb_substr(trim((string) ($staff->first_name ?? '')), 0, 1),
                  mb_substr(trim((string) ($staff->last_name ?? '')), 0, 1),
                ])->filter()->implode('');
                $initials = $initials !== '' ? strtoupper($initials) : 'NA';
                $profilePhotoDocument = optional($staff->applicant)->documents
                  ?->first(function ($doc) {
                    return strtoupper(trim((string) ($doc->type ?? ''))) === 'PROFILE_PHOTO' && !empty($doc->filepath);
                  });
                if (!$profilePhotoDocument) {
                  $profilePhotoDocument = optional($staff->applicant)->documents
                    ?->first(function ($doc) {
                      $mime = strtolower(trim((string) ($doc->mime_type ?? '')));
                      $filename = strtolower(trim((string) ($doc->filename ?? '')));
                      return !empty($doc->filepath) && (str_starts_with($mime, 'image/') || preg_match('/\.(png|jpe?g|gif|webp)$/i', $filename));
                    });
                }
                $profilePhotoUrl = $profilePhotoDocument?->filepath ? asset('storage/'.$profilePhotoDocument->filepath) : null;
                $matrixEmployeeModalData = [
                  'employee_id' => $employeeIdDisplay,
                  'first_name' => trim((string) ($staff->first_name ?? '')) !== '' ? trim((string) ($staff->first_name ?? '')) : 'N/A',
                  'last_name' => trim((string) ($staff->last_name ?? '')) !== '' ? trim((string) ($staff->last_name ?? '')) : 'N/A',
                  'gender' => $genderDisplay,
                  'hired_date' => $hireDateDisplay,
                  'position' => $positionDisplay,
                  'department' => $departmentDisplay,
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
                  @if ($specialization->isNotEmpty())
                    {{ $specialization->implode(', ') }}
                  @else
                    -
                  @endif
                </td>
                <td class="border border-stone-300 px-3 py-3">{{ $employmentStatus !== '' ? $employmentStatus : '-' }}</td>
                <td class="border border-stone-300 px-3 py-3">{{ $salaryText }}</td>
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
                  No non-teaching employees found.
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

<script>
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

  function normalizeExcelCellText(cell) {
    return (cell.innerText || cell.textContent || '')
      .replace(/\r\n/g, '\n')
      .replace(/\n{3,}/g, '\n\n')
      .trim();
  }

  function buildMatrixExcelXml(sourceTable, worksheetName, titleText, widths) {
    const rows = [];
    rows.push(`
      <Row ss:AutoFitHeight="0" ss:Height="28">
        <Cell ss:MergeAcross="${widths.length - 1}" ss:StyleID="title">
          <Data ss:Type="String">${escapeExcelXml(titleText)}</Data>
        </Cell>
      </Row>
    `);

    const headerRow = sourceTable.querySelector('thead tr');
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
        const styleId = cellIndex === 0
          ? 'name'
          : (rowIndex % 2 === 0 ? 'bodyAlt' : 'body');
        return `
          <Cell ss:StyleID="${styleId}">
            <Data ss:Type="String">${escapeExcelXml(normalizeExcelCellText(cell))}</Data>
          </Cell>
        `;
      }).join('');

      rows.push(`<Row ss:AutoFitHeight="1">${cells}</Row>`);
    });

    const columns = widths.map((width) => {
      const numericWidth = parseInt(width, 10) || 120;
      return `<Column ss:AutoFitWidth="0" ss:Width="${numericWidth}"/>`;
    }).join('');

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
      '</Styles>',
      `<Worksheet ss:Name="${escapeExcelXml(worksheetName).slice(0, 31)}">`,
      '<Table>',
      columns,
      rows.join(''),
      '</Table>',
      '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel"><DisplayGridlines/><FreezePanes/><FrozenNoSplit/><SplitHorizontal>2</SplitHorizontal><TopRowBottomPane>2</TopRowBottomPane></WorksheetOptions>',
      '</Worksheet>',
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
    wordTitleCell.textContent = '15. Matrix list of academic non-teaching personnel including (Registrar, Librarian, Guidance Counselor, Researcher, etc.)';
    wordTitleCell.style.border = '1px solid #000';
    wordTitleCell.style.padding = '6pt 5pt';
    wordTitleCell.style.textAlign = 'left';
    wordTitleCell.style.fontWeight = '700';
    wordTitleRow.appendChild(wordTitleCell);
    const exportWidths = ['12%', '30%', '14%', '10%', '11%', '10%', '13%'];
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


  function downloadMatrixExcel(tableId, title) {
    const table = document.getElementById(tableId);
    if (!table) {
      return;
    }

    const exportWidths = ['260px', '360px', '170px', '145px', '165px', '170px', '205px'];
    const xml = buildMatrixExcelXml(
      table,
      title,
      '15. Matrix list of academic non-teaching personnel including (Registrar, Librarian, Guidance Counselor, Researcher, etc.)',
      exportWidths
    );

    downloadMatrixFile(xml, `${tableId}.xls`, 'application/vnd.ms-excel');
  }
</script>

</body>
</html>
