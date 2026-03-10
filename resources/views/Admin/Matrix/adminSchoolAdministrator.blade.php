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
              onclick="downloadMatrixExcel('school-administrator-matrix', 'School Administrators Matrix')"
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
      <div class="overflow-x-auto rounded-2xl border border-stone-300 bg-white shadow-sm">
        <table id="school-administrator-matrix" class="min-w-[1300px] w-full text-sm text-stone-800 border-collapse">
          <thead class="bg-stone-100">
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

  function downloadMatrixExcel(tableId, title) {
    const table = document.getElementById(tableId);
    if (!table) {
      return;
    }

    const exportTable = table.cloneNode(true);
    const excelTitleRow = exportTable.createTHead().insertRow(0);
    const excelTitleCell = document.createElement('th');
    excelTitleCell.colSpan = 7;
    excelTitleCell.textContent = '14. Matrix list of school administrators, i.e., President, Vice-President(s), Deans, Department Heads, etc, including:';
    excelTitleRow.appendChild(excelTitleCell);
    const exportWidths = ['13%', '32%', '10%', '10%', '10%', '10%', '15%'];
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



