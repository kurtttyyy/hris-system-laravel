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
     @include('components.adminHeader.positionHeader')

    <!-- Dashboard Content -->
    <div class="p-4 md:p-8 space-y-6 pt-20">

<!-- Page Actions Only -->
<div class="flex items-center justify-between">
    <!-- Empty placeholder to keep alignment -->
    <div></div>

    <div class="flex items-center gap-4">
        <div class="relative">
            <i class="fa fa-search absolute left-3 top-3 text-slate-400 text-sm"></i>
            <input
                type="text"
                placeholder="Search applicants..."
                class="pl-9 pr-4 py-2 border rounded-lg text-sm
                       focus:ring-2 focus:ring-indigo-500 outline-none"
            />
        </div>

        <button
            onclick="window.location.href='/system/create/position'"
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700"
        >
            + Add Position
        </button>
    </div>
</div>


<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl p-6 shadow-sm">
        <div class="flex justify-between items-center">
            <span class="text-sm text-slate-500">Open Positions</span>
            <span class="text-xs bg-indigo-100 text-indigo-600 px-2 py-1 rounded-full">Active</span>
        </div>
        <p class="text-3xl font-bold mt-4">{{ $positionCounts }}</p>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm">
        <div class="flex justify-between items-center">
            <span class="text-sm text-slate-500">Total Views</span>
            <span class="text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded-full">+24%</span>
        </div>
        <p class="text-3xl font-bold mt-4">{{ $logs }}</p>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm">
        <div class="flex justify-between items-center">
            <span class="text-sm text-slate-500">New Applications</span>
            <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full">This Week</span>
        </div>
        <p class="text-3xl font-bold mt-4">{{ $applicantCounts }}</p>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm">
        <div class="flex justify-between items-center">
            <span class="text-sm text-slate-500">Days to Fill</span>
            <span class="text-xs bg-orange-100 text-orange-600 px-2 py-1 rounded-full">Avg</span>
        </div>
        <p class="text-3xl font-bold mt-4">14</p>
    </div>
</div>

<!-- Job Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach($openPosition as $open)
    <!-- Frontend Job -->
    <div class="bg-white rounded-xl p-6 shadow-sm">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-lg font-semibold">{{ $open->title}}</h3>
                <p class="text-sm text-slate-500">{{ $open->department }}
                    • {{ $open->employment}} • {{ $open->job_type }}</p>
            </div>
            <span class="text-xs bg-green-100 text-green-600 px-3 py-1 rounded-full">Active</span>
        </div>

                    @php
                        $lines = preg_split("/\r\n|\n|\r/", $open->job_description);
                    @endphp

                    <ul class="text-slate-600 text-sm mt-4">
                        @foreach (array_slice($lines, 0, 3) as $line)
                            <li>
                                {{
                                    Str::limit(
                                        ltrim($line, "•- "),
                                        150,
                                        '......'
                                    )
                                }}
                            </li>
                        @endforeach
                    </ul>

        <div class="flex gap-2 mt-4 flex-wrap">
            @foreach (explode(',', $open->skills) as $skill)
                <span class="px-3 py-1 text-xs bg-indigo-100 text-indigo-600 rounded-full">
                    {{ trim($skill) }}
                </span>
            @endforeach
        </div>


        <div class="flex justify-between items-center mt-6">
            <span class="text-xs text-slate-500">
                <i class="fa fa-users mr-1"></i> {{ $open->applicants_count }} Applicants • Posted {{ $open->created_at->format('m/d/y') }}
            </span>

            <div class="flex gap-2">
                <button onclick="window.location.href='{{ route('admin.adminShowPosition', $open->id) }}'" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">View Details</button>
                <button onclick="window.location.href='{{ route('admin.adminEditPosition', $open->id) }}'" class="border px-4 py-2 rounded-lg text-sm">Edit</button>
            </div>
        </div>
    </div>
    @endforeach
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
</html>

