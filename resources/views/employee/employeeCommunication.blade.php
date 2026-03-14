<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Directory | Employee Portal</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            color-scheme: light;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            transition: margin-left 0.3s ease;
        }

        main {
            transition: margin-left 0.3s ease;
        }

        aside:not(:hover) ~ main {
            margin-left: 4rem;
        }

        aside:hover ~ main {
            margin-left: 14rem;
        }
    </style>
</head>
<body class="bg-[radial-gradient(circle_at_top,_#f0fdf4,_#eff6ff_35%,_#f8fafc_75%)] text-slate-900">
@php
    $directoryMembers = collect($admins ?? []);
    $availableCount = $directoryMembers->filter(function ($member) {
        $status = strtolower(trim((string) ($member->status ?? '')));
        return in_array($status, ['approved', 'available'], true);
    })->count();
@endphp

<div class="flex min-h-screen">
    @include('components.employeeSideBar')

    <main class="flex-1 ml-16 transition-all duration-300">
        @include('components.employeeHeader.communicationHeader')

        <div class="px-4 pb-8 pt-6 md:px-8 md:pb-10">
            <section class="rounded-[2rem] border border-white/70 bg-white/75 p-5 shadow-[0_18px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl md:p-6">
                <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700">Directory Controls</p>
                        <h3 class="mt-2 text-2xl font-black tracking-tight text-slate-900">Find people by name, role, or status.</h3>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                            Use the search box and quick filters to narrow the list without leaving the page.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 xl:min-w-[560px] xl:max-w-[640px] xl:flex-row">
                        <label class="group flex flex-1 items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition focus-within:border-emerald-400 focus-within:bg-white focus-within:shadow-sm">
                            <i class="fa fa-search text-slate-400"></i>
                            <input
                                id="directory-search"
                                type="text"
                                placeholder="Search by employee name, role, or account type"
                                class="w-full bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400"
                            >
                        </label>

                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="directory-filter rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800" data-filter="all">
                                All <span class="ml-1 text-white/70">{{ $directoryMembers->count() }}</span>
                            </button>
                            <button type="button" class="directory-filter rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100" data-filter="available">
                                Available <span class="ml-1 text-emerald-500">{{ $availableCount }}</span>
                            </button>
                            <button type="button" class="directory-filter rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-200" data-filter="other">
                                Other
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-6">
                <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700">Directory Cards</p>
                        <h3 class="mt-2 text-2xl font-black tracking-tight text-slate-900">Meet the people behind the system.</h3>
                    </div>

                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/90 px-4 py-2 text-sm text-slate-600 shadow-sm">
                        <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        <span id="directory-results-count">{{ $directoryMembers->count() }}</span>
                        <span>visible member<span id="directory-results-plural">{{ $directoryMembers->count() === 1 ? '' : 's' }}</span></span>
                    </div>
                </div>

                <div id="directory-grid" class="grid grid-cols-1 gap-6 md:grid-cols-2 2xl:grid-cols-3">
                    @forelse($admins as $admin)
                        @php
                            $nameParts = array_filter([
                                $admin->first_name ?? '',
                                $admin->middle_name ?? '',
                                $admin->last_name ?? '',
                            ]);
                            $fullName = trim(implode(' ', $nameParts));
                            $initials = strtoupper(substr((string) ($admin->first_name ?? ''), 0, 1) . substr((string) ($admin->last_name ?? ''), 0, 1));
                            $displayStatus = trim((string) ($admin->status ?? ''));
                            if (strtolower($displayStatus) === 'approved') {
                                $displayStatus = 'Available';
                            }
                            $normalizedStatus = strtolower($displayStatus);
                            $isAvailable = $normalizedStatus === 'available';
                            $jobRole = trim((string) ($admin->job_role ?? 'Administrator'));
                            $role = trim((string) ($admin->role ?? 'Admin'));
                            $email = trim((string) ($admin->email ?? ''));
                            $statusClasses = $isAvailable
                                ? 'bg-emerald-100 text-emerald-700 ring-emerald-200'
                                : 'bg-slate-100 text-slate-600 ring-slate-200';
                            $cardAccent = $isAvailable
                                ? 'from-emerald-500 via-teal-500 to-sky-500'
                                : 'from-slate-500 via-slate-600 to-slate-700';
                            $cardGlow = $isAvailable
                                ? 'shadow-emerald-500/10'
                                : 'shadow-slate-500/10';
                        @endphp

                        <article
                            class="directory-card group relative overflow-hidden rounded-[2rem] border border-white/80 bg-white/90 p-6 shadow-[0_18px_60px_rgba(15,23,42,0.08)] transition duration-300 hover:-translate-y-1 hover:shadow-2xl {{ $cardGlow }}"
                            data-name="{{ strtolower($fullName) }}"
                            data-role="{{ strtolower($jobRole.' '.$role) }}"
                            data-status="{{ $isAvailable ? 'available' : 'other' }}"
                        >
                            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r {{ $cardAccent }}"></div>
                            <div class="absolute -right-10 top-12 h-28 w-28 rounded-full bg-sky-100/50 blur-3xl transition duration-300 group-hover:bg-emerald-100/70"></div>

                            <div class="relative flex h-full flex-col">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-20 w-20 items-center justify-center rounded-[1.6rem] bg-gradient-to-br {{ $cardAccent }} text-2xl font-black text-white shadow-lg">
                                            {{ $initials !== '' ? $initials : 'AD' }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $role !== '' ? $role : 'Admin' }}</p>
                                            <h4 class="mt-1 text-xl font-black leading-tight text-slate-900">
                                                {{ $fullName !== '' ? $fullName : 'Admin User' }}
                                            </h4>
                                            <p class="mt-1 text-sm font-medium text-slate-500">{{ $jobRole }}</p>
                                        </div>
                                    </div>

                                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $statusClasses }}">
                                        <span class="h-2 w-2 rounded-full {{ $isAvailable ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $displayStatus !== '' ? $displayStatus : 'No Status' }}
                                    </span>
                                </div>

                                <div class="mt-6 grid grid-cols-2 gap-3">
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Access</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-800">{{ $role !== '' ? $role : 'Employee' }}</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Status</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-800">{{ $displayStatus !== '' ? $displayStatus : 'No Status' }}</p>
                                    </div>
                                </div>

                                <div class="mt-5 rounded-[1.5rem] border border-slate-200 bg-white/80 px-4 py-4">
                                    <div class="flex items-start gap-3">
                                        <span class="mt-0.5 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                            <i class="fa fa-envelope"></i>
                                        </span>
                                        <div class="min-w-0">
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Contact</p>
                                            <p class="mt-1 truncate text-sm text-slate-600">{{ $email !== '' ? $email : 'Email not available' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex flex-wrap gap-3">
                                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                                        <i class="fa fa-user"></i>
                                        View Profile
                                    </button>
                                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                                        <i class="fa fa-comment"></i>
                                        Connect
                                    </button>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/80 p-10 text-center md:col-span-2 2xl:col-span-3">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                <i class="fa fa-users text-2xl"></i>
                            </div>
                            <h4 class="mt-5 text-xl font-bold text-slate-900">No admin users found.</h4>
                            <p class="mt-2 text-sm text-slate-500">Once members are available in the system, they will appear here as directory cards.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</div>

<script>
    const sidebar = document.querySelector('aside');
    const main = document.querySelector('main');

    if (sidebar && main) {
        sidebar.addEventListener('mouseenter', function() {
            main.classList.remove('ml-16');
            main.classList.add('ml-56');
        });

        sidebar.addEventListener('mouseleave', function() {
            main.classList.remove('ml-56');
            main.classList.add('ml-16');
        });
    }

    const searchInput = document.getElementById('directory-search');
    const filterButtons = Array.from(document.querySelectorAll('.directory-filter'));
    const directoryCards = Array.from(document.querySelectorAll('.directory-card'));
    const resultsCount = document.getElementById('directory-results-count');
    const resultsPlural = document.getElementById('directory-results-plural');

    let activeFilter = 'all';

    function applyDirectoryFilters() {
        const query = (searchInput?.value || '').trim().toLowerCase();
        let visibleCount = 0;

        directoryCards.forEach((card) => {
            const name = card.dataset.name || '';
            const role = card.dataset.role || '';
            const status = card.dataset.status || '';
            const matchesQuery = query === '' || name.includes(query) || role.includes(query);
            const matchesStatus = activeFilter === 'all' || status === activeFilter;
            const isVisible = matchesQuery && matchesStatus;

            card.classList.toggle('hidden', !isVisible);
            if (isVisible) {
                visibleCount += 1;
            }
        });

        if (resultsCount) {
            resultsCount.textContent = String(visibleCount);
        }

        if (resultsPlural) {
            resultsPlural.textContent = visibleCount === 1 ? '' : 's';
        }
    }

    filterButtons.forEach((button) => {
        button.addEventListener('click', function() {
            activeFilter = button.dataset.filter || 'all';

            filterButtons.forEach((item) => {
                item.classList.remove('bg-slate-900', 'text-white', 'bg-emerald-600');
                item.classList.add('bg-slate-100', 'text-slate-600');
            });

            if (activeFilter === 'available') {
                button.classList.remove('bg-slate-100', 'text-slate-600', 'bg-emerald-50', 'text-emerald-700');
                button.classList.add('bg-emerald-600', 'text-white');
            } else if (activeFilter === 'all') {
                button.classList.remove('bg-slate-100', 'text-slate-600');
                button.classList.add('bg-slate-900', 'text-white');
            } else {
                button.classList.remove('bg-slate-100', 'text-slate-600');
                button.classList.add('bg-slate-900', 'text-white');
            }

            filterButtons.forEach((item) => {
                if (item !== button && item.dataset.filter === 'available') {
                    item.classList.remove('bg-emerald-600', 'text-white');
                    item.classList.add('bg-emerald-50', 'text-emerald-700');
                }
            });

            applyDirectoryFilters();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyDirectoryFilters);
    }

    applyDirectoryFilters();
</script>

</body>
</html>
