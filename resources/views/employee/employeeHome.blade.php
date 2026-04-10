<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - Northeastern College</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { transition: margin-left 0.3s ease; }
        main { transition: margin-left 0.3s ease; }
        aside:not(:hover) ~ main { margin-left: 4rem; }
        aside:hover ~ main { margin-left: 14rem; }
    </style>
</head>
<body class="bg-slate-100">

<div class="flex min-h-screen">
    @include('components.employeeSideBar')

    <main class="flex-1 ml-16 transition-all duration-300">
        @include('components.employeeHeader.dashboardHeader', [
            'notifications' => (int) ($notifications ?? 0),
        ])

        <div class="space-y-8 p-4 pt-20 md:p-8">
            <section class="relative overflow-hidden rounded-[2rem] border border-emerald-950/40 bg-gradient-to-br from-slate-950 via-emerald-950 to-emerald-800 p-6 text-white shadow-2xl md:p-8">
                <div class="absolute -right-10 -top-12 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute bottom-0 right-20 h-24 w-24 rounded-full bg-emerald-300/10 blur-2xl"></div>
                <div class="relative grid gap-6 lg:grid-cols-[1.7fr_1fr] lg:items-end">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-emerald-100">
                            Employee Workspace
                        </div>
                        <div>
                            <h1 class="max-w-2xl text-3xl font-black leading-tight md:text-5xl">Your workday at a glance.</h1>
                            <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50 md:text-base">
                                Track attendance, monitor leave balances, and jump into the tasks you use most without digging through menus.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3 text-sm">
                            <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur-sm">
                                <p class="text-xs uppercase tracking-wide text-emerald-100">Month</p>
                                <p class="mt-1 font-semibold">{{ now()->format('F Y') }}</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur-sm">
                                <p class="text-xs uppercase tracking-wide text-emerald-100">Attendance</p>
                                <p class="mt-1 font-semibold">{{ $attendanceStatusLabel ?? 'No Data' }}</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur-sm">
                                <p class="text-xs uppercase tracking-wide text-emerald-100">Available Leave</p>
                                <p class="mt-1 font-semibold">{{ rtrim(rtrim(number_format((float) ($combinedLeaveAvailable ?? 0), 1, '.', ''), '0'), '.') }} Days</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.75rem] border border-white/15 bg-white/10 p-5 backdrop-blur-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-100">Focus Panel</p>
                        <div class="mt-5 space-y-4">
                            <div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-emerald-50">Leave usage</span>
                                    <span class="font-semibold">{{ (int) ($combinedLeavePercentUsed ?? 0) }}%</span>
                                </div>
                                <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-white/15">
                                    <div class="h-full rounded-full bg-emerald-300" style="width: {{ (int) ($combinedLeavePercentUsed ?? 0) }}%;"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-2xl bg-white/10 p-4">
                                    <p class="text-xs uppercase tracking-wide text-emerald-100">Vacation</p>
                                    <p class="mt-2 text-xl font-bold">{{ rtrim(rtrim(number_format((float) ($vacationCardAvailable ?? 0), 1, '.', ''), '0'), '.') }}</p>
                                </div>
                                <div class="rounded-2xl bg-white/10 p-4">
                                    <p class="text-xs uppercase tracking-wide text-emerald-100">Sick</p>
                                    <p class="mt-2 text-xl font-bold">{{ rtrim(rtrim(number_format((float) ($sickCardAvailable ?? 0), 1, '.', ''), '0'), '.') }}</p>
                                </div>
                            </div>
                            <p class="text-xs leading-5 text-emerald-50">
                                Keep your records updated to avoid delays in payroll, leave approvals, and required document submissions.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            @if (!empty($accountAlerts ?? []))
                <section class="rounded-[1.75rem] border border-amber-200 bg-gradient-to-br from-amber-50 to-white p-5 shadow-sm md:p-6">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">Account Alerts</p>
                            <h3 class="mt-2 text-xl font-black text-slate-900">Action needed on your account</h3>
                            <p class="mt-1 text-sm text-slate-600">Review these updates to keep your account records complete and up to date.</p>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-700">
                            <i class="fa fa-exclamation-circle"></i>
                            {{ count($accountAlerts) }} item{{ count($accountAlerts) === 1 ? '' : 's' }}
                        </span>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-2">
                        @foreach ($accountAlerts as $alert)
                            @php
                                $tone = strtolower(trim((string) ($alert['tone'] ?? 'slate')));
                                $alertToneClass = match ($tone) {
                                    'rose' => 'border-rose-200 bg-rose-50',
                                    'amber' => 'border-amber-200 bg-amber-50',
                                    'violet' => 'border-violet-200 bg-violet-50',
                                    'sky' => 'border-sky-200 bg-sky-50',
                                    default => 'border-slate-200 bg-slate-50',
                                };
                            @endphp
                            <a href="{{ $alert['href'] ?? '#' }}" class="block rounded-2xl border px-4 py-3 transition hover:-translate-y-0.5 hover:shadow-sm {{ $alertToneClass }}">
                                <p class="text-sm font-bold text-slate-900">{{ $alert['title'] ?? 'Account update' }}</p>
                                <p class="mt-1 text-xs leading-5 text-slate-600">{{ $alert['desc'] ?? '' }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-[1.75rem] border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-500 text-white shadow-lg shadow-blue-500/25">
                            <i class="fa fa-calendar fa-2x"></i>
                        </div>
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">{{ (int) ($combinedLeavePercentUsed ?? 0) }}% Used</span>
                    </div>
                    <h3 class="mt-8 text-4xl font-black text-slate-900">{{ rtrim(rtrim(number_format((float) ($combinedLeaveAvailable ?? 0), 1, '.', ''), '0'), '.') }}</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600">Leave Balance</p>
                    <div class="mt-5 space-y-2 text-xs text-slate-500">
                        <p>Vacation: {{ rtrim(rtrim(number_format((float) ($vacationCardAvailable ?? 0), 1, '.', ''), '0'), '.') }} / {{ rtrim(rtrim(number_format((float) ($annualLimit ?? 0), 1, '.', ''), '0'), '.') }}</p>
                        <p>Sick: {{ rtrim(rtrim(number_format((float) ($sickCardAvailable ?? 0), 1, '.', ''), '0'), '.') }} / {{ rtrim(rtrim(number_format((float) ($sickLimit ?? 0), 1, '.', ''), '0'), '.') }}</p>
                    </div>
                    <div class="mt-5 h-2.5 overflow-hidden rounded-full bg-blue-100">
                        <div class="h-full rounded-full bg-blue-500" style="width: {{ (int) ($combinedLeavePercentUsed ?? 0) }}%;"></div>
                    </div>
                </article>

                <article class="rounded-[1.75rem] border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/25">
                            <i class="fa fa-clock-o fa-2x"></i>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $attendanceStatusLabel ?? 'No Data' }}</span>
                    </div>
                    <h3 class="mt-8 text-4xl font-black text-slate-900">{{ number_format((float) ($attendanceRatePercent ?? 0), 1) }}%</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600">Attendance Rate</p>
                    <p class="mt-5 text-xs leading-5 text-slate-500">{{ $attendanceMonthLabel ?? now()->format('F Y') }} • {{ (int) ($attendancePresentDays ?? 0) }}/{{ (int) ($attendanceTotalDays ?? 0) }} days present</p>
                </article>

                <article class="rounded-[1.75rem] border border-violet-100 bg-gradient-to-br from-violet-50 to-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-500 text-white shadow-lg shadow-violet-500/25">
                            <i class="fa fa-calendar-o fa-2x"></i>
                        </div>
                        <span id="month-events-badge" class="rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700">0 Events</span>
                    </div>
                    <h3 class="mt-8 text-3xl font-black text-slate-900">Monthly Pulse</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600">Upcoming Events</p>
                    <p id="month-events-caption" class="mt-5 text-xs leading-5 text-slate-500">{{ now()->format('F Y') }}</p>
                </article>

                <article class="rounded-[1.75rem] border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg shadow-amber-500/25">
                            <i class="fa fa-credit-card fa-2x"></i>
                        </div>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Salary</span>
                    </div>
                    <h3 class="mt-8 text-3xl font-black text-slate-900">Payment Hub</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600">Payslip and salary access</p>
                    <p class="mt-5 text-xs leading-5 text-slate-500">Use the payslip section to review your latest payroll records.</p>
                </article>
            </section>

            <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-700">Quick Actions</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-900">Jump back into the work that matters.</h3>
                    </div>
                    <p class="text-sm text-slate-500">Your most-used employee tools in one row.</p>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    <a href="{{ route('employee.employeeLeave') }}" class="group rounded-[1.5rem] border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-6 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-500 text-white shadow-lg shadow-blue-500/20">
                            <i class="fa fa-calendar-check-o fa-2x"></i>
                        </div>
                        <h4 class="mt-5 text-lg font-bold text-slate-900">Apply Leave</h4>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Submit requests, review balances, and monitor approvals from one place.</p>
                    </a>

                    <a href="{{ route('employee.employeeDocument') }}" class="group rounded-[1.5rem] border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-6 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                            <i class="fa fa-folder-open fa-2x"></i>
                        </div>
                        <h4 class="mt-5 text-lg font-bold text-slate-900">Documents</h4>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Manage 201 file uploads, organize folders, and keep requirements complete.</p>
                    </a>

                    <a href="{{ route('employee.employeePayslip') }}" class="group rounded-[1.5rem] border border-violet-100 bg-gradient-to-br from-violet-50 to-white p-6 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-500 text-white shadow-lg shadow-violet-500/20">
                            <i class="fa fa-money fa-2x"></i>
                        </div>
                        <h4 class="mt-5 text-lg font-bold text-slate-900">View Payslip</h4>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Check payment history, salary details, and downloadable payroll records.</p>
                    </a>

                    <a href="{{ route('employee.employeeCommunication') }}" class="group rounded-[1.5rem] border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-6 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg shadow-amber-500/20">
                            <i class="fa fa-users fa-2x"></i>
                        </div>
                        <h4 class="mt-5 text-lg font-bold text-slate-900">Team Directory</h4>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Find contacts, connect with teams, and reach out faster when you need support.</p>
                    </a>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div id="weekly-attendance-section" class="bg-white rounded-2xl border border-gray-200 p-4 md:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">This Week's Attendance</h3>
                        <span class="text-sm text-gray-500 font-medium">{{ $weeklyAttendanceRangeLabel ?? '-' }}</span>
                    </div>

                    <div class="space-y-6 max-h-96 overflow-y-auto pr-2">
                        @forelse (($weeklyAttendanceRows ?? []) as $weeklyRow)
                            <div>
                                <div class="grid grid-cols-12 items-start gap-4">
                                    <div class="col-span-2 mt-5 ml-8 relative w-12 h-12 text-green-600">
                                        <i class="fa fa-calendar-o fa-4x w-full h-full mt-[0px]"></i>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center text-xs font-bold mt-8 ml-2">
                                            <span>{{ $weeklyRow['day_short'] ?? '-' }}</span>
                                            <span>{{ $weeklyRow['day_number'] ?? '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="col-span-10 bg-gray-50 border border-gray-200 rounded-xl p-4 mr-2">
                                        <div class="flex items-center justify-between mb-3">
                                            <p class="text-xs text-gray-500">{{ $weeklyRow['date_label'] ?? '-' }}</p>
                                            <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $weeklyRow['status_class'] ?? 'bg-slate-100 text-slate-600' }}">
                                                {{ $weeklyRow['status'] ?? 'No Data' }}
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-12 items-center gap-4">
                                            <div class="col-span-6 space-y-2">
                                                <div class="flex items-center gap-3">
                                                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                                    <p class="text-sm text-gray-700">{{ $weeklyRow['morning_range'] ?? 'No Log' }}</p>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                                    <p class="text-sm text-gray-700">{{ $weeklyRow['afternoon_range'] ?? 'No Log' }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="col-span-6 space-y-2 text-right">
                                                <p class="text-xs text-gray-500">{{ $weeklyRow['morning_hours'] ?? '0 hrs worked' }}</p>
                                                <p class="text-xs text-gray-500">{{ $weeklyRow['afternoon_hours'] ?? '0 hrs worked' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No attendance records for this week.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[2rem] border border-slate-200 bg-white p-4 shadow-sm md:p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-700">Events</p>
                            <h3 class="mt-1 text-xl font-black text-slate-900">Upcoming Events</h3>
                        </div>
                        <span id="upcoming-events-range" class="rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-600">{{ now()->format('F Y') }}</span>
                    </div>

                    <ul id="upcoming-events-list" class="space-y-4 max-h-96 overflow-y-auto pr-2">
                        <li class="text-sm text-gray-500">Loading events...</li>
                    </ul>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
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

    (function () {
        const focusId = @json(request()->query('focus'));
        if (!focusId) return;
        const target = document.getElementById(focusId);
        if (!target) return;

        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        target.classList.add('ring-4', 'ring-emerald-300', 'ring-offset-4', 'ring-offset-slate-100', 'transition');

        setTimeout(() => {
            target.classList.remove('ring-4', 'ring-emerald-300', 'ring-offset-4', 'ring-offset-slate-100');
        }, 2200);
    })();

    (function updateThisMonthEventsCard() {
        const badgeEl = document.getElementById('month-events-badge');
        const captionEl = document.getElementById('month-events-caption');
        if (!badgeEl || !captionEl) return;

        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth();
        const monthLabel = now.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        captionEl.textContent = monthLabel;

        const readJson = (key) => {
            try {
                const raw = localStorage.getItem(key);
                if (!raw) return {};
                const parsed = JSON.parse(raw);
                return parsed && typeof parsed === 'object' ? parsed : {};
            } catch (error) {
                return {};
            }
        };

        const customEvents = readJson('school_custom_events_v1');
        const recurringEvents = readJson('school_recurring_events_v1');
        const recurringExamDays = readJson('school_recurring_exams_v1');
        const customHolidays = readJson('school_custom_holidays_v1');
        const recurringHolidays = readJson('school_recurring_holidays_v1');
        const hiddenSpecialEvents = readJson('hidden_special_events_v1');
        const hiddenOfficialHolidays = readJson('hidden_official_holidays_v1');

        const specialEventsByDate = {};
        const addSpecial = (date, label) => {
            if (!specialEventsByDate[date]) specialEventsByDate[date] = [];
            specialEventsByDate[date].push(label);
        };
        addSpecial(`${year}-02-14`, "Valentine's Day");
        addSpecial(`${year}-03-08`, "International Women's Day");
        addSpecial(`${year}-04-22`, 'Earth Day');
        addSpecial(`${year}-10-31`, 'Halloween');
        addSpecial(`${year}-11-01`, "All Saints' Day");
        addSpecial(`${year}-11-02`, "All Souls' Day");
        addSpecial(`${year}-12-24`, 'Christmas Eve');
        addSpecial(`${year}-12-31`, "New Year's Eve");
        const chineseNewYearByYear = {
            2024: '2024-02-10',
            2025: '2025-01-29',
            2026: '2026-02-17',
            2027: '2027-02-06',
            2028: '2028-01-26',
            2029: '2029-02-13',
            2030: '2030-02-03',
            2031: '2031-01-23',
            2032: '2032-02-11',
            2033: '2033-01-31',
            2034: '2034-02-19',
            2035: '2035-02-08',
        };
        if (chineseNewYearByYear[year]) {
            addSpecial(chineseNewYearByYear[year], 'Chinese New Year');
        }

        const dateInCurrentMonth = (dateText) => {
            const parsed = new Date(`${dateText}T00:00:00`);
            return !Number.isNaN(parsed.getTime()) && parsed.getFullYear() === year && parsed.getMonth() === month;
        };

        let totalEvents = 0;

        Object.entries(customEvents).forEach(([dateText, values]) => {
            if (dateInCurrentMonth(dateText) && Array.isArray(values)) totalEvents += values.length;
        });

        Object.entries(customHolidays).forEach(([dateText, values]) => {
            if (dateInCurrentMonth(dateText) && Array.isArray(values)) totalEvents += values.length;
        });

        Object.entries(specialEventsByDate).forEach(([dateText, values]) => {
            if (!dateInCurrentMonth(dateText) || !Array.isArray(values)) return;
            const hidden = Array.isArray(hiddenSpecialEvents[dateText]) ? hiddenSpecialEvents[dateText] : [];
            totalEvents += values.filter((name) => !hidden.includes(name)).length;
        });

        const monthDayPrefix = `${String(month + 1).padStart(2, '0')}-`;
        Object.entries(recurringEvents).forEach(([monthDay, values]) => {
            if (monthDay.startsWith(monthDayPrefix) && Array.isArray(values)) totalEvents += values.length;
        });
        Object.entries(recurringExamDays).forEach(([monthDay, values]) => {
            if (monthDay.startsWith(monthDayPrefix) && Array.isArray(values)) totalEvents += values.length;
        });
        Object.entries(recurringHolidays).forEach(([monthDay, values]) => {
            if (monthDay.startsWith(monthDayPrefix) && Array.isArray(values)) totalEvents += values.length;
        });

        fetch(`https://date.nager.at/api/v3/PublicHolidays/${year}/US`)
            .then((response) => (response.ok ? response.json() : []))
            .then((holidays) => {
                if (Array.isArray(holidays)) {
                    holidays.forEach((holiday) => {
                        const dateText = holiday?.date;
                        if (!dateText || !dateInCurrentMonth(dateText)) return;
                        const holidayName = holiday?.localName || holiday?.name || 'Holiday';
                        const hidden = Array.isArray(hiddenOfficialHolidays[dateText]) ? hiddenOfficialHolidays[dateText] : [];
                        if (!hidden.includes(holidayName)) {
                            totalEvents += 1;
                        }
                    });
                }
            })
            .catch(() => {
            })
            .finally(() => {
                badgeEl.textContent = `${totalEvents} Event${totalEvents === 1 ? '' : 's'}`;
            });
    })();

    (function updateUpcomingEventsCard() {
        const listEl = document.getElementById('upcoming-events-list');
        const rangeEl = document.getElementById('upcoming-events-range');
        if (!listEl || !rangeEl) return;

        const readJson = (key) => {
            try {
                const raw = localStorage.getItem(key);
                if (!raw) return {};
                const parsed = JSON.parse(raw);
                return parsed && typeof parsed === 'object' ? parsed : {};
            } catch (error) {
                return {};
            }
        };

        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth();
        const todayDate = new Date(year, month, now.getDate());
        rangeEl.textContent = now.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

        const customEvents = readJson('school_custom_events_v1');
        const recurringEvents = readJson('school_recurring_events_v1');
        const recurringExamDays = readJson('school_recurring_exams_v1');
        const customHolidays = readJson('school_custom_holidays_v1');
        const recurringHolidays = readJson('school_recurring_holidays_v1');
        const hiddenSpecialEvents = readJson('hidden_special_events_v1');
        const hiddenOfficialHolidays = readJson('hidden_official_holidays_v1');

        const parseIso = (iso) => {
            const d = new Date(`${iso}T00:00:00`);
            return Number.isNaN(d.getTime()) ? null : d;
        };

        const monthDays = new Date(year, month + 1, 0).getDate();
        const monthEvents = [];
        const pushEvent = (isoDate, title, type = 'event') => {
            const d = parseIso(isoDate);
            if (!d) return;
            if (d.getFullYear() !== year || d.getMonth() !== month) return;
            const isEnded = d < todayDate;
            monthEvents.push({ date: isoDate, title: String(title), ts: d.getTime(), isEnded, type });
        };

        Object.entries(customEvents).forEach(([date, values]) => {
            if (Array.isArray(values)) values.forEach((title) => pushEvent(date, title, 'school_event'));
        });
        Object.entries(customHolidays).forEach(([date, values]) => {
            if (Array.isArray(values)) values.forEach((title) => pushEvent(date, title, 'employee_holiday'));
        });

        for (let day = 1; day <= monthDays; day++) {
            const isoDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const monthDay = `${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const recurringList = Array.isArray(recurringEvents[monthDay]) ? recurringEvents[monthDay] : [];
            recurringList.forEach((title) => pushEvent(isoDate, title, 'school_event'));
            const examList = Array.isArray(recurringExamDays[monthDay]) ? recurringExamDays[monthDay] : [];
            examList.forEach((title) => pushEvent(isoDate, title, 'exam_day'));
            const recurringHolidayList = Array.isArray(recurringHolidays[monthDay]) ? recurringHolidays[monthDay] : [];
            recurringHolidayList.forEach((title) => pushEvent(isoDate, title, 'employee_holiday'));
        }

        const specialEventsByDate = {};
        const addSpecial = (date, label) => {
            if (!specialEventsByDate[date]) specialEventsByDate[date] = [];
            specialEventsByDate[date].push(label);
        };
        addSpecial(`${year}-02-14`, "Valentine's Day");
        addSpecial(`${year}-03-08`, "International Women's Day");
        addSpecial(`${year}-04-22`, 'Earth Day');
        addSpecial(`${year}-10-31`, 'Halloween');
        addSpecial(`${year}-11-01`, "All Saints' Day");
        addSpecial(`${year}-11-02`, "All Souls' Day");
        addSpecial(`${year}-12-24`, 'Christmas Eve');
        addSpecial(`${year}-12-31`, "New Year's Eve");
        const chineseNewYearByYear = {
            2024: '2024-02-10', 2025: '2025-01-29', 2026: '2026-02-17', 2027: '2027-02-06',
            2028: '2028-01-26', 2029: '2029-02-13', 2030: '2030-02-03', 2031: '2031-01-23',
            2032: '2032-02-11', 2033: '2033-01-31', 2034: '2034-02-19', 2035: '2035-02-08',
        };
        if (chineseNewYearByYear[year]) addSpecial(chineseNewYearByYear[year], 'Chinese New Year');

        Object.entries(specialEventsByDate).forEach(([date, values]) => {
            const hidden = Array.isArray(hiddenSpecialEvents[date]) ? hiddenSpecialEvents[date] : [];
            values.filter((name) => !hidden.includes(name)).forEach((name) => pushEvent(date, name, 'special_event'));
        });

        const render = (officialHolidayEvents) => {
            const allEvents = [...monthEvents, ...officialHolidayEvents]
                .sort((a, b) => {
                    if (a.isEnded !== b.isEnded) {
                        return a.isEnded ? 1 : -1;
                    }
                    return b.ts - a.ts || a.title.localeCompare(b.title);
                })
                .slice(0, 12);

            if (!allEvents.length) {
                listEl.innerHTML = '<li class="text-sm text-gray-500">No upcoming events this month.</li>';
                return;
            }

            listEl.innerHTML = allEvents.map((event) => {
                const eventDate = new Date(`${event.date}T00:00:00`);
                const label = eventDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                const dayDiff = Math.floor((eventDate - todayDate) / (1000 * 60 * 60 * 24));
                let relativeLabel = '';
                if (dayDiff === 0) {
                    relativeLabel = 'Today';
                } else if (dayDiff === 1) {
                    relativeLabel = 'Tomorrow';
                }
                const isToday = dayDiff === 0;
                const statusText = event.isEnded ? 'Ended' : (isToday ? 'Ongoing' : 'Upcoming');
                const statusClass = event.isEnded
                    ? 'bg-slate-100 text-slate-600'
                    : (isToday ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700');
                const iconClassByType = {
                    employee_holiday: 'bg-rose-100 text-rose-500 border-rose-500',
                    official_holiday: 'bg-rose-100 text-rose-500 border-rose-500',
                    special_event: 'bg-red-100 text-red-500 border-red-500',
                    school_event: 'bg-yellow-100 text-yellow-600 border-yellow-500',
                    exam_day: 'bg-emerald-100 text-emerald-600 border-emerald-500',
                };
                const iconClasses = iconClassByType[event.type] || 'bg-orange-100 text-orange-500 border-orange-500';
                return `<li class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-4">
                    <span class="w-10 h-10 ${iconClasses} flex items-center justify-center rounded-xl border">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <div class="flex flex-col flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-semibold text-gray-900">${event.title}</p>
                            <span class="px-2 py-0.5 rounded text-xs font-medium ${statusClass}">${statusText}</span>
                        </div>
                        <p class="text-sm text-gray-600">${label}${relativeLabel ? ` • ${relativeLabel}` : ''}</p>
                    </div>
                </li>`;
            }).join('');
        };

        fetch(`https://date.nager.at/api/v3/PublicHolidays/${year}/US`)
            .then((response) => (response.ok ? response.json() : []))
            .then((holidays) => {
                const officialHolidayEvents = [];
                if (Array.isArray(holidays)) {
                    holidays.forEach((holiday) => {
                        const dateText = holiday?.date;
                        if (!dateText) return;
                        const d = parseIso(dateText);
                        if (!d || d.getFullYear() !== year || d.getMonth() !== month) return;
                        const name = holiday?.localName || holiday?.name || 'Holiday';
                        const hidden = Array.isArray(hiddenOfficialHolidays[dateText]) ? hiddenOfficialHolidays[dateText] : [];
                        if (!hidden.includes(name)) {
                            officialHolidayEvents.push({
                                date: dateText,
                                title: String(name),
                                ts: d.getTime(),
                                isEnded: d < todayDate,
                                type: 'official_holiday',
                            });
                        }
                    });
                }
                render(officialHolidayEvents);
            })
            .catch(() => {
                render([]);
            });
    })();
</script>

</body>
</html>
