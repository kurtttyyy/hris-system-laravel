@php
  $isHiringRoute = request()->routeIs('admin.adminApplicant')
    || request()->routeIs('admin.adminPosition')
    || request()->routeIs('admin.adminInterview');
@endphp

<style>
  [x-cloak]{display:none !important;}
  details.hiring-menu > summary { list-style: none; }
  details.hiring-menu > summary::-webkit-details-marker { display: none; }
  details.hiring-menu[open] .hiring-chevron { transform: rotate(180deg); }
</style>

<aside class="group fixed left-0 top-0 h-screen bg-slate-900 text-slate-200 flex flex-col w-20 hover:w-64 transition-all duration-300 overflow-hidden z-50" x-data>
  <div class="px-4 py-4 flex items-center gap-3 border-b border-slate-800">
    <div class="flex items-center justify-center">
      <!-- Small square icon visible when collapsed -->
      <img src="{{ asset('images/logo.webp') }}" alt="HR Logo" class="w-8 h-8 object-contain block group-hover:hidden">
      <!-- Full logo visible when sidebar expanded -->
      <img src="{{ asset('images/logo.webp') }}" alt="HR Logo Full" class="hidden group-hover:block h-16">
    </div>
    <span class="text-lg font-semibold inline-block whitespace-nowrap max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300 ml-2">HR PORTAL</span>
  </div>

  <nav class="flex-1 px-4 py-6 space-y-2">

    <!-- Dashboard -->
    <a href="{{ route('admin.adminHome') }}"
       class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition justify-center group-hover:justify-start
       {{ request()->routeIs('admin.adminHome')
        ? 'bg-green-600 text-white'
        : 'text-white hover:bg-green-600/30' }}">
      <i class="fa-solid fa-house"></i>
      <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Dashboard</span>
    </a>

    <!-- Employees -->
    <a href="{{ route('admin.adminEmployee') }}"
       class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition justify-center group-hover:justify-start
       {{ request()->routeIs('admin.adminEmployee')
        ? 'bg-green-600 text-white'
        : 'text-white hover:bg-green-600/30' }}">
      <i class="fa-solid fa-users"></i>
      <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Employees</span>
    </a>

    <!-- Attendance -->
    <a href="{{ route('admin.adminAttendance') }}"
       class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition justify-center group-hover:justify-start
       {{ request()->routeIs('admin.adminAttendance')
        ? 'bg-green-600 text-white'
        : 'text-white hover:bg-green-600/30' }}">
      <i class="fa-solid fa-calendar-check"></i>
      <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Attendance</span>
    </a>

    <!-- Calendar -->
    <a href="{{ route('admin.adminCalendar') }}"
       class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition justify-center group-hover:justify-start
       {{ request()->routeIs('admin.adminCalendar')
        ? 'bg-green-600 text-white'
        : 'text-white hover:bg-green-600/30' }}">
      <i class="fa-solid fa-calendar-days"></i>
      <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Calendar</span>
    </a>

    <!-- Leave -->
    <a href="{{ route('admin.adminLeaveManagement') }}"
       class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition justify-center group-hover:justify-start
       {{ request()->routeIs('admin.adminLeaveManagement')
        ? 'bg-green-600 text-white'
        : 'text-white hover:bg-green-600/30' }}">
      <i class="fa-solid fa-clipboard"></i>
      <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Leave Management</span>
    </a>

    <!-- ✅ Hiring Dropdown (FIXED) -->
    <details class="space-y-1 hiring-menu" {{ $isHiringRoute ? 'open' : '' }}>
      <summary
        class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg font-medium transition text-white hover:bg-green-600/30 cursor-pointer"
      >
        <span class="flex items-center gap-3 justify-center group-hover:justify-start">
          <i class="fa-solid fa-briefcase"></i>
          <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Hiring</span>
        </span>

        <i class="fa-solid fa-chevron-down transition-transform duration-200 hiring-chevron"></i>
      </summary>

      <!-- Submenu -->
      <div class="ml-8 space-y-1">

        <a href="{{ route('admin.adminApplicant') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-md text-sm justify-center group-hover:justify-start
           {{ request()->routeIs('admin.adminApplicant')
                ? 'bg-green-600 text-white'
                : 'text-white hover:bg-green-600/30' }}">
          <i class="fa-solid fa-user-check"></i>
          <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Applicant</span>
        </a>

        <a href="{{ route('admin.adminPosition') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-md text-sm justify-center group-hover:justify-start
           {{ request()->routeIs('admin.adminPosition')
                ? 'bg-green-600 text-white'
                : 'text-white hover:bg-green-600/30' }}">
          <i class="fa-solid fa-briefcase"></i>
          <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Job Position</span>
        </a>

        <a href="{{ route('admin.adminInterview') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-md text-sm justify-center group-hover:justify-start
           {{ request()->routeIs('admin.adminInterview')
                ? 'bg-green-600 text-white'
                : 'text-white hover:bg-green-600/30' }}">
          <i class="fa-solid fa-comments"></i>
          <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Interview</span>
        </a>

      </div>
    </details>

    <!-- Reports -->
    <a href="{{ route('admin.adminReports') }}"
       class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition justify-center group-hover:justify-start
       {{ request()->routeIs('admin.adminReports')
        ? 'bg-green-600 text-white'
        : 'text-white hover:bg-green-600/30' }}">
      <i class="fa-solid fa-chart-line"></i>
      <span class="whitespace-nowrap inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">Reports</span>
    </a>

  </nav>

  <!-- Profile -->
  <div class="px-6 py-4 border-t border-slate-800 flex items-center gap-3 justify-center group-hover:justify-start">
    <div class="w-9 h-9 rounded-full bg-emerald-500 flex items-center justify-center text-white font-semibold">AS</div>
    <div class="text-sm inline-block max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300">
      <p class="font-medium">Admin Sarah</p>
      <p class="text-slate-400">HR Manager</p>
    </div>
  </div>
</aside>
