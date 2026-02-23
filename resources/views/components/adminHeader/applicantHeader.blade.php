<header class="bg-white border-b border-gray-200 sticky top-0 z-40 px-4 md:px-8 py-4 md:py-6 backdrop-blur-sm">
  <div class="flex items-center justify-between gap-6">

    <!-- Title -->
    <div>
      <h2 class="text-3xl font-bold text-gray-900">Applicants</h2>
      <p class="text-gray-600 mt-1">
        Manage and review job applications
      </p>
      <p class="text-xs text-slate-500 mt-1">{{ now()->format('l, F j, Y') }}</p>
    </div>

    <!-- Search -->
    <div class="relative w-80">
      <input
        type="text"
        placeholder="Search applicant..."
        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300
               focus:ring-2 focus:ring-green-500 focus:border-green-500
               text-sm text-gray-700"
      />
      <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
    </div>

  </div>
</header>
