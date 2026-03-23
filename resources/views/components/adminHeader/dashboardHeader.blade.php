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
    $adminNotificationItems = collect($adminNotificationItems ?? []);
    $adminNotificationStats = $adminNotificationStats ?? [];
    $pendingEmployeeApprovalCount = isset($employee) ? (int) collect($employee)->count() : (int) ($adminNotificationStats['approvals'] ?? 0);
    $pendingLeaveCount = (int) ($pendingLeaveRequestCount ?? ($adminNotificationStats['leave'] ?? 0));
    $activeApplicantCount = (int) ($openPositionApplicationsCount ?? ($adminNotificationStats['hiring'] ?? 0));
    $departmentCoverageCount = isset($departments) ? (int) collect($departments)->count() : (int) ($adminNotificationStats['workforce'] ?? 0);
    $adminNotificationItems = $adminNotificationItems->isNotEmpty() ? $adminNotificationItems : collect([
        [
            'label' => 'Pending employee approvals',
            'count' => $pendingEmployeeApprovalCount,
            'description' => 'New employee accounts waiting for review.',
            'href' => route('admin.adminNotifications'),
            'icon' => 'fa-solid fa-user-check',
            'badgeClass' => 'bg-emerald-100 text-emerald-700',
            'iconClass' => 'text-emerald-500',
        ],
        [
            'label' => 'Pending leave approvals',
            'count' => $pendingLeaveCount,
            'description' => 'Leave requests waiting for admin action.',
            'href' => route('admin.adminNotifications'),
            'icon' => 'fa-solid fa-calendar-check',
            'badgeClass' => 'bg-amber-100 text-amber-700',
            'iconClass' => 'text-amber-500',
        ],
        [
            'label' => 'Active hiring pipeline',
            'count' => $activeApplicantCount,
            'description' => 'Applicants currently attached to open roles.',
            'href' => route('admin.adminNotifications'),
            'icon' => 'fa-solid fa-briefcase',
            'badgeClass' => 'bg-sky-100 text-sky-700',
            'iconClass' => 'text-sky-500',
        ],
        [
            'label' => 'Department coverage',
            'count' => $departmentCoverageCount,
            'description' => 'Teams currently represented across the workforce.',
            'href' => route('admin.adminNotifications'),
            'icon' => 'fa-solid fa-building-user',
            'badgeClass' => 'bg-cyan-100 text-cyan-700',
            'iconClass' => 'text-cyan-500',
        ],
    ])->filter(fn ($item) => $item['count'] > 0)->values();
    $adminNotificationItems = $adminNotificationItems->map(function ($item) {
        $category = strtolower((string) ($item['category'] ?? ''));

        return array_merge([
            'href' => route('admin.adminNotifications'),
            'icon' => match ($category) {
                'approvals' => 'fa-solid fa-user-check',
                'leave' => 'fa-solid fa-calendar-check',
                'hiring' => 'fa-solid fa-briefcase',
                'workforce' => 'fa-solid fa-building-user',
                default => 'fa-regular fa-bell',
            },
            'iconClass' => match ($item['tone'] ?? 'slate') {
                'emerald' => 'text-emerald-500',
                'amber' => 'text-amber-500',
                'sky' => 'text-sky-500',
                'slate' => 'text-slate-500',
                default => 'text-slate-500',
            },
            'badgeClass' => match ($item['tone'] ?? 'slate') {
                'emerald' => 'bg-emerald-100 text-emerald-700',
                'amber' => 'bg-amber-100 text-amber-700',
                'sky' => 'bg-sky-100 text-sky-700',
                'slate' => 'bg-slate-100 text-slate-700',
                default => 'bg-slate-100 text-slate-700',
            },
            'label' => $item['label'] ?? ($item['title'] ?? 'Admin update'),
            'description' => $item['description'] ?? ($item['message'] ?? ''),
            'count' => (int) ($item['count'] ?? 1),
        ], $item);
    })->values();
    $adminNotificationTotal = (int) ($adminNotificationStats['total'] ?? $adminNotificationItems->sum('count'));
    $adminNotificationBadge = $adminNotificationTotal > 99 ? '99+' : (string) $adminNotificationTotal;
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
        background-color: rgba(3, 19, 29, 0.22);
        box-shadow: 0 16px 34px rgba(3, 19, 29, 0.18);
    }

    .admin-header-shell.is-scrolled .admin-header-card {
        transform: scale(0.985);
        box-shadow: 0 16px 40px rgba(3, 19, 29, 0.24);
    }

    .admin-header-shell.is-scrolled .admin-header-subtitle,
    .admin-header-shell.is-scrolled .admin-header-meta {
        opacity: 0.8;
        transform: translateY(-3px);
    }
