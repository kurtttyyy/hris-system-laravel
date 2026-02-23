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
     @include('components.adminHeader.reportsHeader')

    <!-- Dashboard Content -->
    <div class="p-4 md:p-8 space-y-6 pt-20">
<!-- Page Title -->


<!-- Section Header -->
<div class="flex items-center justify-between mb-6">
  <div class="flex items-center gap-3">
    <i class="fa-solid fa-chart-line text-emerald-400 text-lg"></i>
    <h2 class="text-xl font-semibold text-gray-600">
      Key Performance Indicators
    </h2>
  </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <!-- Total Employees -->
    <div class="bg-gradient-to-br from-blue-600 to-blue-500 rounded-xl p-5 text-white shadow-lg">
        <p class="text-sm opacity-80">Total Employees</p>
        <h2 class="text-3xl font-bold mt-1">1,245</h2>
        <p class="text-xs mt-1 opacity-80">Active Staff</p>
        <div class="mt-4 text-right">
            <i class="fa-solid fa-users text-white/70"></i>
        </div>
    </div>

    <!-- Attendance Rate -->
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-500 rounded-xl p-5 text-white shadow-lg">
        <p class="text-sm opacity-80">Attendance Rate</p>
        <h2 class="text-3xl font-bold mt-1">94.2%</h2>
        <p class="text-xs mt-1 opacity-80">This Month</p>
        <div class="mt-4 text-right">
            <i class="fa-solid fa-circle-check text-white/70"></i>
        </div>
    </div>

    <!-- Turnover Rate -->
    <div class="bg-gradient-to-br from-orange-600 to-orange-500 rounded-xl p-5 text-white shadow-lg">
        <p class="text-sm opacity-80">Turnover Rate</p>
        <h2 class="text-3xl font-bold mt-1">8.3%</h2>
        <p class="text-xs mt-1 opacity-80">Year-to-Date</p>
        <div class="mt-4 text-right">
            <i class="fa-solid fa-bolt text-white/70"></i>
        </div>
    </div>

    <!-- Open Positions -->
    <div class="bg-gradient-to-br from-purple-600 to-purple-500 rounded-xl p-5 text-white shadow-lg">
        <p class="text-sm opacity-80">Open Positions</p>
        <h2 class="text-3xl font-bold mt-1">23</h2>
        <p class="text-xs mt-1 opacity-80">Recruiting</p>
        <div class="mt-4 text-right">
            <i class="fa-solid fa-plus text-white/70"></i>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="flex items-center justify-between">
    <div class="flex gap-3">
        <button class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2">
            <i class="fa-solid fa-rotate"></i> Refresh Data
        </button>
        <button class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </button>
    </div>

    <div class="flex gap-3">
        <select class="bg-slate-800 text-white text-sm rounded-lg px-3 py-2">
            <option>Attendance Report</option>
        </select>
        <select class="bg-slate-800 text-white text-sm rounded-lg px-3 py-2">
            <option>This Week</option>
        </select>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Attendance Trend -->
