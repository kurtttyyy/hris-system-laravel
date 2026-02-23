<!-- Performance Tab -->
<div x-show="tab === 'performance'" x-transition class="p-6 space-y-6">

  <!-- Metric Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">

    <!-- Overall Rating -->
    <div class="bg-blue-600 text-white rounded-xl p-5 flex flex-col gap-2">
      <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
        </svg>
        <span class="text-sm font-semibold">Overall Rating</span>
      </div>
      <div class="text-3xl font-bold">4.5</div>
    </div>

    <!-- Projects Completed -->
    <div class="bg-green-600 text-white rounded-xl p-5 flex flex-col gap-2">
      <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m3 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm font-semibold">Projects Completed</span>
      </div>
      <div class="text-3xl font-bold">24</div>
    </div>

    <!-- Attendance Rate -->
    <div class="bg-purple-600 text-white rounded-xl p-5 flex flex-col gap-2">
      <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm font-semibold">Attendance Rate</span>
      </div>
      <div class="text-3xl font-bold">98%</div>
    </div>

    <!-- Achievements -->
    <div class="bg-orange-600 text-white rounded-xl p-5 flex flex-col gap-2">
      <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l2.122 6.517a1 1 0 00.95.69h6.853c.969 0 1.371 1.24.588 1.81l-5.54 4.034a1 1 0 00-.364 1.118l2.122 6.517c.3.921-.755 1.688-1.538 1.118l-5.54-4.034a1 1 0 00-1.176 0l-5.54 4.034c-.783.57-1.838-.197-1.538-1.118l2.122-6.517a1 1 0 00-.364-1.118L2.44 11.944c-.783-.57-.38-1.81.588-1.81h6.853a1 1 0 00.95-.69l2.122-6.517z" />
        </svg>
        <span class="text-sm font-semibold">Achievements</span>
      </div>
      <div class="text-3xl font-bold">15</div>
    </div>

  </div>

  <!-- Performance Reviews Section -->
  <section class="bg-white rounded-xl p-6 shadow-sm space-y-6">

    <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2-8v12a2 2 0 01-2 2H7a2 2 0 01-2-2V8a2 2 0 012-2h3l2-2 2 2h3a2 2 0 012 2z" />
      </svg>
      Performance Reviews
    </h3>

    <!-- Review 1 -->
    <article class="border border-gray-200 rounded-lg p-4 space-y-2">
      <header class="flex justify-between items-center">
        <div>
          <h4 class="font-semibold">Q4 2024 Review</h4>
          <time class="text-xs text-gray-500">December 15, 2024</time>
        </div>
        <span class="text-xs bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">Excellent</span>
      </header>
      <p class="text-gray-700 text-sm">
        Consistently exceeded expectations. Strong leadership in project delivery and excellent collaboration with team members.
      </p>
      <div class="text-xs text-gray-500 flex gap-4">
        <span>Technical Skills: <strong>5/5</strong></span>
        <span>Communication: <strong>4.5/5</strong></span>
        <span>Teamwork: <strong>5/5</strong></span>
      </div>
    </article>

    <!-- Review 2 -->
    <article class="border border-gray-200 rounded-lg p-4 space-y-2">
      <header class="flex justify-between items-center">
        <div>
          <h4 class="font-semibold">Q3 2024 Review</h4>
          <time class="text-xs text-gray-500">September 15, 2024</time>
        </div>
        <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">Good</span>
      </header>
      <p class="text-gray-700 text-sm">
        Met all objectives and demonstrated strong problem-solving abilities. Continues to develop leadership skills.
      </p>
      <div class="text-xs text-gray-500 flex gap-4">
        <span>Technical Skills: <strong>4.5/5</strong></span>
        <span>Communication: <strong>4/5</strong></span>
        <span>Teamwork: <strong>4.5/5</strong></span>
      </div>
    </article>

    <!-- Review 3 -->
    <article class="border border-gray-200 rounded-lg p-4 space-y-2">
      <header class="flex justify-between items-center">
        <div>
          <h4 class="font-semibold">Q2 2024 Review</h4>
          <time class="text-xs text-gray-500">June 15, 2024</time>
        </div>
        <span class="text-xs bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">Excellent</span>
      </header>
      <p class="text-gray-700 text-sm">
        Outstanding performance across all metrics. Successfully led multiple high-priority projects to completion.
      </p>
      <div class="text-xs text-gray-500 flex gap-4">
        <span>Technical Skills: <strong>5/5</strong></span>
        <span>Communication: <strong>4.5/5</strong></span>
        <span>Teamwork: <strong>5/5</strong></span>
      </div>
    </article>

  </section>

  <!-- Goals & Objectives Section -->
  <section class="bg-white rounded-xl p-6 shadow-sm space-y-5">

    <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2-8v12a2 2 0 01-2 2H7a2 2 0 01-2-2V8a2 2 0 012-2h3l2-2 2 2h3a2 2 0 012 2z" />
      </svg>
      Goals & Objectives (2024)
    </h3>

    <!-- Goal 1 -->
    <div>
      <div class="flex justify-between mb-1">
        <span class="text-sm font-medium text-gray-700">Lead 3 major projects to completion</span>
        <span class="text-sm font-medium text-gray-700">100%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-3">
        <div class="bg-green-600 h-3 rounded-full" style="width: 100%"></div>
      </div>
    </div>

    <!-- Goal 2 -->
    <div>
      <div class="flex justify-between mb-1">
        <span class="text-sm font-medium text-gray-700">Mentor junior developers</span>
        <span class="text-sm font-medium text-gray-700">75%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-3">
        <div class="bg-blue-600 h-3 rounded-full" style="width: 75%"></div>
      </div>
    </div>

    <!-- Goal 3 -->
    <div>
      <div class="flex justify-between mb-1">
        <span class="text-sm font-medium text-gray-700">Complete AWS certification</span>
        <span class="text-sm font-medium text-gray-700">60%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-3">
        <div class="bg-orange-500 h-3 rounded-full" style="width: 60%"></div>
      </div>
    </div>

  </section>

</div>