</style>

@include('components.adminHeader.scrollBehavior')

<header id="admin-dashboard-header" data-admin-scroll-header class="admin-header-shell sticky top-0 z-40 px-4 py-4 md:px-8 md:py-5">
    <div data-admin-scroll-card class="admin-header-card relative overflow-visible flex flex-col gap-5 rounded-[1.75rem] border border-emerald-950/70 bg-[linear-gradient(135deg,_#03131d_0%,_#052f2a_42%,_#116149_100%)] px-5 py-5 shadow-[0_24px_60px_rgba(3,19,29,0.34)] backdrop-blur-xl lg:flex-row lg:items-center lg:justify-between md:px-7">
        <div class="pointer-events-none absolute inset-0 overflow-hidden rounded-[inherit]">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(45,212,191,0.14),_transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(110,231,183,0.14),_transparent_32%)]"></div>
        </div>
        <div class="relative min-w-0">
            <div class="admin-header-meta inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/8 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-emerald-50">
                Admin Header
            </div>
            <h1 class="admin-header-title mt-3 text-3xl font-black tracking-tight text-white">{{ $headerTitle }}</h1>
            <p class="admin-header-subtitle mt-1 text-sm text-emerald-50/85 md:text-base">{{ $headerSubtitle }}</p>
            <p class="admin-header-meta mt-3 inline-flex rounded-full border border-white/10 bg-white/8 px-3 py-1.5 text-xs font-medium text-emerald-50/80">{{ now()->format('l, F j, Y') }}</p>
        </div>

        <div class="relative flex w-full min-w-0 flex-col gap-4 lg:max-w-[720px] lg:self-end xl:flex-row xl:items-center xl:justify-end">
            <label class="admin-header-search group relative flex min-w-0 flex-1 items-center rounded-2xl border border-white/10 bg-white px-4 py-3 focus-within:border-emerald-300 focus-within:shadow-sm">
                <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                <input
                    class="w-full min-w-0 bg-transparent pl-3 pr-2 text-sm text-slate-700 outline-none placeholder:text-slate-400"
                    placeholder="{{ $headerSearchPlaceholder }}"
                />
            </label>

            <div class="flex flex-wrap items-center justify-end gap-3">
                <div class="admin-header-meta hidden items-center gap-2 rounded-full border border-white/10 bg-white/8 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-50 sm:inline-flex">
                    HR Online
                </div>

                <div class="relative group">
                    <a href="{{ route('admin.adminNotifications') }}" class="relative flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/8 text-emerald-50 shadow-sm transition hover:-translate-y-0.5 hover:border-white/20 hover:bg-white/15" aria-label="Open admin notifications">
                        <span data-admin-notification-badge data-fallback-count="{{ $adminNotificationTotal }}" class="{{ $adminNotificationTotal > 0 ? '' : 'hidden ' }}absolute right-0 top-0 flex h-5 min-w-[1.25rem] -translate-y-1/4 translate-x-1/4 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">
                            {{ $adminNotificationBadge }}
                        </span>
                        <i class="fa-regular fa-bell text-lg"></i>
                    </a>

                    <div class="invisible absolute right-0 z-50 mt-3 w-[21rem] overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white opacity-0 shadow-2xl transition-all duration-200 group-hover:visible group-hover:opacity-100">
                        <div class="border-b border-slate-100 bg-slate-50/80 px-4 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Notifications</p>
                                    <p class="text-xs text-slate-500">Admin work that may need your attention.</p>
                                </div>
                                <span class="rounded-full bg-slate-900 px-2.5 py-1 text-xs font-semibold text-white">{{ $adminNotificationBadge }}</span>
                            </div>
                        </div>

                        @if ($adminNotificationItems->isNotEmpty())
                            <div class="max-h-80 overflow-y-auto py-2">
                                @foreach ($adminNotificationItems as $item)
                                    <a href="{{ $item['href'] }}" class="flex items-start gap-3 px-4 py-3 transition hover:bg-slate-50">
                                        <div class="mt-0.5 flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 {{ $item['iconClass'] }}">
                                            <i class="{{ $item['icon'] }}"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between gap-3">
                                                <p class="truncate text-sm font-semibold text-slate-900">{{ $item['label'] }}</p>
                                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $item['badgeClass'] }}">{{ number_format($item['count']) }}</span>
                                            </div>
                                            <p class="mt-1 text-xs leading-5 text-slate-500">{{ $item['description'] }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="px-4 py-6 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                    <i class="fa-regular fa-circle-check text-lg"></i>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-slate-900">All clear</p>
                                <p class="mt-1 text-xs leading-5 text-slate-500">No admin notifications are waiting right now.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="relative group min-w-0">
                    <button class="flex max-w-full items-center gap-3 rounded-2xl border border-white/10 bg-white/8 px-3 py-2 shadow-sm transition hover:-translate-y-0.5 hover:border-white/20 hover:bg-white/15">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-300 to-cyan-300 text-sm font-bold text-slate-950">
                            {{ $adminInitials !== '' ? $adminInitials : 'AD' }}
                        </div>
                        <div class="hidden min-w-0 text-left sm:block">
                            <p class="truncate text-sm font-semibold text-white">{{ $adminName }}</p>
                            <p class="text-xs text-emerald-50/70">Administrator</p>
                        </div>
                        <i class="fa-solid fa-angle-down text-emerald-50/70"></i>
                    </button>

                    <div class="invisible absolute right-0 z-50 mt-3 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white opacity-0 shadow-xl transition-all duration-200 group-hover:visible group-hover:opacity-100">
                        <button type="button" class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm text-slate-700 hover:bg-slate-50">
                            <i class="fa-regular fa-user text-slate-400"></i>
                            My Profile
                        </button>
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

    (function () {
        const badges = Array.from(document.querySelectorAll('[data-admin-notification-badge]'));
        const summaryUrl = @json(route('admin.adminNotifications.summary'));
        if (!badges.length || !summaryUrl) {
            return;
        }

        const readKey = 'admin_notifications_read_v1';
        const unreadKey = 'admin_notifications_unread_v1';
        const fallbackCount = Number.parseInt(badges[0].getAttribute('data-fallback-count') || '0', 10) || 0;

        const renderBadges = (count) => {
            badges.forEach((badge) => {
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : String(count);
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            });
        };

        const computeUnreadCount = (items) => {
            let readLookup = {};
            try {
                const raw = localStorage.getItem(readKey);
                const parsed = raw ? JSON.parse(raw) : {};
                readLookup = parsed && typeof parsed === 'object' ? parsed : {};
            } catch (error) {
                readLookup = {};
            }

            return (Array.isArray(items) ? items : []).reduce((count, item) => {
                const id = item?.id ? String(item.id) : '';
                return count + (id && !readLookup[id] ? 1 : 0);
            }, 0);
        };

        const syncBadge = async () => {
            try {
                const response = await fetch(summaryUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    throw new Error('Unable to load admin notification summary.');
                }

                const payload = await response.json();
                const unreadCount = computeUnreadCount(payload?.items ?? []);
                localStorage.setItem(unreadKey, String(unreadCount));
                renderBadges(unreadCount);
            } catch (error) {
                const storedUnread = Number.parseInt(localStorage.getItem(unreadKey) || '', 10);
                const nextCount = Number.isFinite(storedUnread) ? storedUnread : fallbackCount;
                renderBadges(nextCount);
            }
        };

        syncBadge();
        window.addEventListener('storage', function (event) {
            if (event.key === unreadKey) {
                const nextCount = Number.parseInt(event.newValue || '', 10);
                renderBadges(Number.isFinite(nextCount) ? nextCount : fallbackCount);
            }
        });
        window.setInterval(syncBadge, 30000);
    })();
</script>
