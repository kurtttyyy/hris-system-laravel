    <header class="bg-white border-b sticky top-0 z-40 px-4 md:px-8 py-4 md:py-5 flex justify-between items-center backdrop-blur-sm">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">HR Dashboard</h1>
        <p class="text-sm text-slate-500">Welcome back! Here's what's happening today.</p>
        <p class="text-xs text-slate-500 mt-1">{{ now()->format('l, F j, Y') }}</p>
      </div>

      <div class="flex items-center gap-4">
        <div class="relative">
          <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
          <input class="pl-9 pr-4 py-2 border rounded-lg text-sm" placeholder="Search employees..." />
        </div>
        <div class="relative">
          <i class="fa-regular fa-bell text-slate-500 text-lg"></i>
          <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </div>
      </div>
    </header>


