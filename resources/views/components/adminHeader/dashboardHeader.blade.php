@php
    $headerTitle = $headerTitle ?? 'HR Dashboard';
    $headerSubtitle = $headerSubtitle ?? "Welcome back! Here's what's happening today.";
    $headerSearchPlaceholder = $headerSearchPlaceholder ?? 'Search employees...';
    $adminUser = auth()->user();
    $adminName = trim(implode(' ', array_filter([
        $adminUser?->first_name ?? null,
        $adminUser?->last_name ?? null,
    ])));
    $adminName = $adminName !== '' ? $adminName : 'Admin User';
    $adminInitials = strtoupper(substr(trim((string) ($adminUser?->first_name ?? 'A')), 0, 1).substr(trim((string) ($adminUser?->last_name ?? 'D')), 0, 1));
@endphp

<style>
    .admin-header-shell,
    .admin-header-card,
    .admin-header-title,
    .admin-header-subtitle,
    .admin-header-search,
    .admin-header-meta {
        transition: all 0.28s ease;
    }

    .admin-header-shell.is-scrolled {
        background-color: rgba(248, 250, 252, 0.85);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
    }

    .admin-header-shell.is-scrolled .admin-header-card {
        transform: scale(0.985);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
    }

    .admin-header-shell.is-scrolled .admin-header-subtitle,
    .admin-header-shell.is-scrolled .admin-header-meta {
        opacity: 0.8;
        transform: translateY(-3px);
    }
</style>

<header id="admin-dashboard-header" class="admin-header-shell sticky top-0 z-40 px-4 py-4 md:px-8 md:py-5">
    <div class="admin-header-card flex flex-col gap-5 rounded-[1.75rem] border border-slate-200/80 bg-white/85 px-5 py-5 shadow-[0_18px_50px_rgba(15,23,42,0.08)] backdrop-blur-xl lg:flex-row lg:items-center lg:justify-between md:px-7">
        <div class="min-w-0">
            <div class="admin-header-meta inline-flex items-center gap-2 rounded-full border border-sky-100 bg-sky-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-sky-700">
                Admin Header
            </div>
            <h1 class="admin-header-title mt-3 text-3xl font-black tracking-tight text-slate-900">{{ $headerTitle }}</h1>
            <p class="admin-header-subtitle mt-1 text-sm text-slate-500 md:text-base">{{ $headerSubtitle }}</p>
            <p class="admin-header-meta mt-1 text-xs font-medium text-slate-400">{{ now()->format('l, F j, Y') }}</p>
        </div>

        <div class="flex flex-col gap-4 xl:min-w-[620px] xl:max-w-[720px] xl:flex-row xl:items-center xl:justify-end">
            <label class="admin-header-search group relative flex flex-1 items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus-within:border-sky-300 focus-within:bg-white focus-within:shadow-sm">
                <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                <input
                    class="w-full bg-transparent pl-3 pr-2 text-sm text-slate-700 outline-none placeholder:text-slate-400"
                    placeholder="{{ $headerSearchPlaceholder }}"
                />
            </label>

            <div class="flex items-center gap-3">
                <div class="admin-header-meta hidden items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700 sm:inline-flex">
                    HR Online
                </div>

                <button class="relative flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:text-sky-700">
                    <span class="absolute right-0 top-0 flex h-5 min-w-[1.25rem] -translate-y-1/4 translate-x-1/4 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">
                        3
                    </span>
                    <i class="fa-regular fa-bell text-lg"></i>
                </button>

                <div class="relative group">
                    <button class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-indigo-500 text-sm font-bold text-white">
                            {{ $adminInitials !== '' ? $adminInitials : 'AD' }}
                        </div>
                        <div class="hidden text-left sm:block">
                            <p class="text-sm font-semibold text-slate-900">{{ $adminName }}</p>
                            <p class="text-xs text-slate-500">Administrator</p>
                        </div>
                        <i class="fa-solid fa-angle-down text-slate-400"></i>
                    </button>

                    <div class="invisible absolute right-0 z-50 mt-3 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white opacity-0 shadow-xl transition-all duration-200 group-hover:visible group-hover:opacity-100">
                        <a href="{{ route('admin.adminHome') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">
                            <i class="fa-solid fa-house text-slate-400"></i>
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm text-rose-600 hover:bg-rose-50">
                                <i class="fa fa-sign-out text-rose-500"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    (function () {
        const header = document.getElementById('admin-dashboard-header');
        if (!header) {
            return;
        }

        let isScrolled = false;

        const updateHeaderOnScroll = () => {
            const nextScrolled = window.scrollY > 24;
            if (nextScrolled === isScrolled) {
                return;
            }

            isScrolled = nextScrolled;
            header.classList.toggle('is-scrolled', isScrolled);
        };

        updateHeaderOnScroll();
        window.addEventListener('scroll', updateHeaderOnScroll, { passive: true });
    })();
</script>
