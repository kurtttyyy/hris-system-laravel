<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PeopleHub - Leave Management</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    body { font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif; transition: margin-left 0.3s ease; }
    main { transition: margin-left 0.3s ease; }
    aside ~ main { margin-left: 16rem; }
  </style>
</head>
<body class="bg-slate-100">
<div class="flex min-h-screen">
  @include('components.adminSideBar')

  <main class="flex-1 ml-16 transition-all duration-300">
    @include('components.adminHeader.leaveHeader')

    <div class="p-4 md:p-8 pt-20 space-y-6">
      @if (session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
          {{ session('success') }}
        </div>
      @endif

      <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.adminLeaveManagement') }}" class="flex items-center gap-3">
          <label class="text-sm font-medium text-gray-700">Month</label>
          <input
            type="month"
            name="month"
            value="{{ $selectedMonth ?? now()->format('Y-m') }}"
            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <button type="submit" class="rounded-lg bg-slate-700 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800">
            Apply
          </button>
        </form>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
          <p class="text-xs text-gray-500 uppercase tracking-wide">Leave Used This Month</p>
          <p class="mt-2 text-3xl font-bold text-slate-800">{{ number_format((int) ($totalLeaveUsedDays ?? 0)) }}</p>
          <p class="mt-1 text-sm text-gray-500">Total approved leave days</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
          <p class="text-xs text-gray-500 uppercase tracking-wide">Sick Leave Used</p>
          <p class="mt-2 text-3xl font-bold text-blue-700">{{ number_format((int) ($sickLeaveUsedDays ?? 0)) }}</p>
          <p class="mt-1 text-sm text-gray-500">Approved sick leave days</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
          <p class="text-xs text-gray-500 uppercase tracking-wide">Approved Requests</p>
          <p class="mt-2 text-3xl font-bold text-emerald-700">{{ number_format(($monthRecords ?? collect())->count()) }}</p>
          <p class="mt-1 text-sm text-gray-500">Approved leave records in month</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
          <p class="text-xs text-gray-500 uppercase tracking-wide">Top Leave Type</p>
          @php
            $topLeaveEntry = collect($leaveTypeCounts ?? [])->sortDesc()->first();
            $topLeaveType = collect($leaveTypeCounts ?? [])->sortDesc()->keys()->first() ?? '-';
          @endphp
          <p class="mt-2 text-2xl font-bold text-purple-700">{{ $topLeaveType }}</p>
          <p class="mt-1 text-sm text-gray-500">{{ (int) ($topLeaveEntry ?? 0) }} day(s)</p>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
          <h3 class="text-sm font-semibold text-amber-700">Pending Leave Requests ({{ $selectedMonth ?? now()->format('Y-m') }})</h3>
          <div class="text-xs text-gray-500">
            {{ ($pendingLeaveRequests ?? collect())->count() }} request(s) •
            {{ rtrim(rtrim(number_format((float) ($pendingLeaveDays ?? 0), 1, '.', ''), '0'), '.') }} day(s)
          </div>
        </div>
        <div>
          @forelse (($pendingLeaveRequests ?? collect()) as $request)
            @php
              $requestFilingDate = $request->filing_date ? \Carbon\Carbon::parse($request->filing_date)->format('M d, Y') : optional($request->created_at)->format('M d, Y');
              $requestDays = rtrim(rtrim(number_format((float) ($request->number_of_working_days ?? 0), 1, '.', ''), '0'), '.');
              $requestLeaveType = $request->leave_type ?: 'Leave Request';
              $requestDates = $request->inclusive_dates ?: '-';
              $requestReason = str_contains(strtolower((string) $requestLeaveType), 'official business')
                ? 'Business Trip'
                : (str_contains(strtolower((string) $requestLeaveType), 'annual leave') ? 'Personal vacation' : (str_contains(strtolower((string) $requestLeaveType), 'sick leave') ? 'Not fit for work due to health reasons' : $requestDates));
            @endphp
            <div class="px-4 py-4 border-b border-slate-100 last:border-b-0 flex items-center justify-between gap-4">
              <div>
                <p class="font-semibold text-gray-900">
                  {{ $requestLeaveType }}
                  <span class="ml-2 inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700">Pending</span>
                </p>
                <p class="text-sm font-semibold text-gray-800">{{ $request->employee_name ?? '-' }}</p>
                <p class="text-sm text-gray-500">Filed: {{ $requestFilingDate }} • {{ $requestDays }} day(s)</p>
                <p class="text-sm text-gray-400">{{ $requestReason }}</p>
              </div>
              <div class="flex items-center gap-2 shrink-0">
                <form method="POST" action="{{ route('admin.updateLeaveRequestStatus', $request->id) }}">
                  @csrf
                  <input type="hidden" name="status" value="Approved">
                  <input type="hidden" name="month" value="{{ $selectedMonth ?? now()->format('Y-m') }}">
                  <button type="submit" class="inline-flex rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                    Approve
                  </button>
                </form>
                <form method="POST" action="{{ route('admin.updateLeaveRequestStatus', $request->id) }}">
                  @csrf
                  <input type="hidden" name="status" value="Rejected">
                  <input type="hidden" name="month" value="{{ $selectedMonth ?? now()->format('Y-m') }}">
                  <button type="submit" class="inline-flex rounded-md bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">
                    Reject
                  </button>
                </form>
              </div>
            </div>
          @empty
            <div class="px-4 py-6 text-center text-sm text-gray-500">
              No pending leave requests for this month.
            </div>
          @endforelse
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
          <h3 class="text-sm font-semibold text-gray-700">Leave History (Approved, {{ $selectedMonth ?? now()->format('Y-m') }})</h3>
        </div>
        <div>
          @forelse (($monthRecords ?? collect()) as $record)
            @php
              $leaveType = (string) ($record['leave_type'] ?? 'Leave');
              $startDate = $record['start_date_carbon'] ?? null;
              $endDate = $record['end_date_carbon'] ?? null;
              $days = (int) ($record['days'] ?? 0);
              $daysLabel = $days === 1 ? '1 day' : ($days.' days');
              $dateLabel = '-';
              if ($startDate && $endDate) {
                $dateLabel = $startDate->isSameDay($endDate)
                  ? $startDate->format('M d, Y')
                  : $startDate->format('M d, Y').' - '.$endDate->format('M d, Y');
              }

              $iconMap = [
                'Annual Leave' => "\u{1F334}",
                'Sick Leave' => "\u{1FA7A}",
                'Personal Leave' => "\u{1F7E1}",
                'Study Leave' => "\u{1F393}",
                'Emergency Leave' => "\u{1F6A8}",
                'Maternity Leave' => "\u{1F476}",
                'Paternity Leave' => "\u{1F468}",
                'Bereavement Leave' => "\u{1F5CA}\u{FE0F}",
                'Service Incentive Leave' => "\u{2B50}",
              ];
              $icon = $iconMap[$leaveType] ?? "\u{1F4C4}";
              $reasonLabel = str_contains(strtolower($leaveType), 'official business')
                ? 'Business Trip'
                : (str_contains(strtolower($leaveType), 'annual leave') ? 'Personal vacation' : (str_contains(strtolower($leaveType), 'sick leave') ? 'Not fit for work due to health reasons' : ($record['reason'] ?? '-')));
            @endphp
            <div class="px-4 py-4 border-b border-slate-100 last:border-b-0 flex items-center justify-between gap-4">
              <div class="flex items-start gap-3">
                <div class="h-10 w-10 rounded-lg bg-emerald-100 flex items-center justify-center text-base">
                  {{ $icon }}
                </div>
                <div>
                  <p class="font-semibold text-gray-900">
                    {{ $leaveType }}
                    <span class="ml-2 inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">Employee</span>
                  </p>
                  <p class="text-sm font-semibold text-gray-800">{{ $record['employee_name'] ?? '-' }}</p>
                  <p class="text-sm text-gray-500">{{ $dateLabel }} • {{ $daysLabel }}</p>
                  <p class="text-sm text-gray-400">{{ $reasonLabel }}</p>
                </div>
              </div>
              <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Approved</span>
            </div>
          @empty
            <div class="px-4 py-6 text-center text-sm text-gray-500">
              No approved leave records for this month.
            </div>
          @endforelse
        </div>
      </div>


    </div>
  </main>
</div>

<script>
  const sidebar = document.querySelector('aside');
  const main = document.querySelector('main');
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
</body>
</html>






