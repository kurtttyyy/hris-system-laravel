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
            margin-left: 4rem; /* w-16 when collapsed */
        }
        
        aside:hover ~ main {
            margin-left: 14rem; /* w-56 when expanded */
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
    @include('components.employeeHeader.dashboardHeader', ['name' => 'Kurt', 'notifications' => 5])


<div class="p-4 md:p-8 space-y-8 pt-20">
    <!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">      // Leave Balance Card
        <div class="relative bg-white rounded-2xl p-6 border border-gray-200">
            <span class="absolute top-9 right-4 bg-blue-500/20 text-black text-sm font-semibold px-2 py-1 rounded-lg backdrop-blur-sm">
                {{ (int) ($combinedLeavePercentUsed ?? 0) }}% Used
            </span>

            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-calendar fa-2x"></i>
            </div>
            <h3 class="text-4xl font-bold text-gray-900 mb-1 mt-7">
                {{ rtrim(rtrim(number_format((float) ($combinedLeaveAvailable ?? 0), 1, '.', ''), '0'), '.') }} Days
            </h3>
            <p class="text-gray-600 text-sm mb-4">Leave Balance</p>
            <p class="text-gray-500 text-xs">
                Vacation:
                {{ rtrim(rtrim(number_format((float) ($vacationCardAvailable ?? 0), 1, '.', ''), '0'), '.') }}
                / {{ rtrim(rtrim(number_format((float) ($annualLimit ?? 0), 1, '.', ''), '0'), '.') }}
            </p>
            <p class="text-gray-500 text-xs">
                Sick:
                {{ rtrim(rtrim(number_format((float) ($sickCardAvailable ?? 0), 1, '.', ''), '0'), '.') }}
                / {{ rtrim(rtrim(number_format((float) ($sickLimit ?? 0), 1, '.', ''), '0'), '.') }}
            </p>

            <div class="flex items-center gap-2 mt-4">
                <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ (int) ($combinedLeavePercentUsed ?? 0) }}%;"></div>
                </div>
                <span class="text-sm font-semibold text-gray-700">{{ (int) ($combinedLeavePercentUsed ?? 0) }}%</span>
            </div>
        </div>


    <!-- Attendance Card -->
        <div class="relative bg-white rounded-2xl p-6 border border-gray-200">
            <span class="absolute top-9 right-4 bg-green-500/20 text-green-900 text-sm font-semibold px-2 py-1 rounded-lg backdrop-blur-sm">
                {{ $attendanceStatusLabel ?? 'No Data' }}
            </span>

            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-clock-o fa-2x"></i>
            </div>
            <h3 class="text-4xl font-bold text-gray-900 mb-1 mt-7">
                {{ number_format((float) ($attendanceRatePercent ?? 0), 1) }}%
            </h3>
            <p class="text-gray-600 text-sm mb-1">Attendance Rate</p>
            <p class="text-gray-500 text-xs mt-4">
                {{ $attendanceMonthLabel ?? now()->format('F Y') }} {{ (int) ($attendancePresentDays ?? 0) }}/{{ (int) ($attendanceTotalDays ?? 0) }} days
            </p>
        </div>


        <!-- Events Card -->
        <div class="relative bg-white rounded-2xl p-6 border border-gray-200">
            <span id="month-events-badge" class="absolute top-9 right-4 bg-purple-500/20 text-purple-900 text-sm font-semibold px-2 py-1 rounded-lg backdrop-blur-sm">
                0 Events
            </span>

            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-calendar-o fa-2x"></i>
            </div>
            <h3 class="text-4xl font-bold text-gray-900 mb-1 mt-7">This Month</h3>
            <p class="text-gray-600 text-sm mb-1">Upcoming Events</p>
            <p id="month-events-caption" class="text-gray-500 text-xs mt-4">{{ now()->format('F Y') }}</p>
        </div>

        <!-- Salary Card -->
        <div class="relative bg-white rounded-2xl p-6 border border-gray-200">
            <span class="absolute top-9 right-4 bg-yellow-500/20 text-yellow-900 text-sm font-semibold px-2 py-1 rounded-lg backdrop-blur-sm">
                Paid
            </span>

            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-credit-card fa-2x"></i>
            </div>
            <h3 class="text-4xl font-bold text-gray-900 mb-1 mt-7">₱5,250</h3>
            <p class="text-gray-600 text-sm mb-1">Last Salary</p>
            <p class="text-gray-500 text-xs mt-4">Next Payment: Jan 31, 2025</p>
        </div>

</div>


