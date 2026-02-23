<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PeopleHub – HR Dashboard</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    body { font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif; transition: margin-left 0.3s ease; }
    main { transition: margin-left 0.3s ease; }
    aside ~ main { margin-left: 16rem; }
  </style>
</head>
<body class="bg-slate-100">

<div class="flex min-h-screen">

  <!-- Sidebar -->
    @include('components.adminSideBar')


  <!-- Main Content -->
  <main class="flex-1 ml-16 transition-all duration-300">

    <!-- Header -->
     @include('components.adminHeader.dashboardHeader')

    <!-- Dashboard Content -->
    <div class="p-4 md:p-8 space-y-6 pt-20">

      <!-- Stats -->
      <div class="grid grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-5 flex justify-between items-center">
          <div>
            <p class="text-sm text-slate-500">Total Employees</p>
            <h2 class="text-2xl font-semibold">{{ number_format($totalEmployeeCount ?? 0) }}</h2>
            @php
              $employeeChange = (float) ($monthlyEmployeePercentChange ?? 0);
              $employeeChangeDirection = $employeeChange > 0 ? 'up' : ($employeeChange < 0 ? 'down' : 'flat');
              $employeeChangeClass = $employeeChangeDirection === 'up'
                ? 'text-emerald-500'
                : ($employeeChangeDirection === 'down' ? 'text-red-500' : 'text-slate-500');
              $employeeChangeArrow = $employeeChangeDirection === 'up'
                ? '↑'
                : ($employeeChangeDirection === 'down' ? '↓' : '→');
              $employeeChangeSign = $employeeChange > 0 ? '+' : '';
            @endphp
            <p class="text-sm {{ $employeeChangeClass }}">{{ $employeeChangeArrow }} {{ $employeeChangeSign }}{{ number_format($employeeChange, 1) }}% this month</p>
          </div>
          <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-users"></i>
          </div>
        </div>

        <div class="bg-white rounded-xl p-5 flex justify-between items-center">
          <div>
            <p class="text-sm text-slate-500">Present Today</p>
            <h2 class="text-2xl font-semibold">{{ number_format($presentTodayCount ?? 0) }}</h2>
            <p class="text-sm text-slate-500">{{ number_format((float) ($presentTodayRate ?? 0), 1) }}% attendance rate</p>
          </div>
          <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-check"></i>
          </div>
        </div>

        <div class="bg-white rounded-xl p-5 flex justify-between items-center">
          <div>
            <p class="text-sm text-slate-500">On Leave</p>
            <h2 class="text-2xl font-semibold">{{ number_format($onLeaveTodayCount ?? 0) }}</h2>
            <p class="text-sm text-orange-500">{{ number_format($pendingLeaveRequestCount ?? 0) }} pending requests</p>
          </div>
          <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-calendar"></i>
          </div>
        </div>

        <div class="bg-white rounded-xl p-5 flex justify-between items-center">
          <div>
            <p class="text-sm text-slate-500">Open Positions</p>
            <h2 class="text-2xl font-semibold">{{ number_format($openPositionsCount ?? 0) }}</h2>
            <p class="text-sm text-purple-500">{{ number_format($openPositionApplicationsCount ?? 0) }} applications</p>
          </div>
          <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-briefcase"></i>
          </div>
        </div>
      </div>

      <!-- Middle Section -->
      <div class="grid grid-cols-3 gap-6">

        <!-- Recent Employees -->
        <div class="col-span-2 bg-white rounded-xl p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold">Recent Employees</h3>
            <button class="text-sm text-emerald-600 bg-emerald-50 px-3 py-1 rounded">View All</button>
          </div>

          <table class="w-full text-sm mb-6">
            <thead class="text-slate-500 border-b">
              <tr>
                <th class="py-2 text-left">Employee</th>
                <th class="text-left">Department</th>
                <th class="text-left">Status</th>
                <th class="text-left">Join Date</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              @forelse($accept as $acc)
              <tr>
                <td class="py-3">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-500 rounded-full text-white flex items-center justify-center">{{ $acc->initials }}</div>
                    <div>
                      <p class="font-medium">{{ trim($acc->first_name.' '.$acc->middle_name.' '.$acc->last_name) }}</p>
                      <p class="text-xs text-slate-500">{{ $acc->email }}</p>
                    </div>
                  </div>
                </td>
                <td>{{ data_get($acc, 'employee.department') ?? data_get($acc, 'applicant.position.department') ?? 'Unassigned' }}</td>
                    <td><span class="text-xs bg-emerald-100 text-emerald-600 px-2 py-1 rounded">Active</span></td>
                    <td>{{ $acc->created_at_formatted ?? '-' }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="py-4 text-center text-slate-400">No recent employees found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>

          <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold">New Account Employees</h3>
            <button class="text-sm text-emerald-600 bg-emerald-50 px-3 py-1 rounded">View All</button>
          </div>

<table class="w-full text-sm">
  <thead class="text-slate-500 border-b">
    <tr>
      <th class="py-2 text-left">Employee</th>
      <th class="text-left"></th>
      <th class="text-left">Action</th>
      <th class="text-left">Join Date</th>
    </tr>
  </thead>
  <tbody class="divide-y">
    @forelse($employee as $e)
    <tr>
      <td class="py-3">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 bg-blue-500 rounded-full text-white flex items-center justify-center">{{ $e->initials }}</div>
          <div>
            <p class="font-medium">{{ trim($e->first_name.' '.$e->middle_name.' '.$e->last_name) }}</p>
            <p class="text-xs text-slate-500">{{ $e->email }}</p>
          </div>
        </div>
      </td>
      <td></td>
        <td>
          <div class="flex gap-2">
            <form action="{{ route('admin.updateEmployee', $e->id) }}" method="POST">
                @csrf
                <button type="submit" class="text-xs bg-emerald-100 text-emerald-600 px-2 py-1 rounded hover:bg-emerald-200">Accept</button>
            </form>
            <form action="{{ route('admin.destroyEmployee', $e->id) }}" method="POST">
                @csrf
                <button type="submit" class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded hover:bg-red-200">Declined</button>
            </form>
          </div>
        </td>
      <td>{{ $e->created_at_formatted ?? '-' }}</td>
    </tr>
    @empty
    <tr>
      <td colspan="4" class="py-4 text-center text-slate-400">No new account employees found.</td>
    </tr>
    @endforelse
  </tbody>
</table>

        </div>




        <!-- Right Column -->
        <div class="space-y-6">

          <!-- Leave Requests -->
          <div class="bg-white rounded-xl p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="font-semibold">Leave Requests</h3>
              <span class="w-6 h-6 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">{{ (int) ($pendingLeaveRequestCount ?? 0) }}</span>
            </div>

            <div class="space-y-4">
              @forelse (($pendingLeaveRequestsForHome ?? collect()) as $request)
                @php
                  $requestName = trim((string) ($request->employee_name ?? ''));
                  if ($requestName === '') {
                    $requestName = 'Unknown Employee';
                  }
                  $nameParts = preg_split('/\s+/', $requestName) ?: [];
                  $initials = '';
                  foreach (array_slice($nameParts, 0, 2) as $part) {
                    $initials .= strtoupper(substr($part, 0, 1));
                  }
                  $initials = $initials !== '' ? $initials : 'NA';
                  $leaveType = (string) ($request->leave_type ?: 'Leave Request');
                  $startDate = $request->filing_date
                    ? \Carbon\Carbon::parse($request->filing_date)->startOfDay()
                    : \Carbon\Carbon::parse($request->created_at)->startOfDay();
                  $days = (float) ($request->number_of_working_days ?? 0);
                  if ($days <= 0) {
                    $days = max(
                      (float) ($request->days_with_pay ?? 0),
                      (float) ($request->applied_total ?? 0)
                    );
                  }
                  $rangeDays = max((int) ceil($days), 1);
                  $endDate = $startDate->copy()->addDays($rangeDays - 1);
                  $dateLabel = $startDate->isSameDay($endDate)
                    ? $startDate->format('M d, Y')
                    : $startDate->format('M d').' - '.$endDate->format('M d, Y');
                @endphp
                <div class="border rounded-lg p-4">
                  <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                      <div class="w-9 h-9 bg-blue-500 rounded-full text-white flex items-center justify-center">{{ $initials }}</div>
                      <div>
                        <p class="font-medium">{{ $requestName }}</p>
                        <p class="text-xs text-slate-500">{{ $leaveType }} - {{ $dateLabel }}</p>
                      </div>
                    </div>
                    <span class="text-xs bg-orange-100 text-orange-600 px-2 py-1 rounded">Pending</span>
                  </div>
                  <div class="flex gap-2 mt-3">
                    <form class="flex-1" method="POST" action="{{ route('admin.updateLeaveRequestStatus', $request->id) }}">
                      @csrf
                      <input type="hidden" name="status" value="Approved">
                      <input type="hidden" name="redirect_back" value="1">
                      <button type="submit" class="w-full bg-emerald-500 text-white py-1.5 rounded hover:bg-emerald-600">Approve</button>
                    </form>
                    <form class="flex-1" method="POST" action="{{ route('admin.updateLeaveRequestStatus', $request->id) }}">
                      @csrf
                      <input type="hidden" name="status" value="Rejected">
                      <input type="hidden" name="redirect_back" value="1">
                      <button type="submit" class="w-full bg-slate-100 py-1.5 rounded hover:bg-slate-200">Decline</button>
                    </form>
                  </div>
                </div>
              @empty
                <div class="border rounded-lg p-4 text-sm text-slate-500">
                  No pending leave requests.
                </div>
              @endforelse
            </div>
          </div>

          <!-- Department Overview -->
          <div class="bg-white rounded-xl p-6">
            <h3 class="font-semibold mb-4">Department Overview</h3>

            <div class="space-y-3 text-sm">
              @php
                $colors = ['#10b981', '#3b82f6', '#f97316', '#a855f7', '#ec4899', '#6366f1'];
                $colorIndex = 0;
                $totalEmployees = $departments->sum('count') ?? 1;
              @endphp
              
              @forelse($departments as $dept)
                @php
                  $percentage = ($dept['count'] / $totalEmployees) * 100;
                  $color = $colors[$colorIndex % count($colors)];
                  $colorIndex++;
                @endphp
                <div>
                  <div class="flex justify-between">
                    <span>{{ $dept['name'] }}</span>
                    <span>{{ $dept['count'] }} ({{ round($percentage) }}%)</span>
                  </div>
                  <div class="h-2 bg-slate-100 rounded mt-1">
                    <div class="h-2 rounded" style="width: {{ $percentage }}%; background-color: {{ $color }};"></div>
                  </div>
                </div>
              @empty
                <p class="text-slate-400">No department data available</p>
              @endforelse
            </div>
          </div>

        </div>
      </div>

    </div>
  </main>
</div>

</body>

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

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

</html>
