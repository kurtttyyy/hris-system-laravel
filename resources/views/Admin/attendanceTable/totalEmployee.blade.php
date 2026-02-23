<div class="bg-white rounded-xl border border-gray-200 p-4">
  <div class="mb-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h3 class="text-sm font-semibold text-gray-700">All Employees Attendance</h3>
    <div class="flex items-center gap-2">
      <button
        type="button"
        id="view_total_employee_summary"
        class="inline-flex items-center rounded-lg bg-slate-700 px-3 py-2 text-xs font-semibold text-white transition hover:bg-slate-800"
      >
        <i class="fa-solid fa-chart-pie mr-2"></i>Summary
      </button>
      <button
        type="button"
        id="view_total_employee_chart"
        class="hidden inline-flex items-center rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-700"
      >
        <i class="fa-solid fa-chart-column mr-2"></i>Chart
      </button>
      <button
        type="button"
        id="export_total_employee_excel"
        class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700"
      >
        <i class="fa-solid fa-file-excel mr-2"></i>Export Excel
      </button>
      <button
        type="button"
        id="export_total_employee_pdf"
        class="inline-flex items-center rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-red-700"
      >
        <i class="fa-solid fa-file-pdf mr-2"></i>Export PDF
      </button>
    </div>
  </div>
  <div class="overflow-x-auto">
    <table id="total_employee_table" class="min-w-full text-sm">
      <thead class="bg-slate-100 text-slate-700">
        <tr>
          <th class="px-3 py-2 text-left">Employee ID</th>
          <th class="px-3 py-2 text-left">Name</th>
          <th class="px-3 py-2 text-left">Gate</th>
          <th class="px-3 py-2 text-left">Date</th>
          <th class="px-3 py-2 text-left">AM In</th>
          <th class="px-3 py-2 text-left">AM Out</th>
          <th class="px-3 py-2 text-left">PM In</th>
          <th class="px-3 py-2 text-left">PM Out</th>
          <th class="px-3 py-2 text-left">Late Duration</th>
          <th class="px-3 py-2 text-left">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($rows as $row)
          @php
            $lateMinutes = (int) ($row->late_minutes ?? 0);
            $lateHours = intdiv($lateMinutes, 60);
            $remainingMinutes = $lateMinutes % 60;
            $hourText = $lateHours === 1 ? 'hour' : 'hours';
            $minuteText = $remainingMinutes === 1 ? 'minute' : 'minutes';
            $hasAnyTimeLog = !empty($row->morning_in) || !empty($row->morning_out) || !empty($row->afternoon_in) || !empty($row->afternoon_out);

            $statusText = 'Present';
            $statusClass = 'bg-green-100 text-green-700';
            $summaryStatus = 'present';
            if (!$hasAnyTimeLog && !empty($row->is_absent)) {
              $statusText = 'Absent';
              $statusClass = 'bg-red-100 text-red-700';
              $summaryStatus = 'absent';
            } elseif ($lateMinutes > 0) {
              // Keep table status as Present even when late.
              $summaryStatus = 'tardy';
            }

            $attendanceDateText = optional($row->attendance_date)->format('Y-m-d') ?? '-';
            $missingLogs = $row->missing_time_logs ?? [];
            if (is_string($missingLogs)) {
              $decoded = json_decode($missingLogs, true);
              $missingLogs = is_array($decoded) ? $decoded : [];
            }
            if (!is_array($missingLogs)) {
              $missingLogs = [];
            }
            $missingLogs = array_values(array_filter(array_map(function ($item) {
              $label = strtolower(trim((string) $item));
              if ($label === '') {
                return null;
              }

              $codeMap = [
                'morning_in' => 'NTI',
                'morning_out' => 'NTO',
                'afternoon_in' => 'NBI',
                'afternoon_out' => 'NBO',
              ];

              return $codeMap[$label] ?? strtoupper(str_replace('_', ' ', $label));
            }, $missingLogs)));
            $missingLogsForData = implode('|', $missingLogs);
          @endphp
          <tr
            class="border-b border-slate-100"
            data-employee-id="{{ $row->employee_id }}"
            data-employee-name="{{ $row->employee_name ?? '-' }}"
            data-department="{{ $row->department ?? '-' }}"
            data-attendance-date="{{ $attendanceDateText }}"
            data-status="{{ $summaryStatus }}"
            data-late-minutes="{{ $lateMinutes }}"
            data-missing-logs="{{ $missingLogsForData }}"
          >
            <td class="px-3 py-2">{{ $row->employee_id }}</td>
            <td class="px-3 py-2">{{ $row->employee_name ?? '-' }}</td>
            <td class="px-3 py-2">{{ $row->main_gate ?? '-' }}</td>
            <td class="px-3 py-2">{{ $attendanceDateText }}</td>
            <td class="px-3 py-2">{{ $row->morning_in ? \Carbon\Carbon::parse($row->morning_in)->format('h:i A') : '-' }}</td>
            <td class="px-3 py-2">{{ $row->morning_out ? \Carbon\Carbon::parse($row->morning_out)->format('h:i A') : '-' }}</td>
            <td class="px-3 py-2">{{ $row->afternoon_in ? \Carbon\Carbon::parse($row->afternoon_in)->format('h:i A') : '-' }}</td>
            <td class="px-3 py-2">{{ $row->afternoon_out ? \Carbon\Carbon::parse($row->afternoon_out)->format('h:i A') : '-' }}</td>
            <td class="px-3 py-2">
              @if ($lateMinutes <= 0)
                -
              @elseif ($lateHours > 0 && $remainingMinutes > 0)
                {{ $lateHours }} {{ $hourText }} {{ $remainingMinutes }} {{ $minuteText }} late
              @elseif ($lateHours > 0)
                {{ $lateHours }} {{ $hourText }} late
              @else
                {{ $remainingMinutes }} {{ $minuteText }} late
              @endif
            </td>
            <td class="px-3 py-2">
              <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                {{ $statusText }}
              </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="px-3 py-4 text-center text-gray-500">No attendance records found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div id="total_employee_chart_container" class="hidden mt-4 space-y-3"></div>