<div class="bg-slate-800 rounded-xl p-6 shadow">

    <!-- Header + Filters -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-10">
        <h3 class="text-white font-semibold text-lg">Attendance Trend</h3>

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-3">
            <!-- From -->
            <div class="flex items-center gap-2 bg-slate-700 px-3 py-2 rounded-lg">
                <i class="fa-solid fa-calendar text-slate-400 text-sm"></i>
                <input
                    type="date"
                    value="2026-12-01"
                    class="bg-transparent text-slate-300 text-sm outline-none"
                />
            </div>

            <!-- Quick Range -->
            <select class="bg-slate-700 text-slate-300 text-sm rounded-lg px-3 py-2 outline-none">
                <option>This Week</option>
                <option>Last Week</option>
                <option>This Month</option>
                <option selected>Custom</option>
            </select>
        </div>
    </div>

    <!-- Chart -->
    <div class="grid grid-cols-7 gap-12">

        <!-- Sun -->
        <div class="flex flex-col items-center">
            <div class="h-40 w-full flex items-end relative">
                <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-xs text-emerald-400 font-medium">70%</span>
                <div class="w-full bg-emerald-500 rounded-md h-[70%] transition-all duration-700"></div>
            </div>
            <span class="text-xs text-slate-400 mt-2">Sun</span>
        </div>

        <!-- Mon -->
        <div class="flex flex-col items-center">
            <div class="h-40 w-full flex items-end relative">
                <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-xs text-emerald-400 font-medium">85%</span>
                <div class="w-full bg-emerald-500 rounded-md h-[85%] transition-all duration-700"></div>
            </div>
            <span class="text-xs text-slate-400 mt-2">Mon</span>
        </div>

        <!-- Tue -->
        <div class="flex flex-col items-center">
            <div class="h-40 w-full flex items-end relative">
                <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-xs text-emerald-400 font-medium">75%</span>
                <div class="w-full bg-emerald-500 rounded-md h-[75%] transition-all duration-700"></div>
            </div>
            <span class="text-xs text-slate-400 mt-2">Tue</span>
        </div>

        <!-- Wed -->
        <div class="flex flex-col items-center">
            <div class="h-40 w-full flex items-end relative">
                <span class="absolute -top-6 text-xs font-semibold text-emerald-400 left-1/2 -translate-x-1/2">
                    80%
                </span>
                <div class="w-full bg-emerald-500 rounded-md h-[80%] transition-all duration-700"></div>
            </div>
            <span class="text-xs text-slate-400 mt-2">Wed</span>
        </div>


        <!-- Thu -->
        <div class="flex flex-col items-center">
            <div class="h-40 w-full flex items-end relative">
                <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-xs text-emerald-400 font-medium">
                    65%
                </span>
                <div class="w-full bg-emerald-500 rounded-md h-[65%] transition-all duration-700"></div>
            </div>
            <span class="text-xs text-slate-400 mt-2">Thu</span>
        </div>

        <!-- Fri -->
        <div class="flex flex-col items-center">
            <div class="h-40 w-full flex items-end relative">
                <span class="absolute -top-6 text-xs text-orange-400 font-medium left-1/2 -translate-x-1/2">45%</span>
                <div class="w-full bg-orange-500 rounded-md h-[45%] transition-all duration-700"></div>
            </div>
            <span class="text-xs text-slate-400 mt-2">Fri</span>
        </div>

        <!-- Sat -->
        <div class="flex flex-col items-center">
            <div class="h-40 w-full flex items-end relative">
                <span class="absolute -top-6 text-xs text-orange-400 font-medium left-1/2 -translate-x-1/2">35%</span>
                <div class="w-full bg-orange-500 rounded-md h-[35%] transition-all duration-700"></div>
            </div>
            <span class="text-xs text-slate-400 mt-2">Sat</span>
        </div>

    </div>

    <!-- Footer -->
    <p class="text-xs text-slate-400 mt-6">
        Average: <span class="text-white font-semibold">65.0%</span>
    </p>
</div>


    <!-- Employee Distribution -->
    <div class="bg-slate-800 rounded-xl p-6 shadow">
        <h3 class="text-white font-semibold mb-4">Employee Distribution</h3>

        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm text-slate-300">
                    <span>Engineering</span><span>435</span>
                </div>
                <div class="w-full bg-slate-700 rounded-full h-2 mt-1">
                    <div class="bg-blue-500 h-2 rounded-full w-[70%]"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm text-slate-300">
                    <span>Sales</span><span>348</span>
                </div>
                <div class="w-full bg-slate-700 rounded-full h-2 mt-1">
                    <div class="bg-emerald-500 h-2 rounded-full w-[55%]"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm text-slate-300">
                    <span>HR & Operations</span><span>224</span>
                </div>
                <div class="w-full bg-slate-700 rounded-full h-2 mt-1">
                    <div class="bg-purple-500 h-2 rounded-full w-[40%]"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm text-slate-300">
                    <span>Marketing</span><span>149</span>
                </div>
                <div class="w-full bg-slate-700 rounded-full h-2 mt-1">
                    <div class="bg-orange-500 h-2 rounded-full w-[30%]"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm text-slate-300">
                    <span>Support</span><span>89</span>
                </div>
                <div class="w-full bg-slate-700 rounded-full h-2 mt-1">
                    <div class="bg-pink-500 h-2 rounded-full w-[20%]"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Employee Performance -->
<!-- Section Header -->
<div class="flex items-center justify-between mb-6">
  <div class="flex items-center gap-3">
    <i class="fa-solid fa-chart-line text-emerald-400 text-lg"></i>
    <h2 class="text-xl font-semibold text-gray-600">
      Employee Performance
    </h2>
  </div>
