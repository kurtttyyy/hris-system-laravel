<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - Northeastern College</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            transition: margin-left 0.3s ease;
        }
        
        main {
            transition: margin-left 0.3s ease;
        }
        
        aside:not(:hover) ~ main {
            margin-left: 4rem;
        }
        
        aside:hover ~ main {
            margin-left: 14rem;
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('components.employeeSideBar')

    <!-- Main Content -->
    <main class="flex-1 ml-16 transition-all duration-300">
        <!-- Top Header -->
    @include('components.employeeHeader.leaveHeader')


<div class="p-4 md:p-8 space-y-8 pt-20">
    @php
        $authUser = auth()->user();
        $activeEmployeeForm = request()->query('form', 'leave');
        if (!in_array($activeEmployeeForm, ['leave', 'official'], true)) {
            $activeEmployeeForm = 'leave';
        }
        $employeeFormName = $employeeDisplayName
            ?? trim(implode(' ', array_filter([
                $authUser?->first_name ?? null,
                $authUser?->middle_name ?? null,
                $authUser?->last_name ?? null,
            ])));
        $employeeFormPosition = $authUser?->employee?->position
            ?? data_get($authUser, 'applicant.position.title')
            ?? '';
        $employeeFormQueryBase = array_filter([
            'month' => $selectedMonth ?? now()->format('Y-m'),
        ], fn ($value) => !is_null($value) && $value !== '');
    @endphp
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" action="{{ route('employee.employeeLeave') }}" class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700">Month</label>
            <input
                type="month"
                name="month"
                value="{{ $selectedMonth ?? now()->format('Y-m') }}"
                class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <input type="hidden" name="form" value="{{ $activeEmployeeForm }}">
            <button type="submit" class="rounded-lg bg-slate-700 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                Apply
            </button>
        </form>
    </div>

    <!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="relative bg-white rounded-2xl p-6 border border-gray-200">


            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-calendar fa-2x"></i>
            </div>
            <h3 class="text-4xl font-bold text-gray-900 mb-1 mt-7">{{ rtrim(rtrim(number_format((float) ($vacationCardAvailable ?? max(($annualLimit ?? 0) - ($annualUsed ?? 0), 0)), 1, '.', ''), '0'), '.') }}</h3>
            <p class="text-gray-600 text-sm mb-4">Vacation Leave</p>
            <p class="text-gray-500 text-xs mt-4">of {{ rtrim(rtrim(number_format((float) ($annualLimit ?? 0), 1, '.', ''), '0'), '.') }} days (used {{ (int) ($annualUsed ?? 0) }})</p>
        </div>


    <!-- Attendance Card -->
        <div class="relative bg-white rounded-2xl p-6 border border-gray-200">

            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-bed fa-2x"></i>
            </div>
            <h3 class="text-4xl font-bold text-gray-900 mb-1 mt-7">{{ rtrim(rtrim(number_format((float) ($sickCardAvailable ?? max(($sickLimit ?? 0) - ($sickUsed ?? 0), 0)), 1, '.', ''), '0'), '.') }}</h3>
            <p class="text-gray-600 text-sm mb-1">Sick Leave</p>
            <p class="text-gray-500 text-xs mt-4">of {{ rtrim(rtrim(number_format((float) ($sickLimit ?? 0), 1, '.', ''), '0'), '.') }} days (used {{ (int) ($sickUsed ?? 0) }})</p>
        </div>


        <!-- Events Card -->
        <div class="relative bg-white rounded-2xl p-6 border border-gray-200">

            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-calendar-o fa-2x"></i>
            </div>
            <h3 class="text-4xl font-bold text-gray-900 mb-1 mt-7">{{ max(($personalLimit ?? 0) - ($personalUsed ?? 0), 0) }}</h3>
            <p class="text-gray-600 text-sm mb-1">Others</p>
            <p class="text-gray-500 text-xs mt-4">of {{ (int) ($personalLimit ?? 0) }} days (used {{ (int) ($personalUsed ?? 0) }})</p>
        </div>

        <!-- Salary Card -->
        <div class="relative bg-white rounded-2xl p-6 border border-gray-200">

            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-hourglass-half fa-2x"></i>
            </div>
            <h3 class="text-4xl font-bold text-gray-900 mb-1 mt-7">{{ rtrim(rtrim(number_format((float) ($totalDaysUsedCard ?? $totalDaysUsed ?? 0), 1, '.', ''), '0'), '.') }}</h3>
            <p class="text-gray-600 text-sm mb-1">Days Used</p>
            <p class="text-gray-500 text-xs mt-4">this month</p>
        </div>

</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-200">
        <h3 class="font-semibold text-gray-700">My Leave History ({{ now()->format('M d, Y') }})</h3>
    </div>
    <div class="max-h-96 overflow-y-auto">
        @forelse (($monthRequestRecords ?? collect()) as $record)
            @php
                $startDate = $record['start_date_carbon'] ?? null;
                $endDate = $record['end_date_carbon'] ?? null;
                $days = (float) ($record['days'] ?? 0);
                $daysLabel = rtrim(rtrim(number_format($days, 1, '.', ''), '0'), '.');
                $dateLabel = '-';
                $statusLabel = ucfirst(strtolower((string) ($record['status'] ?? 'Pending')));
                $statusClass = 'bg-amber-100 text-amber-700';
                if (strcasecmp($statusLabel, 'Approved') === 0) {
                    $statusClass = 'bg-green-100 text-green-700';
                } elseif (strcasecmp($statusLabel, 'Rejected') === 0) {
                    $statusClass = 'bg-rose-100 text-rose-700';
                }
                if ($startDate && $endDate) {
                    $dateLabel = $startDate->isSameDay($endDate)
                        ? $startDate->format('M d, Y')
                        : $startDate->format('M d, Y').' - '. $endDate->format('M d, Y');
                }
            @endphp
            <div class="px-4 py-4 border-b border-slate-100 last:border-b-0 flex items-center justify-between gap-4">
                <div>
                    <p class="font-semibold text-gray-900">{{ $record['leave_type'] ?? 'Leave' }}</p>
                    <p class="text-sm text-gray-700">{{ $employeeDisplayName ?? ($record['employee_name'] ?? '-') }}</p>
                    <p class="text-sm text-gray-500">{{ $dateLabel }} • {{ $daysLabel }} day(s)</p>
                    <p class="text-sm text-gray-400">{{ $record['reason'] ?? '-' }}</p>
                </div>
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>
        @empty
            <div class="px-4 py-5 text-sm text-gray-500">No leave records for this month.</div>
        @endforelse
    </div>
</div>

<div class="p-8 space-y-6 bg-white rounded-2xl border border-gray-200 flex flex-col md:flex-row gap-6">

    <!-- Left Filter Sidebar -->
<div class="w-full md:w-1/4 md:min-w-[280px] bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-2">
    <h4 class="font-semibold text-gray-700 mb-4">Select Form</h4>
    <ul class="space-y-2 text-sm">
        <li>
            <a
                href="{{ route('employee.employeeLeave', array_merge($employeeFormQueryBase, ['form' => 'leave'])) }}"
                class="block w-full text-left px-2 py-1 rounded {{ $activeEmployeeForm === 'leave' ? 'bg-blue-100 text-blue-700 font-medium' : 'hover:bg-blue-100 text-gray-800' }}">
                LEAVE APPLICATION FORM
            </a>
        </li>
        <li>
            <a
                href="{{ route('employee.employeeLeave', array_merge($employeeFormQueryBase, ['form' => 'official'])) }}"
                class="block w-full text-left px-2 py-1 rounded {{ $activeEmployeeForm === 'official' ? 'bg-blue-100 text-blue-700 font-medium' : 'hover:bg-blue-100 text-gray-800' }}">
                APPLICATION FOR OFFICIAL BUSINESS / OFFICIAL TIME
            </a>
        </li>
    </ul>
</div>



<div class="w-full md:flex-1 min-w-0 p-8 space-y-6 bg-white rounded-2xl border border-gray-200 overflow-x-auto text-base">
    @if ($activeEmployeeForm === 'official')
        <h3 class="text-xl font-bold text-gray-900 mb-4">Apply for Business</h3>
        @include('requestForm.applicationOBF')
    @else
        <h3 class="text-xl font-bold text-gray-900 mb-4">Apply for Leave</h3>
        @include('requestForm.leaveApplicationForm')
    @endif
</div>


</div>

    </div>
</div>

    </main>
</div>
<script>
    // Sidebar responsive adjustment
    const sidebar = document.querySelector('aside');
    const main = document.querySelector('main');
    
    if (sidebar && main) {
        sidebar.addEventListener('mouseenter', function() {
            main.classList.remove('ml-16');
            main.classList.add('ml-56');
        });
        
        sidebar.addEventListener('mouseleave', function() {
            main.classList.remove('ml-56');
            main.classList.add('ml-16');
        });
    }
</script>

</body>
</html>

