<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Academic Non-Teaching Matrix</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    @media (min-width: 1024px) {
      .matrix-admin-shell > aside:hover + main {
        margin-left: 18rem;
      }
    }
  </style>
</head>
<body class="bg-gradient-to-br from-amber-50 via-stone-100 to-zinc-200">

<div class="matrix-admin-shell flex min-h-screen">
  @include('components.adminSideBar')

  <main class="flex-1 ml-16 transition-all duration-300">
    <section class="px-4 md:px-8 pt-8 pb-6">
      <div class="rounded-2xl border border-stone-300 bg-white/80 backdrop-blur-sm shadow-sm p-5 md:p-7">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <p class="text-xs uppercase tracking-[0.2em] text-stone-600">Matrix 15</p>
            <h1 class="mt-1 text-xl md:text-2xl font-semibold text-stone-900">
              Matrix List of Academic Non-Teaching Personnel
            </h1>
            <p class="mt-1 text-sm text-stone-600">
                Academic Non-Teaching personnel including (Registrar, Librarian, Guidance Counselor, Researcher, etc.)
            </p>
          </div>
          <button
            type="button"
            onclick="window.print()"
            class="inline-flex items-center gap-2 rounded-lg border border-stone-300 bg-stone-50 px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-100"
          >
            <i class="fa-solid fa-print"></i>
            Print
          </button>
        </div>
      </div>
    </section>

    <section class="px-4 md:px-8 pb-10">
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
      <div class="overflow-x-auto rounded-2xl border border-stone-300 bg-white shadow-sm">
        <table class="min-w-[1320px] w-full text-sm text-stone-800 border-collapse">
          <thead class="bg-stone-100">
            <tr>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[220px]">Names of Non-Teaching</th>
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
              @endphp
              <tr class="odd:bg-white even:bg-stone-50/40">
                <td class="border border-stone-300 px-3 py-3 font-medium">{{ $fullName !== '' ? $fullName : '-' }}</td>
                <td class="border border-stone-300 px-3 py-3">
                  @if ($degreeRows->isNotEmpty())
                    <ul class="list-disc pl-5 space-y-1">
                      @foreach ($degreeRows as $row)
                        <li>
                          <strong>{{ trim((string) ($row->degree_name ?? '-')) }}</strong>
                          @if (trim((string) ($row->school_name ?? '')) !== '')
                            , <span class="italic">{{ trim((string) $row->school_name) }}</span>
                          @endif
                          @if (trim((string) ($row->year_finished ?? '')) !== '')
                            , {{ trim((string) $row->year_finished) }}
                          @endif
                        </li>
                      @endforeach
                    </ul>
                  @elseif ($fallbackDegrees->isNotEmpty())
                    <ul class="list-disc pl-5 space-y-1">
                      @foreach ($fallbackDegrees as $row)
                        <li>
                          <strong>{{ $row['degree_name'] }}</strong>
                          @if ($row['school_name'] !== '')
                            , <span class="italic">{{ $row['school_name'] }}</span>
                          @endif
                          @if ($row['year_finished'] !== '')
                            , {{ $row['year_finished'] }}
                          @endif
                        </li>
                      @endforeach
                    </ul>
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
                  @if ($workPosition !== '' || $displayWorkDuration !== '')
                    {{ $workPosition !== '' ? $workPosition : '-' }}{{ $displayWorkDuration !== '' ? ' - ('.$displayWorkDuration.')' : '' }}
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

</body>
</html>
