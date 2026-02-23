<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PeopleHub – Job Details</title>

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

    <!-- Dashboard Content -->
    <div class="p-4 md:p-8 space-y-6 pt-20">

      <!-- Back -->
      <a  href="{{ route('admin.adminPosition') }}" class="text-sm text-slate-500 flex items-center gap-2">
        <i class="fa fa-arrow-left"></i> Back to Jobs
      </a>
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT COLUMN -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Job Header -->
          <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex justify-between items-start">

              <div class="flex gap-4">
                <div class="w-12 h-12 bg-indigo-600 text-white rounded-xl flex items-center justify-center text-xl">
                  <i class="fa fa-code"></i>
                </div>

                <div class="items-start">
                  <h1 class="text-2xl font-bold text-slate-800">{{ $open->title }}</h1>
                  <p class="text-sm text-slate-500">
                    {{ $open->department }} � {{ $open->employment }} � {{ $open->job_type }}
                  </p>

                  <div class="flex gap-3 mt-4">
                    <a href="{{ route('admin.adminEditPosition', $open->id) }}"
                      class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center">
                        <i class="fa fa-pen mr-1"></i> Edit Job
                    </a>
                <form action="{{ route('admin.destroyPosition', $open->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                     class="border border-red-300 text-red-500 px-4 py-2 rounded-lg text-sm">
                        Close Position
                    </button>
                </form>
                  </div>
                </div>
              </div>

              <span class="text-xs bg-green-100 text-green-600 px-3 py-1 rounded-full">
                Active
              </span>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 text-center mt-8 border-t pt-6">
              <div>
                <p class="text-2xl font-bold">{{ $countApplication }}</p>
                <p class="text-xs text-slate-500">Total Applicants</p>
              </div>
              <div>
                <p class="text-2xl font-bold">8</p>
                <p class="text-xs text-slate-500">In Review</p>
              </div>
              <div>
                <p class="text-2xl font-bold">{{ $open->created_at->format('M. j, Y') }}</p>
                <p class="text-xs text-slate-500">Posted</p>
              </div>
            </div>
          </div>

          <!-- Job Description -->
          <div class="bg-white rounded-xl p-6 shadow-sm space-y-6">

                <div class="[&_ul]:list-none [&_ul]:pl-0 [&_ul]:ml-0 [&_li]:pl-0 [&_li]:ml-0">
                  <h2 class="font-bold mb-2">Job Description</h2>

                    @php
                        $lines = preg_split("/\r\n|\n|\r/", $open->job_description);
                    @endphp

                    <ul class="text-m text-slate-600">
                        @foreach ($lines as $line)
                            <li>{{ ltrim($line, "•- ") }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="[&_ul]:list-none [&_ul]:pl-0 [&_ul]:ml-0 [&_li]:pl-0 [&_li]:ml-0">
                  <h2 class="font-bold mb-2">Responsibilities</h2>

                    @php
                        $lines = preg_split("/\r\n|\n|\r/", $open->responsibilities);
                    @endphp

                    <ul class="text-m text-slate-600">
                        @foreach ($lines as $line)
                            <li><i class="fa fa-check-circle text-indigo-500 mr-2"></i>{{ ltrim($line, "•- ") }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="[&_ul]:list-none [&_ul]:pl-0 [&_ul]:ml-0 [&_li]:pl-0 [&_li]:ml-0">
                  <h2 class="font-bold mb-2">Requirements</h2>

                    @php
                        $lines = preg_split("/\r\n|\n|\r/", $open->requirements);
                    @endphp

                    <ul class="text-m text-slate-600">
                        @foreach ($lines as $line)
                            <li><i class="fa fa-check-circle text-indigo-500 mr-2"></i>{{ ltrim($line, "•- ") }}</li>
                        @endforeach
                    </ul>
                </div>

          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="space-y-6">

          <!-- Job Details -->
          <div class="bg-white rounded-xl p-6 shadow-sm text-sm space-y-4">
            <h3 class="font-semibold">Job Details</h3>

            <div>
              <p class="text-slate-400">Experience Level</p>
              <p class="font-medium">{{ $open->experience_level }}</p>
            </div>

            <div>
              <p class="text-slate-400">Location</p>
              <p class="font-medium">{{ $open->work_mode }} ({{ $open->location }})</p>
            </div>

            <div>
              <p class="text-slate-400">Posted Date</p>
              <p class="font-medium">{{ \Carbon\Carbon::parse($open->one)->format('F j, Y') }}</p>
            </div>

            <div>
              <p class="text-slate-400">Closing Date</p>
              <p class="font-medium">{{ \Carbon\Carbon::parse($open->two)->format('F j, Y') }}</p>
            </div>
          </div>

          <!-- Skills -->
          <div class="bg-white rounded-xl p-6 shadow-sm">
              <h3 class="font-semibold mb-3">Required Skills</h3>

              <div class="flex gap-2 flex-wrap">
                  @foreach (explode(',', $open->skills) as $skill)
                      <span class="px-3 py-1 text-xs bg-indigo-100 text-indigo-600 rounded-full">
                          {{ trim($skill) }}
                      </span>
                  @endforeach
              </div>
          </div>


          <!-- Benefits -->
          <div class="bg-white rounded-xl p-6 shadow-sm text-sm">
                <div class="[&_ul]:list-none [&_ul]:pl-0 [&_ul]:ml-0 [&_li]:pl-0 [&_li]:ml-0">
                  <h2 class="font-bold mb-2">Benefits & Perks</h2>

                    @php
                        $lines = preg_split("/\r\n|\n|\r/", $open->benifits);
                    @endphp

                    <ul class="text-m text-slate-600">
                        @foreach ($lines as $line)
                            <li><i class="fa fa-check text-green-500 mr-2"></i>{{ ltrim($line, "•- ") }}</li>
                        @endforeach
                    </ul>
                </div>
          </div>


          <!-- Hiring Team -->
          <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold mb-4">Hiring Team</h3>
            @foreach($admin as $team)
            <div class="flex items-center gap-3 mb-3">
              <div class="w-10 h-10 bg-indigo-500 text-white rounded-full flex items-center justify-center">JD</div>
              <div>
                <p class="text-sm font-medium">{{ $team->first_name}} {{ $team->last_name}}</p>
                <p class="text-xs text-slate-500">{{$team->job_role}}</p>
              </div>
            </div>
            @endforeach
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
</html>

