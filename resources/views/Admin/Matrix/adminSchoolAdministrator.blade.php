<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>School Administrators Matrix</title>
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
            <p class="text-xs uppercase tracking-[0.2em] text-stone-600">Matrix 14</p>
            <h1 class="mt-1 text-xl md:text-2xl font-semibold text-stone-900">
              Matrix List of School Administrators
            </h1>
            <p class="mt-1 text-sm text-stone-600">
              President, Vice-President(s), Deans, and Department Heads
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
      <div class="overflow-x-auto rounded-2xl border border-stone-300 bg-white shadow-sm">
        <table class="min-w-[1300px] w-full text-sm text-stone-800 border-collapse">
          <thead class="bg-stone-100">
            <tr>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[220px]">Name of Dean/Program Head</th>
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
                $salaryValue = trim((string) (optional($admin->salary)->salary ?? ''));
                $benefits = collect([
                  trim((string) (optional($admin->applicant)->benefit ?? '')),
                  trim((string) (optional(optional($admin->applicant)->position)->benifits ?? '')),
                ])->filter()->values();

                $workPosition = trim((string) (optional($admin->applicant)->work_position ?? ''));
                $workDuration = trim((string) (optional($admin->applicant)->work_duration ?? ''));
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

</body>
</html>



