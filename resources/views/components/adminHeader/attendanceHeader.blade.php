@php
    $activeAttendanceTab = $activeAttendanceTab ?? 'all';
    $isAttendanceRoute = request()->routeIs([
        'admin.adminAttendance',
        'admin.attendance.present',
        'admin.attendance.absent',
        'admin.attendance.tardiness',
        'admin.attendance.totalEmployee',
    ]);
    $currentAttendanceRoute = match ($activeAttendanceTab) {
        'present' => 'admin.attendance.present',
        'absent' => 'admin.attendance.absent',
        'tardiness' => 'admin.attendance.tardiness',
        'total_employee' => 'admin.attendance.totalEmployee',
        default => 'admin.adminAttendance',
    };
    $defaultTitle = $isAttendanceRoute ? 'Daily Attendance' : 'Admin Workspace';
    $defaultSubtitle = $isAttendanceRoute
        ? 'Track uploads, scan logs, and review workforce presence from one place.'
        : 'Manage records, actions, and team operations from a cleaner workspace.';
    $headerTitle = $headerTitle ?? $defaultTitle;
    $headerSubtitle = $headerSubtitle ?? $defaultSubtitle;
    $headerBadge = $headerBadge ?? ($isAttendanceRoute ? 'Attendance Center' : 'Operations Hub');
    $headerSearchPlaceholder = $headerSearchPlaceholder ?? 'Search employee name';
    $currentViewLabel = match ($activeAttendanceTab) {
        'present' => 'Present',
        'absent' => 'Absent',
        'tardiness' => 'Tardiness',
        'total_employee' => 'All Employees',
        default => 'Overview',
    };
@endphp

<header class="sticky top-0 z-40 px-4 py-4 md:px-8 md:py-5">
    <div class="relative overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/90 shadow-[0_24px_55px_rgba(15,23,42,0.08)] backdrop-blur-xl">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.16),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(99,102,241,0.12),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(255,255,255,0.92))]"></div>
        <div class="absolute -left-8 top-6 h-28 w-28 rounded-full bg-sky-200/35 blur-3xl"></div>
        <div class="absolute right-0 top-0 h-36 w-36 translate-x-10 -translate-y-10 rounded-full bg-indigo-200/35 blur-3xl"></div>

        <div class="relative flex flex-col gap-5 px-5 py-5 md:px-7 md:py-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-3xl min-w-0">
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-sky-700">
                    <span class="h-2 w-2 rounded-full bg-sky-500"></span>
                    {{ $headerBadge }}
                </div>

                <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-900 md:text-4xl">{{ $headerTitle }}</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 md:text-base">{{ $headerSubtitle }}</p>

                <div class="mt-4 flex flex-wrap items-center gap-3 text-xs font-medium text-slate-500">
                    <span id="attendance-current-date" class="rounded-full border border-slate-200 bg-white/85 px-3 py-1.5">{{ now()->format('l, F j, Y') }}</span>
                    @if ($isAttendanceRoute)
                        <span class="rounded-full border border-slate-200 bg-white/85 px-3 py-1.5">View: {{ $currentViewLabel }}</span>
                        @if (!empty($fromDate))
                            <span class="rounded-full border border-slate-200 bg-white/85 px-3 py-1.5">
                                Range: {{ $fromDate }}@if (!empty($toDate)) to {{ $toDate }}@endif
                            </span>
                        @endif
                    @endif
                </div>

                @if ($isAttendanceRoute && $activeAttendanceTab !== 'all')
                    <a
                        href="{{ route('admin.adminAttendance') }}"
                        class="mt-4 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/90 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:text-sky-700"
                    >
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Back to Overview
                    </a>
                @endif
            </div>

            @if ($isAttendanceRoute)
                <div class="w-full xl:max-w-xl">
                    <form method="GET" action="{{ route($currentAttendanceRoute) }}" class="rounded-[1.75rem] border border-slate-200/80 bg-white/90 p-4 shadow-[0_16px_34px_rgba(15,23,42,0.07)] backdrop-blur">
                        @if (!empty($fromDate))
                            <input type="hidden" name="from_date" value="{{ $fromDate }}">
                        @endif
                        @if (!empty($toDate))
                            <input type="hidden" name="to_date" value="{{ $toDate }}">
                        @endif
                        @if (!empty($selectedUploadId))
                            <input type="hidden" name="upload_id" value="{{ $selectedUploadId }}">
                        @endif
                        @if (!empty($selectedJobType))
                            <input type="hidden" name="job_type" value="{{ $selectedJobType }}">
                        @endif

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <label class="group relative flex flex-1 items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition focus-within:border-sky-300 focus-within:bg-white focus-within:shadow-sm">
                                <i class="fa-solid fa-magnifying-glass text-slate-400 transition group-focus-within:text-sky-600"></i>
                                <input
                                    type="text"
                                    name="search_name"
                                    value="{{ $searchName ?? '' }}"
                                    placeholder="{{ $headerSearchPlaceholder }}"
                                    class="w-full bg-transparent pl-3 pr-2 text-sm text-slate-700 outline-none placeholder:text-slate-400"
                                />
                            </label>

                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                                <i class="fa-solid fa-arrow-right-long text-xs"></i>
                                Search
                            </button>

                            @if (!empty($searchName))
                                <a
                                    href="{{ route($currentAttendanceRoute, array_filter([
                                        'from_date' => $fromDate ?? null,
                                        'to_date' => $toDate ?? null,
                                        'upload_id' => $selectedUploadId ?? null,
                                        'job_type' => $selectedJobType ?? null,
                                    ], fn ($value) => !is_null($value) && $value !== '')) }}"
                                    class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:text-slate-900"
                                >
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</header>

<script>
  (function () {
    const dateEl = document.getElementById('attendance-current-date');
    if (!dateEl) {
      return;
    }

    const updateDate = () => {
      const now = new Date();
      const formatted = now.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      });
      dateEl.textContent = formatted;
    };

    updateDate();
    setInterval(updateDate, 60000);
  })();
</script>
