<header class="bg-white border-b border-gray-200 sticky top-0 z-40 px-4 md:px-8 py-4 md:py-6 flex items-center justify-between backdrop-blur-sm">
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
    @endphp
    <div>
        <h2 class="text-3xl font-bold text-gray-900">Daily Attendance</h2>
        <p id="attendance-current-date" class="text-gray-600 mt-1">{{ now()->format('l, F j, Y') }}</p>
        @if ($activeAttendanceTab !== 'all')
            <a
                href="{{ route('admin.adminAttendance') }}"
                class="mt-3 inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                <i class=""></i>
                Back
            </a>
        @endif
    </div>

    @if ($isAttendanceRoute)
        <form method="GET" action="{{ route($currentAttendanceRoute) }}" class="flex items-center gap-2">
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
            <input
                type="text"
                name="search_name"
                value="{{ $searchName ?? '' }}"
                placeholder="Search employee name"
                class="w-56 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button type="submit" class="rounded-lg bg-slate-700 px-3 py-2 text-xs font-medium text-white transition hover:bg-slate-800">
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
                    class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Clear
                </a>
            @endif
        </form>
    @endif
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
