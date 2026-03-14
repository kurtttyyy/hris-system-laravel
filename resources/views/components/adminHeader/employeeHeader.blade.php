<header class="sticky top-0 z-40 px-4 py-4 md:px-8 md:py-5">
    <div class="relative overflow-hidden rounded-[2rem] border border-slate-200/70 bg-white/92 shadow-[0_24px_60px_rgba(15,23,42,0.10)] backdrop-blur-xl">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.18),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(16,185,129,0.16),_transparent_28%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(255,255,255,0.92))]"></div>
        <div class="absolute -left-10 top-6 h-28 w-28 rounded-full bg-sky-200/30 blur-3xl"></div>
        <div class="absolute right-0 top-0 h-36 w-36 translate-x-10 -translate-y-10 rounded-full bg-emerald-200/40 blur-3xl"></div>

        <div class="relative flex flex-col gap-6 px-5 py-5 md:px-7 md:py-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-3xl min-w-0">
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-sky-700">
                    <span class="h-2 w-2 rounded-full bg-sky-500"></span>
                    Workforce Center
                </div>

                <div class="mt-4 min-w-0">
                    <h2 class="text-3xl font-black tracking-tight text-slate-900 md:text-4xl">Employee Directory</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 md:text-base">
                        Search profiles, narrow by department, and monitor employee status from one polished workspace.
                    </p>
                    <div class="mt-3 flex flex-wrap items-center gap-3 text-xs font-medium text-slate-500">
                        <span class="rounded-full border border-slate-200 bg-white/80 px-3 py-1.5">{{ now()->format('l, F j, Y') }}</span>
                        <span
                            class="rounded-full border border-slate-200 bg-white/80 px-3 py-1.5"
                            x-text="`${employeeIndex.length} total records`"
                        ></span>
                        <span
                            class="rounded-full border border-slate-200 bg-white/80 px-3 py-1.5"
                            x-text="`Showing ${employeeIndex.filter(emp => matchesDepartment(emp.department) && matchesSearch(emp.name) && matchesStatus(emp.status)).length}`"
                        ></span>
                    </div>
                </div>
            </div>

            <div class="w-full xl:max-w-2xl">
                <div class="rounded-[1.75rem] border border-slate-200/80 bg-white/85 p-4 shadow-[0_16px_34px_rgba(15,23,42,0.07)] backdrop-blur">
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,0.8fr)]">
                        <label class="group relative flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition focus-within:border-sky-300 focus-within:bg-white focus-within:shadow-sm">
                            <i class="fa-solid fa-magnifying-glass text-slate-400 transition group-focus-within:text-sky-600"></i>
                            <input
                                type="text"
                                x-model="search"
                                placeholder="Search by employee name..."
                                class="w-full bg-transparent pl-3 pr-2 text-sm text-slate-700 outline-none placeholder:text-slate-400"
                            >
                        </label>

                        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition focus-within:border-sky-300 focus-within:bg-white focus-within:shadow-sm">
                            <i class="fa-solid fa-layer-group text-slate-400"></i>
                            <select
                                x-model="department"
                                class="w-full bg-transparent text-sm font-medium text-slate-700 outline-none"
                            >
                                <option value="All">All Departments</option>
                                @foreach (($departmentOptions ?? collect()) as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>

                    <div class="mt-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                @click="statusFilter = 'All'"
                                :class="statusFilter === 'All'
                                    ? 'border-slate-900 bg-slate-900 text-white shadow-md'
                                    : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition"
                            >
                                All
                            </button>
                            <button
                                type="button"
                                @click="statusFilter = 'Active'"
                                :class="statusFilter === 'Active'
                                    ? 'border-emerald-500 bg-emerald-500 text-white shadow-md'
                                    : 'border-emerald-200 bg-emerald-50/70 text-emerald-700 hover:bg-emerald-100'"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition"
                            >
                                Active
                            </button>
                            <button
                                type="button"
                                @click="statusFilter = 'On Leave'"
                                :class="statusFilter === 'On Leave'
                                    ? 'border-amber-400 bg-amber-400 text-white shadow-md'
                                    : 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100'"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition"
                            >
                                On Leave
                            </button>
                            <button
                                type="button"
                                @click="statusFilter = 'Inactive'"
                                :class="statusFilter === 'Inactive'
                                    ? 'border-rose-500 bg-rose-500 text-white shadow-md'
                                    : 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100'"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition"
                            >
                                Inactive
                            </button>
                        </div>

                        <button
                            type="button"
                            @click="search = ''; department = 'All'; statusFilter = 'All'"
                            class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:-translate-y-0.5 hover:border-slate-300 hover:text-slate-900"
                        >
                            <i class="fa-solid fa-rotate-left text-xs"></i>
                            Reset filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
