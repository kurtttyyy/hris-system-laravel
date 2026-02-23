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
            <!-- Dashboard Content -->
            <div class="p-8 space-y-6">


        <!-- Card -->
        <form class="space-y-6" action="{{ route('admin.updatePosition', $open->id) }}" method="POST">
            @csrf
        <div class="bg-white rounded-xl shadow-sm p-8 max-w-6xl mx-auto">

            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-slate-800">
                Edit Job Posting
            </h1>

            <div class="flex gap-3">
            <a href="{{ route('admin.adminShowPosition', $open->id) }}"
                class="px-5 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 inline-block text-center">
                Cancel
            </a>


                <button type = "submit"
                class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                Save Changes
                </button>
            </div>
            </div>

            <!-- Section -->
            <h2 class="text-lg font-semibold text-slate-800 mb-6">
            Basic Information
            </h2>

            <!-- Job Title -->
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">
                Job Title
                </label>
                <input type="text" name = "title"
                    value="{{ $open->title }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3
                    focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- College Name -->
                <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">
                    College Name
                </label>
                <input type="text" name ="collage_name"
                        value="{{ $open->collage_name }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Department -->
                <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">
                    Department
                </label>
                <input type="text" name="department"
                        value="{{ old('department', $open->department) }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Employment Type -->
                <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">
                    Employment Type
                </label>
                <select name="employment"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3">
                    <option value="">Select employment type</option>
                    <option value="Full-Time"
                        {{ old('employment', $open->employment) == 'Full-Time' ? 'selected' : '' }}>
                        Full-Time
                    </option>
                    <option value="Part-Time"
                        {{ old('employment', $open->employment) == 'Part-Time' ? 'selected' : '' }}>
                        Part-Time
                    </option>
                    <option value="Contract"
                        {{ old('employment', $open->employment) == 'Contract' ? 'selected' : '' }}>
                        Contract
                    </option>
                </select>

                </div>

                <!-- Job Type -->
                <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">
                    Job Type
                </label>
                <select name="job_type"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3">
                    <option value="">Select job type</option>
                    <option value="Teaching"
                        {{ old('job_type', $open->job_type) == 'Teaching' ? 'selected' : '' }}>
                        Teaching
                    </option>
                    <option value="Non-Teaching"
                        {{ old('job_type', $open->job_type) == 'Non-Teaching' ? 'selected' : '' }}>
                        Non-Teaching
                    </option>
                </select>
                </div>

                <!-- Location -->
                <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">
                    Location
                </label>
                <input type="text" name ="location"
                        value="{{ $open->location }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Experience Level -->
                <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">
                    Experience Level
                </label>
                <select
                    name="experience_level"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3
                        focus:ring-2 focus:ring-indigo-500">
                    <option value="">Select experience level</option>
                    <option value="Senior Level"
                        {{ old('experience_level', $open->experience_level) == 'Senior Level' ? 'selected' : '' }}>
                        Senior Level
                    </option>
                    <option value="Mid Level"
                        {{ old('experience_level', $open->experience_level) == 'Mid Level' ? 'selected' : '' }}>
                        Mid Level
                    </option>
                    <option value="Junior Level"
                        {{ old('experience_level', $open->experience_level) == 'Junior Level' ? 'selected' : '' }}>
                        Junior Level
                    </option>
                </select>

                </div>
            </div>

            <!-- Job Description -->
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">
                Job Description
                </label>
                <textarea rows="5" name ="job_description"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                    {{ old('job_description', $open->job_description) }}
                </textarea>
            </div>
                <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Summary
                </label>
                <div class="w-full rounded-lg border border-gray-300 bg-white p-4 text-gray-800">
                    <textarea rows="5" name ="passionate"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                        {{ old('passionate', $open->passionate) }}
                    </textarea>
                </div>
            </div>

            <!-- Key Responsibilities -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Key Responsibilities
                </label>
                <div class="w-full rounded-lg border border-gray-300 bg-white p-4">
                    <textarea rows="5" name ="responsibilities"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                         {{ old('responsibilities', $open->responsibilities) }}
                    </textarea>
                </div>
            </div>

            <!-- Requirements -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Requirements
                </label>
                <div class="w-full rounded-lg border border-gray-300 bg-white p-4">
                    <textarea rows="5" name ="requirements"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                        {{ old('requirements', $open->requirements) }}
                    </textarea>
                </div>
            </div>

                <!-- Required Skills -->
            <div class="mb-10">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">
                    Required Skills
                </h2>

                <label class="block text-sm font-medium text-gray-600 mb-2">
                    Skills (comma separated)
                </label>

                <input
                    type="text" name ="skills"
                    value="{{ $open->skills }}"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-800
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                />
            </div>

            <!-- Posting Dates -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-6">
                    Posting Dates
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Posted Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Posted Date
                        </label>

                        <div class="relative">
                            <input
                                type="date"
                                name="one"
                                 value="{{ old('one', $open->one ? \Carbon\Carbon::parse($open->one)->format('Y-m-d') : '') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 pr-10 text-gray-800
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            />


                            <!-- Calendar Icon -->
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Closing Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Closing Date
                        </label>

                        <div class="relative">
                            <input
                                type="date"
                                name="two"
                                 value="{{ old('two', $open->two ? \Carbon\Carbon::parse($open->two)->format('Y-m-d') : '') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 pr-10 text-gray-800
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            />


                            <!-- Calendar Icon -->
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        </form>

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