</div>

  <!-- KPI Cards -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <!-- High Performers -->
    <div class="bg-emerald-600/90 rounded-xl p-5 text-white shadow-lg">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-sm opacity-80">High Performers</p>
          <h2 class="text-3xl font-bold">342</h2>
          <p class="text-xs opacity-80">27% of workforce</p>
        </div>
        <div class="bg-white/20 p-3 rounded-lg">
          <i class="fa-solid fa-bolt"></i>
        </div>
      </div>
    </div>

    <!-- Average Performers -->
    <div class="bg-blue-600/90 rounded-xl p-5 text-white shadow-lg">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-sm opacity-80">Average Performers</p>
          <h2 class="text-3xl font-bold">723</h2>
          <p class="text-xs opacity-80">58% of workforce</p>
        </div>
        <div class="bg-white/20 p-3 rounded-lg">
          <i class="fa-solid fa-chart-simple"></i>
        </div>
      </div>
    </div>

    <!-- Below Average -->
    <div class="bg-orange-600/90 rounded-xl p-5 text-white shadow-lg">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-sm opacity-80">Below Average</p>
          <h2 class="text-3xl font-bold">156</h2>
          <p class="text-xs opacity-80">13% of workforce</p>
        </div>
        <div class="bg-white/20 p-3 rounded-lg">
          <i class="fa-solid fa-arrow-down"></i>
        </div>
      </div>
    </div>

    <!-- Needs Improvement -->
    <div class="bg-red-600/90 rounded-xl p-5 text-white shadow-lg">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-sm opacity-80">Needs Improvement</p>
          <h2 class="text-3xl font-bold">24</h2>
          <p class="text-xs opacity-80">2% of workforce</p>
        </div>
        <div class="bg-white/20 p-3 rounded-lg">
          <i class="fa-solid fa-plus"></i>
        </div>
      </div>
    </div>

  </div>

  <!-- Bottom Panels -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Top Performers -->
<div class="bg-slate-800 rounded-xl p-6 shadow">
  <h3 class="text-white font-semibold mb-5">Top Departments</h3>

  <div class="space-y-5">

    <!-- Engineering -->
    <div>
      <div class="flex justify-between items-center mb-1">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-full bg-emerald-500 flex items-center justify-center text-white text-sm font-bold">
            ENG
          </div>
          <div>
            <p class="text-sm text-white font-medium">Engineering</p>
            <p class="text-xs text-slate-400">Product & Platform Team</p>
          </div>
        </div>
        <span class="text-xs text-emerald-400 font-semibold">92%</span>
      </div>
      <div class="w-full bg-slate-700 rounded-full h-2">
        <div class="bg-emerald-500 h-2 rounded-full w-[92%]"></div>
      </div>
    </div>

    <!-- Sales -->
    <div>
      <div class="flex justify-between items-center mb-1">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold">
            SAL
          </div>
          <div>
            <p class="text-sm text-white font-medium">Sales</p>
            <p class="text-xs text-slate-400">Domestic & Enterprise</p>
          </div>
        </div>
        <span class="text-xs text-emerald-400 font-semibold">89%</span>
      </div>
      <div class="w-full bg-slate-700 rounded-full h-2">
        <div class="bg-emerald-500 h-2 rounded-full w-[89%]"></div>
      </div>
    </div>

    <!-- Operations -->
    <div>
      <div class="flex justify-between items-center mb-1">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-full bg-purple-500 flex items-center justify-center text-white text-sm font-bold">
            OPS
          </div>
          <div>
            <p class="text-sm text-white font-medium">Operations</p>
            <p class="text-xs text-slate-400">HR & Administration</p>
          </div>
        </div>
        <span class="text-xs text-emerald-400 font-semibold">87%</span>
      </div>
      <div class="w-full bg-slate-700 rounded-full h-2">
        <div class="bg-emerald-500 h-2 rounded-full w-[87%]"></div>
      </div>
    </div>

    <!-- Support -->
    <div>
      <div class="flex justify-between items-center mb-1">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-full bg-pink-500 flex items-center justify-center text-white text-sm font-bold">
            SUP
          </div>
          <div>
            <p class="text-sm text-white font-medium">Customer Support</p>
            <p class="text-xs text-slate-400">Helpdesk & Success</p>
          </div>
        </div>
        <span class="text-xs text-emerald-400 font-semibold">85%</span>
      </div>
      <div class="w-full bg-slate-700 rounded-full h-2">
        <div class="bg-emerald-500 h-2 rounded-full w-[85%]"></div>
      </div>
    </div>

  </div>
</div>


    <!-- Performance Improvement Plans -->