</div>

<script>
  (function () {
    const table = document.getElementById('total_employee_table');
    const summaryBtn = document.getElementById('view_total_employee_summary');
    const chartBtn = document.getElementById('view_total_employee_chart');
    const chartContainer = document.getElementById('total_employee_chart_container');
    const excelBtn = document.getElementById('export_total_employee_excel');
    const pdfBtn = document.getElementById('export_total_employee_pdf');
    const tableWrapper = table ? table.closest('.overflow-x-auto') : null;

    if (!table) {
      return;
    }

    if (excelBtn) {
      excelBtn.addEventListener('click', function () {
        const rows = Array.from(table.querySelectorAll('tr'));
        const csv = rows
          .map((row) => {
            const cells = Array.from(row.querySelectorAll('th, td'));
            return cells
              .map((cell) => {
                const text = (cell.innerText || '').replace(/\r?\n|\r/g, ' ').trim();
                const escaped = text.replace(/"/g, '""');
                return `"${escaped}"`;
              })
              .join(',');
          })
          .join('\n');

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'total_employee_attendance.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
      });
    }

    const summaryFromDate = @json($fromDate ?? null);
    const summaryToDate = @json($toDate ?? null);
    const originalTheadHtml = table.querySelector('thead')?.innerHTML || '';
    const originalTbodyHtml = table.querySelector('tbody')?.innerHTML || '';
    const rawSourceRows = Array.from(table.querySelectorAll('tbody tr')).filter((row) => row.querySelectorAll('td').length > 1);
    let isSummaryView = false;
    let isChartView = false;
    let activeChartMetric = 'tardiness';

    function formatDateLabel(dateStr) {
      const dateObj = new Date(`${dateStr}T00:00:00`);
      const weekday = dateObj.toLocaleDateString('en-US', { weekday: 'short' });
      const month = dateObj.getMonth() + 1;
      const day = dateObj.getDate();
      const year = dateObj.getFullYear();
      return `${weekday}(${month}.${day}.${year})`;
    }

    function normalizeEmployeeId(value) {
      const raw = String(value || '').trim();
      if (!raw) {
        return '';
      }

      // Normalize spaces and casing for non-numeric IDs.
      const compact = raw.replace(/\s+/g, '');

      // Excel often exports numeric IDs like 123.0 / 123.00.
      const excelStyleMatch = compact.match(/^(\d+)\.0+$/);
      const normalized = excelStyleMatch ? excelStyleMatch[1] : compact;

      // If purely numeric, collapse leading zeros so 00123 and 123 count as one ID.
      if (/^\d+$/.test(normalized)) {
        return String(parseInt(normalized, 10));
      }

      return normalized.toUpperCase();
    }

    function buildDateRange() {
      if (summaryFromDate && summaryToDate) {
        const start = new Date(`${summaryFromDate}T00:00:00`);
        const end = new Date(`${summaryToDate}T00:00:00`);
        const min = start <= end ? start : end;
        const max = start <= end ? end : start;
        const dates = [];
        const cursor = new Date(min);
        while (cursor <= max) {
          const y = cursor.getFullYear();
          const m = String(cursor.getMonth() + 1).padStart(2, '0');
          const d = String(cursor.getDate()).padStart(2, '0');
          dates.push(`${y}-${m}-${d}`);
          cursor.setDate(cursor.getDate() + 1);
        }
        return dates;
      }

      if (summaryFromDate) {
        return [summaryFromDate];
      }

      if (summaryToDate) {
        return [summaryToDate];
      }

      const found = new Set();
      Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
        const date = row.getAttribute('data-attendance-date');
        if (date && date !== '-') {
          found.add(date);
        }
      });
      return Array.from(found).sort();
    }

    function renderSummaryTable() {
      const dateRange = buildDateRange();
      const sourceRows = rawSourceRows;
      const employeeMap = new Map();

      sourceRows.forEach((row) => {
        const employeeId = normalizeEmployeeId(row.getAttribute('data-employee-id'));
        const employeeName = (row.getAttribute('data-employee-name') || '-').trim();
        const department = (row.getAttribute('data-department') || '-').trim();
        const attendanceDate = (row.getAttribute('data-attendance-date') || '').trim();
        const status = (row.getAttribute('data-status') || '').trim();
        const missingLogsRaw = (row.getAttribute('data-missing-logs') || '').trim();
        const missingLogs = missingLogsRaw ? missingLogsRaw.split('|').map((value) => value.trim()).filter(Boolean) : [];
        const hasMissingLogs = missingLogs.length > 0;

        if (!employeeId) return;

        if (!employeeMap.has(employeeId)) {
          employeeMap.set(employeeId, {
            employeeId,
            employeeName,
            department,
            byDate: {},
            absent: 0,
            tardy: 0,
            totalLateMinutes: 0,
          });
        }

        const record = employeeMap.get(employeeId);
        const lateMinutes = parseInt(row.getAttribute('data-late-minutes') || '0', 10) || 0;
        if (attendanceDate) {
          const currentDay = record.byDate[attendanceDate] || { status: '', lateMinutes: 0, hasMissingLogs: false, missingLogs: [] };
          const statusRank = { '': 0, present: 1, absent: 2, tardy: 3 };
          const mergedStatus = (statusRank[status] || 0) >= (statusRank[currentDay.status] || 0)
            ? status
            : currentDay.status;
          const mergedMissingLogs = Array.from(new Set([...(currentDay.missingLogs || []), ...missingLogs]));

          record.byDate[attendanceDate] = {
            status: mergedStatus,
            lateMinutes: Math.max(currentDay.lateMinutes || 0, lateMinutes),
            hasMissingLogs: currentDay.hasMissingLogs || hasMissingLogs,
            missingLogs: mergedMissingLogs,
          };
        }
      });

      Array.from(employeeMap.values()).forEach((emp) => {
        const dayValues = Object.values(emp.byDate);
        emp.absent = dayValues.filter((day) => day.status === 'absent').length;
        emp.tardy = dayValues.filter((day) => day.status === 'tardy').length;
        emp.totalLateMinutes = dayValues.reduce((sum, day) => sum + (day.lateMinutes || 0), 0);
      });

      const departmentAbsenceTotals = {};
      Array.from(employeeMap.values()).forEach((emp) => {
        const dept = emp.department || '-';
        departmentAbsenceTotals[dept] = (departmentAbsenceTotals[dept] || 0) + emp.absent;
      });

      const headers = [
        'No.',
        'Employee Name',
        'Department',
        ...dateRange.map((date) => formatDateLabel(date)),
        'Total Tardiness',
        'Total Absence',
        'Total Absence Department',
      ];

      const theadHtml = `<tr>${headers.map((h) => `<th class="px-3 py-2 text-left">${h}</th>`).join('')}</tr>`;
      table.querySelector('thead').innerHTML = theadHtml;

      const employees = Array.from(employeeMap.values()).sort((a, b) => {
        const deptCompare = (a.department || '-').localeCompare(b.department || '-');
        if (deptCompare !== 0) return deptCompare;
        return (a.employeeName || '-').localeCompare(b.employeeName || '-');
      });
      if (!employees.length) {
        table.querySelector('tbody').innerHTML = `<tr><td colspan="${headers.length}" class="px-3 py-4 text-center text-gray-500">No attendance records found.</td></tr>`;
        return;
      }

      const totalDays = Math.max(dateRange.length, 1);
      const departmentRowspans = {};
      employees.forEach((emp) => {
        const dept = emp.department || '-';
        departmentRowspans[dept] = (departmentRowspans[dept] || 0) + 1;
      });
      const renderedDepartmentCell = {};

        const bodyHtml = employees.map((emp, index) => {
          const dateCells = dateRange.map((date) => {
          const day = emp.byDate[date] || { status: '', lateMinutes: 0, hasMissingLogs: false, missingLogs: [] };
          const status = day.status;
          const missingLogsText = (day.missingLogs || []).join('/');
          if (status === 'present' && day.hasMissingLogs) return `<td class="px-3 py-2">${missingLogsText || 'Missing Logs'}</td>`;
          if (status === 'present') return `<td class="px-3 py-2">-</td>`;
          if (status === 'tardy') {
            const tardyPercent = ((day.lateMinutes || 0) / 100).toFixed(2);
            if (day.hasMissingLogs) {
              return `<td class="px-3 py-2">${tardyPercent}/${missingLogsText || 'Missing Logs'}</td>`;
            }
            return `<td class="px-3 py-2">${tardyPercent}</td>`;
          }
          if (status === 'absent') return `<td class="px-3 py-2">A</td>`;
          if (day.hasMissingLogs) return `<td class="px-3 py-2">${missingLogsText || 'Missing Logs'}</td>`;
          return `<td class="px-3 py-2">-</td>`;
        }).join('');

        const tardyDecimal = (emp.totalLateMinutes / 100).toFixed(2);
        const hasAttendanceIssue = emp.absent > 0 || emp.totalLateMinutes > 0;
        const tardinessDisplay = hasAttendanceIssue ? tardyDecimal : '-';
        const absenceDisplay = hasAttendanceIssue ? emp.absent : '-';
        const deptKey = emp.department || '-';
        const deptAbs = departmentAbsenceTotals[deptKey] || 0;
        let departmentAbsenceCell = '';
        if (!renderedDepartmentCell[deptKey]) {
          renderedDepartmentCell[deptKey] = true;
          departmentAbsenceCell = `<td class="px-3 py-2 align-middle text-center" rowspan="${departmentRowspans[deptKey]}">${deptAbs}</td>`;
        }

        return `
          <tr class="border-b border-slate-100">
            <td class="px-3 py-2">${index + 1}</td>
            <td class="px-3 py-2">${emp.employeeName || '-'}</td>
            <td class="px-3 py-2">${emp.department || '-'}</td>
            ${dateCells}
            <td class="px-3 py-2">${tardinessDisplay}</td>
            <td class="px-3 py-2">${absenceDisplay}</td>
            ${departmentAbsenceCell}
          </tr>
        `;
      }).join('');

      table.querySelector('tbody').innerHTML = bodyHtml;

      return {
        employees,
        dateRange,
      };
    }

    function renderSummaryChart() {
      if (!chartContainer) {
        return false;
      }

      const summaryData = renderSummaryTable();
      const employees = (summaryData?.employees || []);
      if (!employees.length) {
        chartContainer.classList.add('hidden');
        chartContainer.innerHTML = '';
        return false;
      }

      const tardinessByDepartment = {};
      const employeeCountByDepartment = {};
      const absencesByDepartment = {};
      const absentEmployeeCountByDepartment = {};
      employees.forEach((emp) => {
        const department = (emp.department || '-').trim() || '-';
        const tardiness = Number(emp.totalLateMinutes || 0);
        const absences = Number(emp.absent || 0);
        tardinessByDepartment[department] = (tardinessByDepartment[department] || 0) + tardiness;
        employeeCountByDepartment[department] = (employeeCountByDepartment[department] || 0) + 1;
        absencesByDepartment[department] = (absencesByDepartment[department] || 0) + absences;
        if (absences > 0) {
          absentEmployeeCountByDepartment[department] = (absentEmployeeCountByDepartment[department] || 0) + 1;
        }
      });

      const totalLateMinutes = Object.values(tardinessByDepartment).reduce((sum, value) => sum + Number(value || 0), 0);
      const totalEmployees = new Set(
        employees
          .map((emp) => normalizeEmployeeId(emp.employeeId))
          .filter(Boolean)
      ).size;
      const totalAbsences = employees.reduce((sum, emp) => sum + (emp.absent || 0), 0);
      const totalDaysInRange = Math.max((summaryData?.dateRange || []).length, 1);
      const totalClassDaysByDepartment = {};
      const absenceRateByDepartment = {};
      Object.keys(employeeCountByDepartment).forEach((department) => {
        const employeeCount = Number(employeeCountByDepartment[department] || 0);
        const classDays = employeeCount * totalDaysInRange;
        const absences = Number(absencesByDepartment[department] || 0);
        totalClassDaysByDepartment[department] = classDays;
        absenceRateByDepartment[department] = classDays > 0
          ? Number(((absences / classDays) * 100).toFixed(2))
          : 0;
      });
      const totalPossibleClassDays = Object.values(totalClassDaysByDepartment)
        .reduce((sum, value) => sum + Number(value || 0), 0);
      const overallAbsenceRate = totalPossibleClassDays > 0
        ? Number(((totalAbsences / totalPossibleClassDays) * 100).toFixed(2))
        : 0;
      const totalAbsentEmployees = Object.values(absentEmployeeCountByDepartment)
        .reduce((sum, value) => sum + Number(value || 0), 0);
      const minutesPerClassDay = 9 * 60;
      const tardinessRateByDepartment = {};
      Object.keys(employeeCountByDepartment).forEach((department) => {
        const lateMinutes = Number(tardinessByDepartment[department] || 0);
        const classDays = Number(totalClassDaysByDepartment[department] || 0);
        const possibleMinutes = classDays * minutesPerClassDay;
        tardinessRateByDepartment[department] = possibleMinutes > 0
          ? Number(((lateMinutes / possibleMinutes) * 100).toFixed(2))
          : 0;
      });
      const totalPossibleLateMinutes = totalPossibleClassDays * minutesPerClassDay;
      const overallTardinessRate = totalPossibleLateMinutes > 0
        ? Number(((totalLateMinutes / totalPossibleLateMinutes) * 100).toFixed(2))
        : 0;
      const outstandingScoreByDepartment = {};
      Object.keys(employeeCountByDepartment).forEach((department) => {
        const absenceRate = Number(absenceRateByDepartment[department] || 0);
        const tardinessRate = Number(tardinessRateByDepartment[department] || 0);
        // Rule: Outstanding % = 100 - (Absence % + Tardiness %)
        const score = Math.max(0, Math.min(100, 100 - (absenceRate + tardinessRate)));
        outstandingScoreByDepartment[department] = Number(score.toFixed(2));
      });
      const departmentScoreEntries = Object.entries(outstandingScoreByDepartment)
        .sort((a, b) => b[1] - a[1]);
      const outstandingDepartmentsEntries = Object.entries(outstandingScoreByDepartment)
        .filter(([, score]) => Number(score || 0) >= 95)
        .sort((a, b) => b[1] - a[1]);
      const totalOutstandingDepartments = outstandingDepartmentsEntries.length;
      const colors = [
        '#ef4444', '#f97316', '#eab308', '#22c55e', '#06b6d4',
        '#3b82f6', '#6366f1', '#a855f7', '#ec4899', '#64748b',
      ];

      const metricConfig = {
        absences: {
          title: 'Pie Chart: Absence Rate per Department',
          totalLabel: 'Overall Absence Rate',
          totalValue: totalAbsences,
          emptyText: 'No absence data to chart.',
          formatValue: (value) => `${Number(value || 0).toFixed(2)}%`,
          cardClasses: {
            base: 'rounded-xl border p-3 cursor-pointer transition',
            active: 'border-red-400 bg-red-100 ring-1 ring-red-300',
            inactive: 'border-red-200 bg-red-50 hover:bg-red-100',
          },
        },
        employees: {
          title: 'Pie Chart: Total Employees per Department',
          totalLabel: 'Total Employees',
          totalValue: totalEmployees,
          emptyText: 'No employee data to chart.',
          formatValue: (value) => `${Math.round(value)}`,
          cardClasses: {
            base: 'rounded-xl border p-3 cursor-pointer transition',
            active: 'border-blue-400 bg-blue-100 ring-1 ring-blue-300',
            inactive: 'border-blue-200 bg-blue-50 hover:bg-blue-100',
          },
        },
        outstanding: {
          title: 'Pie Chart: Outstanding Score by Department (100 - (Absence% + Tardiness%))',
          totalLabel: 'Outstanding Departments',
          totalValue: totalOutstandingDepartments,
          emptyText: 'No department score data to chart.',
          formatValue: (value) => `${Number(value || 0).toFixed(2)}%`,
          cardClasses: {
            base: 'rounded-xl border p-3 cursor-pointer transition',
            active: 'border-emerald-400 bg-emerald-100 ring-1 ring-emerald-300',
            inactive: 'border-emerald-200 bg-emerald-50 hover:bg-emerald-100',
          },
        },
        tardiness: {
          title: 'Pie Chart: Tardiness Rate per Department (Late Minutes vs 9 Hours/Class Day)',
          totalLabel: 'Overall Tardiness Rate',
          totalValue: totalLateMinutes,
          emptyText: 'No tardiness data to chart.',
          formatValue: (value) => `${Number(value || 0).toFixed(2)}%`,
          cardClasses: {
            base: 'rounded-xl border p-3 cursor-pointer transition',
            active: 'border-amber-400 bg-amber-100 ring-1 ring-amber-300',
            inactive: 'border-amber-200 bg-amber-50 hover:bg-amber-100',
          },
        },
      };

      function getEntriesByMetric(metric) {
        if (metric === 'absences') {
          return Object.entries(absenceRateByDepartment).sort((a, b) => b[1] - a[1]);
        }
        if (metric === 'employees') {
          return Object.entries(employeeCountByDepartment).sort((a, b) => b[1] - a[1]);
        }
        if (metric === 'outstanding') {
          return departmentScoreEntries;
        }
        return Object.entries(tardinessRateByDepartment).sort((a, b) => b[1] - a[1]);
      }

      function metricHasData(metric) {
        const selectedMetric = metricConfig[metric] ? metric : 'tardiness';
        const departmentEntries = getEntriesByMetric(selectedMetric);
        const totalValue = departmentEntries.reduce((sum, [, value]) => sum + Number(value || 0), 0);
        if (!departmentEntries.length) {
          return false;
        }

        return selectedMetric === 'outstanding' || totalValue > 0;
      }

      function renderMetricChart(metric) {
        const selectedMetric = metricConfig[metric] ? metric : 'tardiness';
        activeChartMetric = selectedMetric;
        const config = metricConfig[selectedMetric];
        const departmentEntries = getEntriesByMetric(selectedMetric);
        const totalValue = departmentEntries.reduce((sum, [, value]) => sum + Number(value || 0), 0);

        if (!metricHasData(selectedMetric)) {
          chartContainer.innerHTML = `<div class="rounded-lg border border-gray-200 p-4 text-sm text-gray-500">${config.emptyText}</div>`;
          return;
        }

        let currentDeg = 0;
        const gradientStops = departmentEntries.map(([, value], index) => {
          const percentage = Number(value || 0) / totalValue;
          const start = currentDeg;
          const end = currentDeg + (percentage * 360);
          currentDeg = end;
          const color = colors[index % colors.length];
          return `${color} ${start.toFixed(2)}deg ${end.toFixed(2)}deg`;
        });

        const pieStyle = `background: conic-gradient(${gradientStops.join(', ')});`;
        const legendHtml = departmentEntries.map(([department, value], index) => {
          const color = colors[index % colors.length];
          const numericValue = Number(value || 0);
          const percentage = totalValue > 0 ? ((numericValue / totalValue) * 100).toFixed(1) : '0.0';
          const employeeCount = employeeCountByDepartment[department] || 0;
          const departmentAbsentEmployees = Number(absentEmployeeCountByDepartment[department] || 0);
          const departmentClassDays = Number(totalClassDaysByDepartment[department] || 0);
          const departmentLateMinutes = Number(tardinessByDepartment[department] || 0);
          const departmentPossibleLateMinutes = departmentClassDays * minutesPerClassDay;
          const outstandingRemark = numericValue >= 96
            ? 'Excellent'
            : numericValue >= 86
              ? 'Very Good'
              : numericValue >= 75
                ? 'Good'
                : 'Needs Improvement';
          const outstandingRemarkClass = numericValue >= 96
            ? 'text-emerald-600'
            : numericValue >= 86
              ? 'text-blue-600'
              : numericValue >= 75
                ? 'text-amber-600'
                : 'text-slate-500';
          const extraLabel = selectedMetric === 'outstanding'
            ? `<div class="text-[11px] ${outstandingRemarkClass}">${outstandingRemark}</div>`
            : selectedMetric === 'absences'
              ? `<div class="text-[11px] text-slate-500">${departmentAbsentEmployees}/${Math.round(employeeCount)} employees</div>`
              : selectedMetric === 'tardiness'
                ? `<div class="text-[11px] text-slate-500">${Math.round(departmentLateMinutes)}/${Math.round(departmentPossibleLateMinutes)} late-minutes</div>`
              : `<div class="text-[11px] text-slate-500">Employees: ${employeeCount}</div>`;
          const valueLine = selectedMetric === 'absences' || selectedMetric === 'tardiness'
            ? `${config.formatValue(numericValue)}`
            : `${config.formatValue(numericValue)} (${percentage}%)`;
          return `
            <div class="flex items-center justify-between rounded-md border border-slate-200 bg-white px-3 py-2 text-xs">
              <div class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-sm" style="background:${color}"></span>
                <span class="font-medium text-slate-700">${department}</span>
              </div>
              <div class="text-right text-slate-600">
                <div>${valueLine}</div>
                ${extraLabel}
              </div>
            </div>
          `;
        }).join('');

        const absencesCardClass = selectedMetric === 'absences'
          ? `${metricConfig.absences.cardClasses.base} ${metricConfig.absences.cardClasses.active}`
          : `${metricConfig.absences.cardClasses.base} ${metricConfig.absences.cardClasses.inactive}`;
        const employeesCardClass = selectedMetric === 'employees'
          ? `${metricConfig.employees.cardClasses.base} ${metricConfig.employees.cardClasses.active}`
          : `${metricConfig.employees.cardClasses.base} ${metricConfig.employees.cardClasses.inactive}`;
        const tardinessCardClass = selectedMetric === 'tardiness'
          ? `${metricConfig.tardiness.cardClasses.base} ${metricConfig.tardiness.cardClasses.active}`
          : `${metricConfig.tardiness.cardClasses.base} ${metricConfig.tardiness.cardClasses.inactive}`;
        const outstandingCardClass = selectedMetric === 'outstanding'
          ? `${metricConfig.outstanding.cardClasses.base} ${metricConfig.outstanding.cardClasses.active}`
          : `${metricConfig.outstanding.cardClasses.base} ${metricConfig.outstanding.cardClasses.inactive}`;
        const footerLabel = selectedMetric === 'outstanding'
          ? 'Outstanding Departments (>=95%)'
          : selectedMetric === 'absences'
            ? 'Overall Absence Rate'
            : selectedMetric === 'tardiness'
              ? 'Overall Tardiness Rate'
            : config.totalLabel;
        const footerValue = selectedMetric === 'outstanding'
          ? `${outstandingDepartmentsEntries.length}`
          : selectedMetric === 'absences'
            ? `${overallAbsenceRate.toFixed(2)}% (${Math.round(totalAbsentEmployees)}/${Math.round(totalEmployees)} employees absent)`
            : selectedMetric === 'tardiness'
              ? `${overallTardinessRate.toFixed(2)}% (${Math.round(totalLateMinutes)}/${Math.round(totalPossibleLateMinutes)} late-minutes)`
          : config.formatValue(totalValue);

        chartContainer.innerHTML = `
          <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-3 text-xs text-indigo-700">
            ${config.title}
          </div>
          <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <button type="button" class="${absencesCardClass}" data-chart-metric="absences">
              <div class="text-[11px] font-semibold tracking-wide text-red-700">TOTAL ABSENCES</div>
              <div class="mt-1 text-xl font-bold text-red-800">${Math.round(totalAbsences)}</div>
              <div class="text-[11px] text-red-700">${overallAbsenceRate.toFixed(2)}%</div>
            </button>
            <button type="button" class="${employeesCardClass}" data-chart-metric="employees">
              <div class="text-[11px] font-semibold tracking-wide text-blue-700">TOTAL EMPLOYEES</div>
              <div class="mt-1 text-xl font-bold text-blue-800">${Math.round(totalEmployees)}</div>
            </button>
            <button type="button" class="${outstandingCardClass}" data-chart-metric="outstanding">
              <div class="text-[11px] font-semibold tracking-wide text-emerald-700">OUTSTANDING ATTENDANCE DEPARTMENTS</div>
              <div class="mt-1 text-xl font-bold text-emerald-800">${Math.round(totalOutstandingDepartments)}</div>
            </button>
            <button type="button" class="${tardinessCardClass}" data-chart-metric="tardiness">
              <div class="text-[11px] font-semibold tracking-wide text-amber-700">TOTAL TARDINESS</div>
              <div class="mt-1 text-xl font-bold text-amber-800">${Math.round(totalLateMinutes)}</div>
              <div class="text-[11px] text-amber-700">${overallTardinessRate.toFixed(2)}%</div>
            </button>
          </div>
          <div class="grid gap-4 md:grid-cols-[260px_1fr]">
            <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200 bg-white p-4">
              <div class="h-52 w-52 rounded-full border border-slate-200" style="${pieStyle}"></div>
              <div class="mt-3 text-xs text-slate-500">${footerLabel}: ${footerValue}</div>
            </div>
            <div class="space-y-2">${legendHtml}</div>
          </div>
        `;

        chartContainer.querySelectorAll('[data-chart-metric]').forEach((card) => {
          card.addEventListener('click', function () {
            const nextMetric = this.getAttribute('data-chart-metric') || '';
            if (!metricConfig[nextMetric] || nextMetric === activeChartMetric) {
              return;
            }

            if (!metricHasData(nextMetric)) {
              alert(metricConfig[nextMetric].emptyText);
              return;
            }

            renderMetricChart(nextMetric);
          });
        });
      }

      if (!metricHasData(activeChartMetric)) {
        const fallbackMetric = ['employees', 'outstanding', 'absences', 'tardiness']
          .find((metric) => metricHasData(metric));
        if (!fallbackMetric) {
          chartContainer.classList.add('hidden');
          chartContainer.innerHTML = '';
          return false;
        }
        activeChartMetric = fallbackMetric;
      }

      renderMetricChart(activeChartMetric);
      return true;
    }

    if (summaryBtn) {
      summaryBtn.addEventListener('click', function () {
        if (!isSummaryView) {
          renderSummaryTable();
          summaryBtn.innerHTML = '<i class="fa-solid fa-table mr-2"></i>Back to Table';
          if (chartBtn) {
            chartBtn.classList.remove('hidden');
            chartBtn.innerHTML = '<i class="fa-solid fa-chart-column mr-2"></i>Chart';
          }
          isSummaryView = true;
          isChartView = false;
          return;
        }

        if (chartContainer) {
          chartContainer.classList.add('hidden');
          chartContainer.innerHTML = '';
        }
        if (tableWrapper) {
          tableWrapper.classList.remove('hidden');
        }
        table.querySelector('thead').innerHTML = originalTheadHtml;
        table.querySelector('tbody').innerHTML = originalTbodyHtml;
        summaryBtn.innerHTML = '<i class="fa-solid fa-chart-pie mr-2"></i>Summary';
        if (chartBtn) {
          chartBtn.classList.add('hidden');
          chartBtn.innerHTML = '<i class="fa-solid fa-chart-column mr-2"></i>Chart';
        }
        isSummaryView = false;
        isChartView = false;
      });
    }

    if (chartBtn) {
      chartBtn.addEventListener('click', function () {
        if (!isSummaryView) {
          return;
        }

        if (!isChartView) {
          const canRenderChart = renderSummaryChart();
          if (!canRenderChart) {
            alert('No summary data to chart.');
            return;
          }
          if (tableWrapper) {
            tableWrapper.classList.add('hidden');
          }
          if (chartContainer) {
            chartContainer.classList.remove('hidden');
          }
          chartBtn.innerHTML = '<i class="fa-solid fa-table mr-2"></i>Back to Summary';
          isChartView = true;
          return;
        }

        if (chartContainer) {
          chartContainer.classList.add('hidden');
        }
        if (tableWrapper) {
          tableWrapper.classList.remove('hidden');
        }
        chartBtn.innerHTML = '<i class="fa-solid fa-chart-column mr-2"></i>Chart';
        isChartView = false;
      });
    }

    if (pdfBtn) {
      pdfBtn.addEventListener('click', function () {
        const printWindow = window.open('', '_blank');
        if (!printWindow) {
          return;
        }

        const tableHtml = table.outerHTML;
        printWindow.document.write(`
          <html>
            <head>
              <title>Total Employees Attendance</title>
              <style>
                body { font-family: Arial, sans-serif; margin: 24px; color: #111827; }
                h1 { font-size: 18px; margin-bottom: 12px; }
                table { width: 100%; border-collapse: collapse; font-size: 12px; }
                th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
                th { background: #f1f5f9; }
              </style>
            </head>
            <body>
              <h1>Total Employees Attendance</h1>
              ${tableHtml}
            </body>
          </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
      });
    }
  })();
</script>
