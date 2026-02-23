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
     @include('components.adminHeader.attendanceHeader')

    <!-- Dashboard Content -->
    <div class="p-4 md:p-8 space-y-6 pt-20">

<div class="p-6 max-w-7xl mx-auto">

  <!-- Header -->
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Add New Position</h1>
    <button class="px-4 py-2 rounded-lg border text-slate-600 hover:bg-slate-200">
      Cancel
    </button>
  </div>

  <!-- FORM START -->
  <form action="{{ route('admin.createPositionStore') }}" method="POST">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- LEFT SIDE -->
      <div class="lg:col-span-2 space-y-6">

        <!-- Job Overview -->
        <div class="bg-white rounded-xl shadow p-6">
          <h2 class="font-semibold text-lg mb-4">Job Overview</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input class="input" placeholder="Job Title" name="title">
            <input class="input" placeholder="College Name" name="collage_name">
            <input class="input" placeholder="Department" name="department">
            <select class="input" name="employment">
              <option>Employment Type</option>
              <option value="Full-Time">Full-Time</option>
              <option value="Part-Time">Part-Time</option>
            </select>

            <select class="input" name="mode">
              <option>Work Mode</option>
              <option value="Remote">Remote</option>
              <option value="Onsite">Onsite</option>
              <option value="Hybrid">Hybrid</option>
            </select>
          </div>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-xl shadow p-6">
          <h2 class="font-semibold text-lg mb-4">Job Description</h2>
          <textarea
            rows="6"
            name="description"
            class="input resize-none bullet-textarea"
            placeholder="• Describe the position"
          ></textarea>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
          <h2 class="font-semibold text-lg mb-4">Passionate</h2>
          <textarea
            rows="6"
            name="passionate"
            class="input resize-none bullet-textarea"
            placeholder="• Describe the passionate"
          ></textarea>
        </div>

        <!-- Responsibilities -->
        <div class="bg-white rounded-xl shadow p-6">
          <h2 class="font-semibold text-lg mb-4">Responsibilities</h2>
          <textarea
            rows="5"
            name="responsibilities"
            class="input resize-none bullet-textarea"
            placeholder="• Build UI components"
          ></textarea>
        </div>

        <!-- Requirements -->
        <div class="bg-white rounded-xl shadow p-6">
          <h2 class="font-semibold text-lg mb-4">Requirements</h2>
          <textarea
            rows="5"
            name="requirements"
            class="input resize-none bullet-textarea"
            placeholder="• 5+ years experience"
          ></textarea>
        </div>

      </div>

      <!-- RIGHT SIDE -->
      <div class="space-y-6">

        <!-- Job Details -->
<div class="bg-white rounded-xl shadow p-6">
  <h2 class="font-semibold text-lg mb-4">Job Details</h2>

        <div class="space-y-3">

          <select class="input" name="level">
            <option>Experience Level</option>
            <option value="Junior">Junior</option>
            <option value="Mid">Mid</option>
            <option value="Senior">Senior</option>
          </select>

          <select class="input" name="job_type">
            <option>Job Type</option>
            <option value="Teaching">Teaching</option>
            <option value="Non-Teaching">Non-Teaching</option>
          </select>

          <input class="input" placeholder="Location" name="location">

          <div>
            <label class="block text-sm text-gray-600 mb-1">Start Date</label>
            <input type="date" class="input" name="start_date">
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">Close Date</label>
            <input type="date" class="input" name="end_date">
          </div>

        </div>
      </div>


        <!-- Skills -->
        <div class="bg-white rounded-xl shadow p-6">
          <h2 class="font-semibold text-lg mb-4">Required Skills</h2>
          <input class="input" placeholder="Type skill and press Enter" name="skills">
        </div>

        <!-- Benefits -->
        <div class="bg-white rounded-xl shadow p-6">
          <h2 class="font-semibold text-lg mb-4">Benefits & Perks</h2>
          <textarea
            rows="4"
            name="benefits"
            class="input resize-none bullet-textarea"
            placeholder="• Health Insurance"
          ></textarea>
        </div>

        <!-- Submit -->
        <button type="submit"
          class="w-full py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
          Create Position
        </button>

      </div>
    </div>
  </form>
</div>


    </div>
  </main>
</div>

</body>
<!-- Styles -->
<style>
.input {
  width: 100%;
  border: 1px solid #cbd5e1;
  border-radius: 0.75rem;
  padding: 0.5rem 1rem;
}
.input:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 2px rgb(99 102 241 / 30%);
}
</style>

<!-- Bullet Logic -->
<script>
  const bullet = '• ';

  document.querySelectorAll('.bullet-textarea').forEach(textarea => {

    textarea.addEventListener('focus', () => {
      if (textarea.value.trim() === '') {
        textarea.value = bullet;
      }
    });

    textarea.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        const start = this.selectionStart;
        this.value =
          this.value.substring(0, start) + '\n' + bullet +
          this.value.substring(this.selectionEnd);
        this.selectionStart = this.selectionEnd =
          start + bullet.length + 1;
      }
    });
  });
</script>

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
</html>