<div class="bg-slate-800 rounded-xl p-6 shadow">
  <h3 class="text-white font-semibold mb-5">Departments Needing Attention</h3>

  <div class="space-y-4">

    <!-- Marketing -->
    <div class="flex justify-between items-center bg-slate-700/40 p-4 rounded-lg">
      <div>
        <p class="text-sm text-white font-medium">Marketing</p>
        <p class="text-xs text-slate-400">Campaign & Content Team</p>
        <p class="text-xs text-orange-400 mt-1">
          ⚠ Attendance below target · 2 weeks
        </p>
      </div>
      <span class="text-xs bg-orange-500/20 text-orange-400 px-2 py-1 rounded">
        52%
      </span>
    </div>

    <!-- Finance -->
    <div class="flex justify-between items-center bg-slate-700/40 p-4 rounded-lg">
      <div>
        <p class="text-sm text-white font-medium">Finance</p>
        <p class="text-xs text-slate-400">Accounts & Payroll</p>
        <p class="text-xs text-orange-400 mt-1">
          ⚠ Repeated late check-ins
        </p>
      </div>
      <span class="text-xs bg-orange-500/20 text-orange-400 px-2 py-1 rounded">
        58%
      </span>
    </div>

    <!-- Logistics -->
    <div class="flex justify-between items-center bg-red-900/30 p-4 rounded-lg">
      <div>
        <p class="text-sm text-white font-medium">Logistics</p>
        <p class="text-xs text-slate-400">Warehouse & Transport</p>
        <p class="text-xs text-red-400 mt-1">
          ✖ Critical absenteeism · Review scheduled
        </p>
      </div>
      <span class="text-xs bg-red-500/20 text-red-400 px-2 py-1 rounded">
        34%
      </span>
    </div>

  </div>
</div>
  </div>


  <!-- Section Header -->
<div class="flex items-center justify-between mb-6">
  <div class="flex items-center gap-3">
    <i class="fa-solid fa-triangle-exclamation text-yellow-400 text-lg"></i>
    <h2 class="text-xl font-semibold text-gray-600">
      Reports & Alerts
    </h2>
  </div>
</div>

<!-- Reports & Alerts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

  <!-- Recent Reports -->
  <div class="bg-slate-800 rounded-xl shadow">
    <div class="px-6 py-4 border-b border-slate-700">
      <h3 class="text-white font-semibold">Recent Reports</h3>
    </div>

    <div class="divide-y divide-slate-700">

      <!-- Item -->
      <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-700/40 transition">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400">
            <i class="fa-solid fa-file-lines"></i>
          </div>
          <div>
            <p class="text-sm font-medium text-white">
              Monthly Attendance Report
            </p>
            <p class="text-xs text-slate-400">
              Generated today at 10:30 AM
            </p>
          </div>
        </div>
        <i class="fa-solid fa-chevron-right text-slate-500 text-sm"></i>
      </div>

      <!-- Item -->
      <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-700/40 transition">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400">
            <i class="fa-solid fa-chart-simple"></i>
          </div>
          <div>
            <p class="text-sm font-medium text-white">
              Department Performance Report
            </p>
            <p class="text-xs text-slate-400">
              Generated yesterday at 3:15 PM
            </p>
          </div>
        </div>
        <i class="fa-solid fa-chevron-right text-slate-500 text-sm"></i>
      </div>

      <!-- Item -->
      <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-700/40 transition">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-orange-500/20 flex items-center justify-center text-orange-400">
            <i class="fa-solid fa-rotate"></i>
          </div>
          <div>
            <p class="text-sm font-medium text-white">
              Turnover Analysis Report
            </p>
            <p class="text-xs text-slate-400">
              Generated 2 days ago
            </p>
          </div>
        </div>
        <i class="fa-solid fa-chevron-right text-slate-500 text-sm"></i>
      </div>

    </div>
  </div>

  <!-- Key Alerts & Insights -->
  <div class="bg-slate-800 rounded-xl shadow">
    <div class="px-6 py-4 border-b border-slate-700">
      <h3 class="text-white font-semibold">Key Alerts & Insights</h3>
    </div>

    <div class="divide-y divide-slate-700">

      <!-- Alert -->
      <div class="flex gap-4 px-6 py-4 bg-yellow-500/10">
        <div class="w-9 h-9 rounded-lg bg-yellow-500/20 flex items-center justify-center text-yellow-400">
          <i class="fa-solid fa-trophy"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-white">
            Low Attendance Alert
          </p>
          <p class="text-xs text-slate-300">
            3 employees below 85% attendance threshold this month
          </p>
        </div>
      </div>

      <!-- Insight -->
      <div class="flex gap-4 px-6 py-4 bg-emerald-500/10">
        <div class="w-9 h-9 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400">
          <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-white">
            Performance Milestone
          </p>
          <p class="text-xs text-slate-300">
            Engineering department exceeded Q1 targets by 12%
          </p>
        </div>
      </div>

      <!-- Alert -->
      <div class="flex gap-4 px-6 py-4 bg-red-500/10">
        <div class="w-9 h-9 rounded-lg bg-red-500/20 flex items-center justify-center text-red-400">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-white">
            High Turnover Notice
          </p>
          <p class="text-xs text-slate-300">
            Sales department turnover increased to 12% (industry avg: 8%)
          </p>
        </div>
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
