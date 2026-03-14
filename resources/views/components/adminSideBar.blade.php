@php
  $isHiringRoute = request()->routeIs('admin.adminApplicant')
    || request()->routeIs('admin.adminPosition')
    || request()->routeIs('admin.adminInterview');
  $adminUser = auth()->user();
  $nameParts = array_filter([
    trim((string) ($adminUser->first_name ?? '')),
    trim((string) ($adminUser->middle_name ?? '')),
    trim((string) ($adminUser->last_name ?? '')),
  ]);
  $adminDisplayName = count($nameParts) ? implode(' ', $nameParts) : (string) ($adminUser->email ?? 'Admin');
  $initialA = strtoupper(substr(trim((string) ($adminUser->first_name ?? 'A')), 0, 1));
  $initialB = strtoupper(substr(trim((string) ($adminUser->last_name ?? '')), 0, 1));
  $adminInitials = trim($initialA.$initialB);
  if ($adminInitials === '') {
    $adminInitials = 'AD';
  }
  $adminRoleLabel = trim((string) ($adminUser->role ?? 'Admin'));
@endphp

<style>
  [x-cloak]{display:none !important;}
  .admin-sidebar {
    background: linear-gradient(180deg, #0f172a 0%, #0b1533 52%, #08112b 100%);
    box-shadow: 8px 0 24px rgba(2, 6, 23, 0.35);
  }
  .admin-sidebar nav a,
  .admin-sidebar nav summary {
    min-height: 44px;
  }
  .admin-sidebar nav a > i,
  .admin-sidebar nav summary > span > i {
    width: 1.25rem;
    text-align: center;
    flex-shrink: 0;
  }
  details.hiring-menu > summary { list-style: none; }
  details.hiring-menu > summary::-webkit-details-marker { display: none; }
  details.hiring-menu[open] .hiring-chevron { transform: rotate(180deg); }
  details.more-menu > summary { list-style: none; }
  details.more-menu > summary::-webkit-details-marker { display: none; }
  details.more-menu[open] .more-chevron { transform: rotate(180deg); }
  details.matrix-menu > summary { list-style: none; }
  details.matrix-menu > summary::-webkit-details-marker { display: none; }
  details.matrix-menu[open] .matrix-chevron { transform: rotate(90deg); }
</style>

<aside class="admin-sidebar group fixed left-0 top-0 h-screen text-slate-200 flex flex-col w-16 hover:w-72 transition-all duration-300 overflow-x-hidden overflow-y-auto z-50" x-data>
  <div class="px-3 py-4 flex items-center gap-3 border-b border-slate-800/90">
    <div class="flex items-center justify-center">
      <!-- Small square icon visible when collapsed -->
      <img src="{{ asset('images/logo.webp') }}" alt="HR Logo" class="w-8 h-8 object-contain block group-hover:hidden">
      <!-- Full logo visible when sidebar expanded -->
      <img src="{{ asset('images/logo.webp') }}" alt="HR Logo Full" class="hidden group-hover:block h-16">
    </div>
    <span class="text-lg font-semibold inline-block whitespace-nowrap max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300 ml-2">HR PORTAL</span>
  </div>

  <nav class="flex-1 px-2 group-hover:px-3 py-4 space-y-1.5">

    <!-- Dashboard -->
    <a href="{{ route('admin.adminHome') }}"
       class="flex items-center gap-0 group-hover:gap-3 px-4 py-2.5 rounded-lg font-medium transition justify-center group-hover:justify-start
       {{ request()->routeIs('admin.adminHome')
        ? 'bg-green-600 text-white'
        : 'text-white hover:bg-green-600/30' }}">
      <i class="fa-solid fa-house"></i>
      <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Dashboard</span>
    </a>

    <!-- Employees -->
    <a href="{{ route('admin.adminEmployee') }}"
       class="flex items-center gap-0 group-hover:gap-3 px-4 py-2.5 rounded-lg font-medium transition justify-center group-hover:justify-start
       {{ request()->routeIs('admin.adminEmployee')
        ? 'bg-green-600 text-white'
        : 'text-white hover:bg-green-600/30' }}">
      <i class="fa-solid fa-users"></i>
      <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Employees</span>
    </a>

    <!-- Attendance -->
    <a href="{{ route('admin.adminAttendance') }}"
       class="flex items-center gap-0 group-hover:gap-3 px-4 py-2.5 rounded-lg font-medium transition justify-center group-hover:justify-start
       {{ request()->routeIs('admin.adminAttendance')
        ? 'bg-green-600 text-white'
        : 'text-white hover:bg-green-600/30' }}">
      <i class="fa-solid fa-calendar-check"></i>
      <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Attendance</span>
    </a>

    <!-- Leave -->
    <a href="{{ route('admin.adminLeaveManagement') }}"
       class="flex items-center gap-0 group-hover:gap-3 px-4 py-2.5 rounded-lg font-medium transition justify-center group-hover:justify-start
       {{ request()->routeIs('admin.adminLeaveManagement')
        ? 'bg-green-600 text-white'
        : 'text-white hover:bg-green-600/30' }}">
      <i class="fa-solid fa-clipboard"></i>
      <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Leave Management</span>
    </a>

    <!-- ✅ Hiring Dropdown (FIXED) -->
    <!-- Payslip -->
    <a href="{{ route('admin.adminPayslip') }}"
       class="flex items-center gap-0 group-hover:gap-3 px-4 py-2.5 rounded-lg font-medium transition justify-center group-hover:justify-start
       {{ request()->routeIs('admin.adminPayslip')
        ? 'bg-green-600 text-white'
        : 'text-white hover:bg-green-600/30' }}">
      <i class="fa-solid fa-file-invoice-dollar"></i>
      <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Payslip</span>
    </a>

    <details class="space-y-1 hiring-menu" {{ $isHiringRoute ? 'open' : '' }}>
      <summary
        class="w-full flex items-center justify-center group-hover:justify-between px-4 py-2.5 rounded-lg font-medium transition text-white hover:bg-green-600/30 cursor-pointer"
      >
        <span class="flex items-center gap-0 group-hover:gap-3 justify-center group-hover:justify-start">
          <i class="fa-solid fa-briefcase"></i>
          <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Hiring</span>
        </span>

        <i class="fa-solid fa-chevron-down hidden group-hover:inline-block transition-all duration-200 hiring-chevron"></i>
      </summary>

      <!-- Submenu -->
      <div class="ml-0 group-hover:ml-8 space-y-1">

        <a href="{{ route('admin.adminApplicant') }}"
           class="flex items-center gap-0 group-hover:gap-2 px-4 py-2 rounded-md text-sm justify-center group-hover:justify-start
           {{ request()->routeIs('admin.adminApplicant')
                ? 'bg-green-600 text-white'
                : 'text-white hover:bg-green-600/30' }}">
          <i class="fa-solid fa-user-check"></i>
          <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Applicant</span>
        </a>

        <a href="{{ route('admin.adminPosition') }}"
           class="flex items-center gap-0 group-hover:gap-2 px-4 py-2 rounded-md text-sm justify-center group-hover:justify-start
           {{ request()->routeIs('admin.adminPosition')
                ? 'bg-green-600 text-white'
                : 'text-white hover:bg-green-600/30' }}">
          <i class="fa-solid fa-briefcase"></i>
          <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Job Position</span>
        </a>

        <a href="{{ route('admin.adminInterview') }}"
           class="flex items-center gap-0 group-hover:gap-2 px-4 py-2 rounded-md text-sm justify-center group-hover:justify-start
           {{ request()->routeIs('admin.adminInterview')
                ? 'bg-green-600 text-white'
                : 'text-white hover:bg-green-600/30' }}">
          <i class="fa-solid fa-comments"></i>
          <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Interview</span>
        </a>

      </div>
    </details>

    @if (false)
      <!-- Reports hidden temporarily -->
      <a href="{{ route('admin.adminReports') }}"
         class="flex items-center gap-0 group-hover:gap-3 px-4 py-2.5 rounded-lg font-medium transition justify-center group-hover:justify-start
         {{ request()->routeIs('admin.adminReports')
          ? 'bg-green-600 text-white'
          : 'text-white hover:bg-green-600/30' }}">
        <i class="fa-solid fa-chart-line"></i>
        <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Reports</span>
      </a>
    @endif

    <!-- See More -->
    <details class="space-y-1 more-menu">
      <summary
        class="w-full flex items-center justify-center group-hover:justify-between px-4 py-2.5 rounded-lg font-medium transition text-white hover:bg-green-600/30 cursor-pointer"
      >
        <span class="flex items-center gap-0 group-hover:gap-3 justify-center group-hover:justify-start">
          <i class="fa-solid fa-ellipsis"></i>
          <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">More</span>
        </span>
        <i class="fa-solid fa-chevron-down hidden group-hover:inline-block transition-all duration-200 more-chevron"></i>
      </summary>

      <div class="ml-0 group-hover:ml-8 space-y-1">
        <a href="{{ route('admin.adminCalendar') }}"
           class="flex items-center gap-0 group-hover:gap-2 px-4 py-2 rounded-md text-sm justify-center group-hover:justify-start
           {{ request()->routeIs('admin.adminCalendar')
                ? 'bg-green-600 text-white'
                : 'text-white hover:bg-green-600/30' }}">
          <i class="fa-solid fa-calendar-days"></i>
          <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Calendar</span>
        </a>

        <details class="space-y-1 matrix-menu">
          <summary
            class="w-full flex items-center justify-center group-hover:justify-between px-4 py-2 rounded-md text-sm transition text-white hover:bg-green-600/30 cursor-pointer"
          >
            <span class="flex items-center gap-0 group-hover:gap-2 justify-center group-hover:justify-start">
              <i class="fa-solid fa-folder"></i>
              <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Matrix</span>
            </span>
            <i class="fa-solid fa-chevron-right hidden group-hover:inline-block transition-all duration-200 matrix-chevron"></i>
          </summary>

          <div class="ml-0 group-hover:ml-6 space-y-1">
            <a href="{{ route('admin.schoolAdministrator') }}"
               class="flex items-center gap-0 group-hover:gap-2 px-4 py-2 rounded-md text-sm justify-center group-hover:justify-start
               {{ request()->routeIs('admin.schoolAdministrator')
                    ? 'bg-green-600 text-white'
                    : 'text-white hover:bg-green-600/30' }}">
              <i class="fa-regular fa-file"></i>
              <span class="hidden group-hover:block leading-tight break-words">School Administrator</span>
            </a>

            <a href="{{ route('admin.nonTeachingMatrix') }}"
               class="flex items-center gap-0 group-hover:gap-2 px-4 py-2 rounded-md text-sm justify-center group-hover:justify-start
               {{ request()->routeIs('admin.nonTeachingMatrix')
                    ? 'bg-green-600 text-white'
                    : 'text-white hover:bg-green-600/30' }}">
              <i class="fa-regular fa-file"></i>
              <span class="hidden group-hover:block leading-tight break-words">Academic Non-Teaching</span>
            </a>

            <a href="{{ route('admin.teachingMatrix') }}"
               class="flex items-center gap-0 group-hover:gap-2 px-4 py-2 rounded-md text-sm justify-center group-hover:justify-start
               {{ request()->routeIs('admin.teachingMatrix')
                    ? 'bg-green-600 text-white'
                    : 'text-white hover:bg-green-600/30' }}">
              <i class="fa-regular fa-file"></i>
              <span class="hidden group-hover:block leading-tight break-words">Academic Staff / Teaching</span>
            </a>
          </div>
        </details>

        <a href="{{ route('admin.adminResignations') }}"
           class="flex items-center gap-0 group-hover:gap-2 px-4 py-2 rounded-md text-sm justify-center group-hover:justify-start
           {{ request()->routeIs('admin.adminResignations')
                ? 'bg-green-600 text-white'
                : 'text-white hover:bg-green-600/30' }}">
          <i class="fa-solid fa-user-minus"></i>
          <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Resignations</span>
        </a>

      </div>
    </details>

  </nav>

  <!-- Profile -->
  <div class="px-3 group-hover:px-6 py-4 border-t border-slate-800/90 flex items-center gap-3 justify-center group-hover:justify-start">
    <div class="w-9 h-9 min-w-9 min-h-9 max-w-9 max-h-9 shrink-0 rounded-full bg-emerald-500 flex items-center justify-center text-white font-semibold leading-none">{{ $adminInitials }}</div>
    <div class="text-sm inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">
      <div class="flex items-center gap-2">
        <p class="font-medium truncate">{{ $adminDisplayName }}</p>
        <form method="POST" action="{{ route('logout') }}" class="inline-flex">
          @csrf
          <button
            type="submit"
            class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-slate-700 text-slate-200 hover:border-red-500 hover:bg-red-600 hover:text-white"
            title="Logout"
            aria-label="Logout"
          >
            <i class="fa-solid fa-right-from-bracket"></i>
          </button>
        </form>
      </div>
      <p class="text-slate-400">{{ $adminRoleLabel }}</p>
    </div>
  </div>
</aside>
