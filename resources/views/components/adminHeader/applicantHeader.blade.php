<header class="sticky top-0 z-40 px-4 py-4 md:px-8 md:py-5">
  <div class="relative overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/90 shadow-[0_24px_55px_rgba(15,23,42,0.08)] backdrop-blur-xl">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.16),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(16,185,129,0.12),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(255,255,255,0.92))]"></div>
    <div class="absolute -left-8 top-6 h-28 w-28 rounded-full bg-sky-200/35 blur-3xl"></div>
    <div class="absolute right-0 top-0 h-36 w-36 translate-x-10 -translate-y-10 rounded-full bg-emerald-200/35 blur-3xl"></div>

    <div class="relative flex flex-col gap-5 px-5 py-5 md:px-7 md:py-6 xl:flex-row xl:items-end xl:justify-between">
      <div class="max-w-3xl min-w-0">
        <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-sky-700">
          <span class="h-2 w-2 rounded-full bg-sky-500"></span>
          Talent Pipeline
        </div>

        <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-900 md:text-4xl">Applicants</h2>
        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 md:text-base">
          Manage incoming candidates, track movement through hiring stages, and review top talent from one cleaner dashboard.
        </p>

        <div class="mt-4 flex flex-wrap items-center gap-3 text-xs font-medium text-slate-500">
          <span class="rounded-full border border-slate-200 bg-white/85 px-3 py-1.5">{{ now()->format('l, F j, Y') }}</span>
          <span class="rounded-full border border-slate-200 bg-white/85 px-3 py-1.5">Hiring Workspace</span>
        </div>
      </div>

      <div class="w-full xl:max-w-xl">
        <div class="rounded-[1.75rem] border border-slate-200/80 bg-white/90 p-4 shadow-[0_16px_34px_rgba(15,23,42,0.07)] backdrop-blur">
          <label class="group relative flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition focus-within:border-sky-300 focus-within:bg-white focus-within:shadow-sm">
            <i class="fa-solid fa-magnifying-glass text-slate-400 transition group-focus-within:text-sky-600"></i>
            <input
              id="headerApplicantSearch"
              type="text"
              placeholder="Search applicant name, email, position, or status..."
              class="w-full bg-transparent pl-3 pr-2 text-sm text-slate-700 outline-none placeholder:text-slate-400"
            />
          </label>
        </div>
      </div>
    </div>
  </div>
</header>