<div class="p-4 md:p-8 space-y-6 bg-white rounded-2xl border border-gray-200 mx-4 md:mx-0">
    <!-- Quick Actions Container -->
    <div>
        <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h3>

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('employee.employeeLeave') }}" class="bg-white rounded-2xl border-2 border-gray-200 p-6 flex flex-col items-center justify-center gap-2 hover:shadow-md cursor-pointer">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center border-2 border-blue-500">
                    <i class="fa fa-calendar-check-o fa-2x"></i>
                </div>
                <p class="font-medium text-gray-700">Apply Leave</p>
            </a>

            <a href="{{ route('employee.employeeDocument') }}" class="bg-white rounded-2xl border-2 border-gray-200 p-6 flex flex-col items-center justify-center gap-2 hover:shadow-md cursor-pointer">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center border-2 border-green-500">
                    <i class="fa fa-clock-o fa-2x"></i>
                </div>
                <p class="font-medium text-gray-700">Documents</p>
            </a>

            <a href="{{ route('employee.employeePayslip') }}" class="bg-white rounded-2xl border-2 border-gray-200 p-6 flex flex-col items-center justify-center gap-2 hover:shadow-md cursor-pointer">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center border-2 border-purple-500">
                    <i class="fa fa-money fa-2x"></i>
                </div>
                <p class="font-medium text-gray-700">View Payslip</p>
            </a>

            <a href="{{ route('employee.employeeCommunication') }}" class="bg-white rounded-2xl border-2 border-gray-200 p-6 flex flex-col items-center justify-center gap-2 hover:shadow-md cursor-pointer">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center border-2 border-yellow-500">
                    <i class="fa fa-users fa-2x"></i>
                </div>
                <p class="font-medium text-gray-700">Team Directory</p>
            </a>
        </div>
    </div>
</div>


    <!-- Attendance + Upcoming Events Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 md:px-0">
        <!-- This Week's Attendance -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 md:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">This Week's Attendance</h3>
                <span class="text-sm text-gray-500 font-medium">{{ $weeklyAttendanceRangeLabel ?? '-' }}</span>
            </div>

            <div class="space-y-6 max-h-96 overflow-y-auto pr-2">
                @forelse (($weeklyAttendanceRows ?? []) as $weeklyRow)      // Loop through each day's attendance data for the week
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


<!-- Upcoming Events -->
<div class="bg-white border border-gray-200 rounded-2xl p-4 md:p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Upcoming Events</h3>
        <span id="upcoming-events-range" class="text-sm text-gray-500 font-medium">{{ now()->format('F Y') }}</span>
    </div>

    <ul id="upcoming-events-list" class="space-y-4 max-h-96 overflow-y-auto pr-2">
        <li class="text-sm text-gray-500">Loading events...</li>
    </ul>
</div>


    </div>
</div>

    </main>
</div>

<script>
    // Handle sidebar state changes and adjust layout
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

    (function updateThisMonthEventsCard() {
        const badgeEl = document.getElementById('month-events-badge');
        const captionEl = document.getElementById('month-events-caption');
        if (!badgeEl || !captionEl) return;

        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth(); // 0-based
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

        // Include official holidays like adminCalendar does, excluding hidden official holidays.
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
                        return a.isEnded ? 1 : -1; // upcoming first
                    }
                    return b.ts - a.ts || a.title.localeCompare(b.title); // higher day/date first
                })
                .slice(0, 12);

            if (!allEvents.length) {
                listEl.innerHTML = '<li class=\"text-sm text-gray-500\">No upcoming events this month.</li>';
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
                const statusText = event.isEnded ? 'Ended' : 'Upcoming';
                const statusClass = event.isEnded
                    ? 'bg-slate-100 text-slate-600'
                    : 'bg-green-100 text-green-700';
                const iconClassByType = {
                    employee_holiday: 'bg-rose-100 text-rose-500 border-rose-500',
                    official_holiday: 'bg-rose-100 text-rose-500 border-rose-500',
                    special_event: 'bg-red-100 text-red-500 border-red-500',
                    school_event: 'bg-yellow-100 text-yellow-600 border-yellow-500',
                    exam_day: 'bg-emerald-100 text-emerald-600 border-emerald-500',
                };
                const iconClasses = iconClassByType[event.type] || 'bg-orange-100 text-orange-500 border-orange-500';
                return `<li class=\"bg-white rounded-xl border-2 border-gray-200 p-4 hover:shadow-sm transition-shadow flex items-center gap-4\">
                    <span class=\"w-8 h-8 ${iconClasses} flex items-center justify-center rounded border-2\">
                        <i class=\"fa fa-calendar\"></i>
                    </span>
                    <div class=\"flex flex-col flex-1\">
                        <div class=\"flex items-center justify-between gap-2\">
                            <p class=\"font-semibold text-gray-900\">${event.title}</p>
                            <span class=\"px-2 py-0.5 rounded text-xs font-medium ${statusClass}\">${statusText}</span>
                        </div>
                        <p class=\"text-sm text-gray-600\">${label}${relativeLabel ? ` • ${relativeLabel}` : ''}</p>
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
