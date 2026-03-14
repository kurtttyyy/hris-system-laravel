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
<body class="min-h-screen bg-[linear-gradient(180deg,#f8fbff_0%,#f7fafc_45%,#eefbf6_100%)] text-slate-800">
<div class="flex min-h-screen">
  @include('components.adminSideBar')

  <main class="flex-1 ml-16 transition-all duration-300">
    @include('components.adminHeader.leaveHeader')

    <div class="p-4 md:p-8 pt-20 space-y-6">
      @php
        $selectedMonthValue = $selectedMonth ?? now()->format('Y-m');
        $selectedMonthLabel = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonthValue)->format('F Y');
        $topLeaveEntry = collect($leaveTypeCounts ?? [])->sortDesc()->first();
        $topLeaveType = collect($leaveTypeCounts ?? [])->sortDesc()->keys()->first() ?? '-';
        $pendingRequestCount = ($pendingLeaveRequests ?? collect())->count();
        $approvedRequestCount = ($monthRecords ?? collect())->count();
        $pendingLeaveDaysLabel = rtrim(rtrim(number_format((float) ($pendingLeaveDays ?? 0), 1, '.', ''), '0'), '.');
      @endphp

      @if (session('success'))
        <div class="rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">
          {{ session('success') }}
        </div>
      @endif

      <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[1.75rem] border border-white/80 bg-white/90 p-5 shadow-[0_18px_40px_rgba(15,23,42,0.06)] backdrop-blur">
          <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-600">
            <i class="fa-regular fa-calendar-check text-lg"></i>
          </span>
          <p class="mt-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Leave Used This Month</p>
          <p class="mt-2 text-3xl font-black tracking-tight text-slate-900">{{ number_format((int) ($totalLeaveUsedDays ?? 0)) }}</p>
          <p class="mt-1 text-sm text-slate-500">Total approved leave days</p>
        </div>

        <div class="rounded-[1.75rem] border border-white/80 bg-white/90 p-5 shadow-[0_18px_40px_rgba(15,23,42,0.06)] backdrop-blur">
          <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
            <i class="fa-solid fa-notes-medical text-lg"></i>
          </span>
          <p class="mt-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Sick Leave Used</p>
          <p class="mt-2 text-3xl font-black tracking-tight text-blue-700">{{ number_format((int) ($sickLeaveUsedDays ?? 0)) }}</p>
          <p class="mt-1 text-sm text-slate-500">Approved sick leave days</p>
        </div>

        <div class="rounded-[1.75rem] border border-white/80 bg-white/90 p-5 shadow-[0_18px_40px_rgba(15,23,42,0.06)] backdrop-blur">
          <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
            <i class="fa-solid fa-badge-check text-lg"></i>
          </span>
          <p class="mt-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Approved Requests</p>
          <p class="mt-2 text-3xl font-black tracking-tight text-emerald-700">{{ number_format($approvedRequestCount) }}</p>
          <p class="mt-1 text-sm text-slate-500">Approved leave records in month</p>
        </div>

        <div class="rounded-[1.75rem] border border-white/80 bg-white/90 p-5 shadow-[0_18px_40px_rgba(15,23,42,0.06)] backdrop-blur">
          <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-100 text-violet-600">
            <i class="fa-solid fa-layer-group text-lg"></i>
          </span>
          <p class="mt-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Top Leave Type</p>
          <p class="mt-2 text-2xl font-black tracking-tight text-violet-700">{{ $topLeaveType }}</p>
          <p class="mt-1 text-sm text-slate-500">{{ (int) ($topLeaveEntry ?? 0) }} day(s)</p>
        </div>
      </div>

      <div class="grid gap-6 xl:grid-cols-[minmax(0,1.05fr)_minmax(0,1.15fr)]">
        <section class="overflow-hidden rounded-[1.75rem] border border-amber-100/80 bg-white/92 shadow-[0_22px_50px_rgba(15,23,42,0.07)] backdrop-blur">
          <div class="border-b border-amber-100 bg-[linear-gradient(180deg,rgba(254,243,199,0.45),rgba(255,255,255,0.85))] px-5 py-4">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
              <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-white/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-amber-700">
                  Priority Queue
                </div>
                <h3 class="mt-3 text-xl font-black tracking-tight text-slate-900">Pending Leave Requests</h3>
                <p class="mt-1 text-sm text-slate-500">{{ $selectedMonthLabel }} • {{ $pendingRequestCount }} request(s) • {{ $pendingLeaveDaysLabel }} day(s)</p>
              </div>
            </div>
          </div>

          <div class="p-4 space-y-4">
            @forelse (($pendingLeaveRequests ?? collect()) as $request)
              @php
                $requestFilingDate = $request->filing_date ? \Carbon\Carbon::parse($request->filing_date)->format('M d, Y') : optional($request->created_at)->format('M d, Y');
                $requestDays = rtrim(rtrim(number_format((float) ($request->number_of_working_days ?? 0), 1, '.', ''), '0'), '.');
                $requestLeaveType = $request->leave_type ?: 'Leave Request';
                $requestDates = $request->inclusive_dates ?: '-';
                $requestReason = str_contains(strtolower((string) $requestLeaveType), 'official business')
                  ? 'Business Trip'
                  : (str_contains(strtolower((string) $requestLeaveType), 'annual leave') ? 'Personal vacation' : (str_contains(strtolower((string) $requestLeaveType), 'sick leave') ? 'Not fit for work due to health reasons' : $requestDates));
                $employeeName = trim((string) ($request->employee_name ?? '-'));
                $nameParts = array_values(array_filter(explode(' ', $employeeName)));
                $initials = strtoupper(substr($nameParts[0] ?? 'L', 0, 1).substr($nameParts[count($nameParts) - 1] ?? 'R', 0, 1));
              @endphp
              <div class="rounded-[1.5rem] border border-slate-200 bg-[linear-gradient(180deg,#fffef7,#ffffff)] p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                  <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-sm font-bold text-amber-700">
                      {{ $initials !== '' ? $initials : 'LR' }}
                    </div>
                    <div>
                      <div class="flex flex-wrap items-center gap-2">
                        <p class="text-base font-semibold text-slate-900">{{ $requestLeaveType }}</p>
                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-amber-700">Pending</span>
                      </div>
                      <p class="mt-1 text-sm font-semibold text-slate-800">{{ $employeeName }}</p>
                      <p class="mt-1 text-sm text-slate-500">Filed: {{ $requestFilingDate }} • {{ $requestDays }} day(s)</p>
                      <p class="mt-1 text-sm text-slate-400">{{ $requestReason }}</p>
                    </div>
                  </div>

                  <div class="flex items-center gap-2 shrink-0">
                    <form method="POST" action="{{ route('admin.updateLeaveRequestStatus', $request->id) }}">
                      @csrf
                      <input type="hidden" name="status" value="Approved">
                      <input type="hidden" name="month" value="{{ $selectedMonthValue }}">
                      <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700">
                        <i class="fa-solid fa-check"></i>
                        Approve
                      </button>
                    </form>
                    <form method="POST" action="{{ route('admin.updateLeaveRequestStatus', $request->id) }}">
                      @csrf
                      <input type="hidden" name="status" value="Rejected">
                      <input type="hidden" name="month" value="{{ $selectedMonthValue }}">
                      <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-rose-700">
                        <i class="fa-solid fa-xmark"></i>
                        Reject
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            @empty
              <div class="rounded-[1.5rem] border border-dashed border-amber-200 bg-amber-50/60 px-6 py-10 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-amber-500 shadow-sm">
                  <i class="fa-regular fa-calendar-check text-xl"></i>
                </div>
                <p class="mt-4 text-sm font-semibold text-slate-700">No pending leave requests for this month.</p>
                <p class="mt-1 text-sm text-slate-500">Everything is up to date for {{ $selectedMonthLabel }}.</p>
              </div>
            @endforelse
          </div>
        </section>

        <section class="overflow-hidden rounded-[1.75rem] border border-white/80 bg-white/92 shadow-[0_22px_50px_rgba(15,23,42,0.07)] backdrop-blur">
          <div class="border-b border-slate-200 bg-[linear-gradient(180deg,rgba(239,246,255,0.7),rgba(255,255,255,0.92))] px-5 py-4">
            <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-white/85 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-sky-700">
              Approved Timeline
            </div>
            <h3 class="mt-3 text-xl font-black tracking-tight text-slate-900">Leave History</h3>
            <p class="mt-1 text-sm text-slate-500">Approved records for {{ $selectedMonthLabel }}</p>
          </div>

          <div class="p-4 space-y-4">
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
                  'Annual Leave' => 'fa-solid fa-umbrella-beach',
                  'Sick Leave' => 'fa-solid fa-notes-medical',
                  'Personal Leave' => 'fa-solid fa-user-clock',
                  'Study Leave' => 'fa-solid fa-graduation-cap',
                  'Emergency Leave' => 'fa-solid fa-triangle-exclamation',
                  'Maternity Leave' => 'fa-solid fa-baby',
                  'Paternity Leave' => 'fa-solid fa-people-roof',
                  'Bereavement Leave' => 'fa-solid fa-ribbon',
                  'Service Incentive Leave' => 'fa-solid fa-star',
                ];
                $colorMap = [
                  'Annual Leave' => 'bg-emerald-100 text-emerald-700',
                  'Sick Leave' => 'bg-blue-100 text-blue-700',
                  'Personal Leave' => 'bg-amber-100 text-amber-700',
                  'Study Leave' => 'bg-violet-100 text-violet-700',
                  'Emergency Leave' => 'bg-rose-100 text-rose-700',
                  'Maternity Leave' => 'bg-pink-100 text-pink-700',
                  'Paternity Leave' => 'bg-cyan-100 text-cyan-700',
                  'Bereavement Leave' => 'bg-slate-200 text-slate-700',
                  'Service Incentive Leave' => 'bg-yellow-100 text-yellow-700',
                ];
                $iconClass = $iconMap[$leaveType] ?? 'fa-regular fa-file-lines';
                $iconToneClass = $colorMap[$leaveType] ?? 'bg-slate-100 text-slate-700';
                $reasonLabel = str_contains(strtolower($leaveType), 'official business')
                  ? 'Business Trip'
                  : (str_contains(strtolower($leaveType), 'annual leave') ? 'Personal vacation' : (str_contains(strtolower($leaveType), 'sick leave') ? 'Not fit for work due to health reasons' : ($record['reason'] ?? '-')));
              @endphp
              <div class="rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                  <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $iconToneClass }}">
                      <i class="{{ $iconClass }} text-lg"></i>
                    </div>
                    <div>
                      <div class="flex flex-wrap items-center gap-2">
                        <p class="text-base font-semibold text-slate-900">{{ $leaveType }}</p>
                        <span class="inline-flex rounded-full bg-sky-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-sky-700">Employee</span>
                      </div>
                      <p class="mt-1 text-sm font-semibold text-slate-800">{{ $record['employee_name'] ?? '-' }}</p>
                      <p class="mt-1 text-sm text-slate-500">{{ $dateLabel }} • {{ $daysLabel }}</p>
                      <p class="mt-1 text-sm text-slate-400">{{ $reasonLabel }}</p>
                    </div>
                  </div>
                  <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Approved</span>
                </div>
              </div>
            @empty
              <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50/70 px-6 py-10 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                  <i class="fa-regular fa-folder-open text-xl"></i>
                </div>
                <p class="mt-4 text-sm font-semibold text-slate-700">No approved leave records for this month.</p>
                <p class="mt-1 text-sm text-slate-500">Approved leave history will appear here once requests are processed.</p>
              </div>
            @endforelse
          </div>
        </section>
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
