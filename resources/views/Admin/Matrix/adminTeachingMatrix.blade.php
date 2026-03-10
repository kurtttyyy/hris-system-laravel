<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Academic Teaching Matrix</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    @media (min-width: 1024px) {
      .matrix-admin-shell > aside:hover + main {
        margin-left: 18rem;
      }
    }

    @media print {
      .matrix-export-actions {
        display: none !important;
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
            <p class="text-xs uppercase tracking-[0.2em] text-stone-600">Matrix 16</p>
            <h1 class="mt-1 text-xl md:text-2xl font-semibold text-stone-900">
              Matrix List of Academic Teaching Personnel
            </h1>
            <p class="mt-1 text-sm text-stone-600">
              Academic teaching staff and subject load assignments
            </p>
          </div>
          <div class="matrix-export-actions flex flex-wrap items-center gap-2">
            <button
              type="button"
              onclick="downloadMatrixWord('teaching-matrix', 'Academic Teaching Matrix')"
              class="inline-flex items-center gap-2 rounded-lg border border-blue-300 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100"
            >
              <i class="fa-solid fa-file-word"></i>
              Word
            </button>
            <button
              type="button"
              onclick="downloadMatrixExcel('teaching-matrix', 'Academic Teaching Matrix')"
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

    <section class="px-4 md:px-8 pb-10">
      @php
        $rows = collect($teachingEmployees ?? ($administrators ?? []));
      @endphp
      <div class="overflow-x-auto rounded-2xl border border-stone-300 bg-white shadow-sm">
        <table id="teaching-matrix" class="min-w-[1700px] w-full text-sm text-stone-800 border-collapse">
          <thead class="bg-stone-100">
            <tr>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[20%]">Names of Teaching</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[330px]">Educational Qualifications</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[220px]">Feild/s of Specialization</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[220px]">Subject assignments/loads</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[150px]">Status of Employement</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[160px]">Number of teaching/Contact hours per week</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[190px]">Rate of salary per hour/month</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[220px]">Other Employement benefits</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[320px]">Relevant Experience/s</th>
            </tr>
          </thead>
          <tbody class="align-top">
            @forelse ($rows as $staff)
              @php
                $normalize = function ($value) {
                  return strtolower(trim((string) ($value ?? '')));
                };
                $isPlaceholder = function ($value) use ($normalize): bool {
                  $normalized = $normalize($value);
                  return $normalized === '' || $normalized === 'n/a' || $normalized === 'na' || $normalized === '-';
                };

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

                $subjectLoads = collect([
                  trim((string) (optional(optional($staff->applicant)->position)->responsibilities ?? '')),
                  trim((string) (optional(optional($staff->applicant)->position)->requirements ?? '')),
                ])->filter()->values();

                $jobTypeRaw = optional($staff->employee)->job_type ?: optional(optional($staff->applicant)->position)->job_type;
                $jobType = $normalize($jobTypeRaw);
                $rawJoinDate = optional($staff->applicant)->date_hired ?? optional($staff->employee)->employement_date;
                $employmentStatus = 'N/A';
                if (in_array($jobType, ['teaching', 'teacher', 'faculty'], true)) {
                  $employmentStatus = 'Probationary';
                  if (!empty($rawJoinDate)) {
                    try {
                      $joinDate = \Carbon\Carbon::parse($rawJoinDate);
                      $threshold = $joinDate->copy()->addYears(3);
                      $employmentStatus = now()->lt($threshold) ? 'Probationary' : 'Permanent';
                    } catch (\Throwable $e) {
                      $employmentStatus = 'Probationary';
                    }
                  }
                }

                $teachingCount = collect([
                  trim((string) (optional($staff->employee)->classification ?? '')),
                  trim((string) (optional($staff->employee)->job_type ?? '')),
                  trim((string) (optional(optional($staff->applicant)->position)->employment ?? '')),
                ])->filter(fn ($value) => !$isPlaceholder($value))->first() ?? '-';

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

                $workPositionRaw = trim((string) (optional($staff->applicant)->work_position ?? ''));
                $workDurationRaw = trim((string) (optional($staff->applicant)->work_duration ?? ''));
                $workPosition = $isPlaceholder($workPositionRaw)
                  ? (collect([
                      trim((string) (optional($staff->employee)->position ?? '')),
                      trim((string) (optional(optional($staff->applicant)->position)->title ?? '')),
                      trim((string) ($staff->position ?? '')),
                    ])->first(fn ($value) => !$isPlaceholder($value)) ?? '')
                  : $workPositionRaw;

                $workDuration = $isPlaceholder($workDurationRaw) ? '' : $workDurationRaw;
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
              @endphp
              <tr class="odd:bg-white even:bg-stone-50/40">
                <td class="border border-stone-300 px-3 py-3 font-medium">{{ $fullName !== '' ? $fullName : '-' }}</td>
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
                <td class="border border-stone-300 px-3 py-3">
                  @if ($subjectLoads->isNotEmpty())
                    {{ $subjectLoads->implode(' | ') }}
                  @else
                    -
                  @endif
                </td>
                <td class="border border-stone-300 px-3 py-3">{{ $employmentStatus !== '' ? $employmentStatus : '-' }}</td>
                <td class="border border-stone-300 px-3 py-3">{{ $teachingCount }}</td>
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
                <td colspan="9" class="border border-stone-300 px-3 py-6 text-center text-stone-500">
                  No teaching employees found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>

<script>
  function createExportColGroup(widths) {
    const colGroup = document.createElement('colgroup');
    widths.forEach((width) => {
      const col = document.createElement('col');
      col.style.width = width;
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
      });
    });
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

  function downloadMatrixWord(tableId, title) {
    const table = document.getElementById(tableId);
    if (!table) {
      return;
    }

    const wordTable = table.cloneNode(true);
    const wordTitleRow = wordTable.createTHead().insertRow(0);
    const wordTitleCell = document.createElement('th');
    wordTitleCell.colSpan = 9;
    wordTitleCell.textContent = '16. Matrix list of academic teaching personnel including subject assignments/loads and contact hours per week';
    wordTitleCell.style.border = '1px solid #000';
    wordTitleCell.style.padding = '6pt 5pt';
    wordTitleCell.style.textAlign = 'left';
    wordTitleCell.style.fontWeight = '700';
    wordTitleRow.appendChild(wordTitleCell);
    const exportWidths = ['12%', '23%', '8%', '10%', '7%', '8%', '9%', '8%', '15%'];
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

    const exportTable = table.cloneNode(true);
    const excelTitleRow = exportTable.createTHead().insertRow(0);
    const excelTitleCell = document.createElement('th');
    excelTitleCell.colSpan = 9;
    excelTitleCell.textContent = '16. Matrix list of academic teaching personnel including subject assignments/loads and contact hours per week';
    excelTitleRow.appendChild(excelTitleCell);
    const exportWidths = ['12%', '23%', '8%', '10%', '7%', '8%', '9%', '8%', '15%'];
    exportTable.insertBefore(createExportColGroup(exportWidths), exportTable.firstChild);
    applyExportColumnWidths(exportTable, exportWidths);

    const html = `
      <html xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:x="urn:schemas-microsoft-com:office:excel"
            xmlns="http://www.w3.org/TR/REC-html40">
      <head>
        <meta charset="utf-8">
        <title>${title}</title>
      </head>
      <body>
        <table>${exportTable.innerHTML}</table>
      </body>
      </html>
    `;

    downloadMatrixFile(html, `${tableId}.xls`, 'application/vnd.ms-excel');
  }
</script>

</body>
</html>
