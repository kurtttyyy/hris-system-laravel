<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Notifications | PeopleHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; transition: margin-left 0.3s ease; }
        main { transition: margin-left 0.3s ease; }
        aside ~ main { margin-left: 16rem; }
        .admin-display {
            font-family: "Arial Black", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            letter-spacing: -0.03em;
        }
        .admin-kicker {
            letter-spacing: 0.22em;
        }
    </style>
</head>
<body class="bg-[radial-gradient(circle_at_top,_#f8fafc,_#eef2ff_40%,_#f8fafc_100%)] text-slate-900">
@php
    $adminNotificationItems = collect($adminNotificationItems ?? []);
    $adminNotificationStats = $adminNotificationStats ?? ['total' => 0, 'approvals' => 0, 'leave' => 0, 'hiring' => 0, 'workforce' => 0];
    $toneClasses = [
        'emerald' => 'bg-emerald-100 text-emerald-700',
        'amber' => 'bg-amber-100 text-amber-700',
        'rose' => 'bg-rose-100 text-rose-700',
        'violet' => 'bg-violet-100 text-violet-700',
        'sky' => 'bg-sky-100 text-sky-700',
        'slate' => 'bg-slate-100 text-slate-700',
    ];
    $iconClasses = [
        'Approvals' => 'fa-user-check',
        'Leave' => 'fa-calendar-check',
        'Hiring' => 'fa-briefcase',
        'Workforce' => 'fa-building-user',
    ];
    $categoryKeyMap = [
        'Approvals' => 'approvals',
        'Leave' => 'leave',
        'Hiring' => 'hiring',
        'Workforce' => 'workforce',
    ];
@endphp

<div class="flex min-h-screen">
    @include('components.adminSideBar')

    <main class="flex-1 ml-16 transition-all duration-300">
        @include('components.adminHeader.dashboardHeader', [
            'headerTitle' => 'Admin Notifications',
            'headerSubtitle' => 'Track approvals, leave requests, and workforce signals from one inbox.',
            'headerSearchPlaceholder' => 'Search notifications...',
            'adminNotificationItems' => $adminNotificationItems,
            'adminNotificationStats' => $adminNotificationStats,
            'employee' => $employee ?? collect(),
            'departments' => $departments ?? collect(),
            'openPositionApplicationsCount' => $openPositionApplicationsCount ?? 0,
        ])

        <div class="p-4 pt-20 md:p-8">
            <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_18px_60px_rgba(15,23,42,0.08)]">
                <div class="grid grid-cols-1 xl:grid-cols-[280px_1fr]">
                    <aside class="border-b border-slate-200 bg-[linear-gradient(180deg,#f8fafc,#f1f5f9)] p-5 xl:border-b-0 xl:border-r">
                        <div class="rounded-[1.4rem] bg-slate-900 px-4 py-4 text-white shadow-lg">
                            <p class="admin-kicker text-[11px] font-semibold uppercase text-slate-300">Inbox</p>
                            <p class="admin-display mt-2 text-3xl text-white">{{ (int) ($adminNotificationStats['total'] ?? 0) }}</p>
                            <p class="mt-1 text-sm text-slate-300">admin update{{ (int) ($adminNotificationStats['total'] ?? 0) === 1 ? '' : 's' }}</p>
                        </div>

                        <div class="mt-6 space-y-2">
                            <button type="button" data-filter="all" class="notification-filter flex w-full items-center justify-between rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                                <span><i class="fa-solid fa-inbox mr-2"></i>All mail</span>
                                <span>{{ (int) ($adminNotificationStats['total'] ?? 0) }}</span>
                            </button>
                            <button type="button" data-filter="approvals" class="notification-filter flex w-full items-center justify-between rounded-2xl px-4 py-3 text-sm text-slate-600 transition hover:bg-slate-100">
                                <span><i class="fa-solid fa-user-check mr-2 text-emerald-600"></i>Approvals</span>
                                <span>{{ (int) ($adminNotificationStats['approvals'] ?? 0) }}</span>
                            </button>
                            <button type="button" data-filter="leave" class="notification-filter flex w-full items-center justify-between rounded-2xl px-4 py-3 text-sm text-slate-600 transition hover:bg-slate-100">
                                <span><i class="fa-solid fa-calendar-check mr-2 text-amber-600"></i>Leave</span>
                                <span>{{ (int) ($adminNotificationStats['leave'] ?? 0) }}</span>
                            </button>
                            <button type="button" data-filter="hiring" class="notification-filter flex w-full items-center justify-between rounded-2xl px-4 py-3 text-sm text-slate-600 transition hover:bg-slate-100">
                                <span><i class="fa-solid fa-briefcase mr-2 text-sky-600"></i>Hiring</span>
                                <span>{{ (int) ($adminNotificationStats['hiring'] ?? 0) }}</span>
                            </button>
                            <button type="button" data-filter="workforce" class="notification-filter flex w-full items-center justify-between rounded-2xl px-4 py-3 text-sm text-slate-600 transition hover:bg-slate-100">
                                <span><i class="fa-solid fa-building-user mr-2 text-slate-600"></i>Workforce</span>
                                <span>{{ (int) ($adminNotificationStats['workforce'] ?? 0) }}</span>
                            </button>
                        </div>

                        <div class="mt-8 rounded-[1.4rem] border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Quick Actions</p>
                            <div class="mt-4 space-y-2">
                                <a href="{{ route('admin.adminEmployee') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-700">Review Employee Queue</a>
                                <a href="{{ route('admin.adminLeaveManagement') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-amber-50 hover:text-amber-700">Open Leave Management</a>
                                <a href="{{ route('admin.adminApplicant') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-sky-50 hover:text-sky-700">Review Applicants</a>
                                <a href="{{ route('admin.adminHome') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Back to Dashboard</a>
                            </div>
                        </div>
                    </aside>

                    <div class="min-w-0">
                        <div class="border-b border-slate-200 bg-white px-5 py-4">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Notification Mail</p>
                                    <h2 class="admin-display mt-1 text-2xl text-slate-900">Inbox</h2>
                                </div>
                                <div class="flex items-center gap-3 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-500">
                                    <i class="fa-solid fa-bell"></i>
                                    <span>Admin workflow feed</span>
                                </div>
                            </div>
                        </div>

                        <div id="notification-results-label" class="border-b border-slate-200 bg-slate-50/70 px-5 py-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                            {{ $adminNotificationItems->count() }} conversation{{ $adminNotificationItems->count() === 1 ? '' : 's' }}
                        </div>

                        <div id="notification-list" class="divide-y divide-slate-200">
                            @forelse ($adminNotificationItems as $item)
                                @php
                                    $tone = $toneClasses[$item['tone'] ?? 'slate'] ?? $toneClasses['slate'];
                                    $itemDate = $item['date'] ?? null;
                                    $iconClass = $iconClasses[$item['category'] ?? ''] ?? 'fa-bell';
                                    $itemFilter = $categoryKeyMap[$item['category'] ?? ''] ?? 'other';
                                @endphp
                                <a
                                    href="{{ $item['href'] ?? '#' }}"
                                    data-category="{{ $itemFilter }}"
                                    data-notification-id="{{ $item['id'] ?? md5(($item['category'] ?? 'update').'|'.($item['title'] ?? '').'|'.($item['message'] ?? '').'|'.optional($itemDate)->format('Y-m-d H:i:s')) }}"
                                    class="notification-row group grid grid-cols-1 gap-3 bg-white px-5 py-4 transition hover:bg-slate-50 md:grid-cols-[52px_170px_minmax(0,1fr)_110px] md:items-center"
                                >
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $tone }}">
                                        <i class="fa-solid {{ $iconClass }}"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-900">
                                            {{ $item['category'] ?? 'Update' }}
                                        </p>
                                        <span class="mt-1 inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $tone }}">
                                            {{ $item['badge'] ?? 'Notice' }}
                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="notification-title truncate text-sm font-black text-slate-900 group-hover:text-emerald-700">
                                                {{ $item['title'] ?? 'Notification' }}
                                            </span>
                                            <span class="hidden text-slate-300 md:inline">-</span>
                                            <span class="truncate text-sm text-slate-500">
                                                {{ $item['message'] ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-sm font-medium text-slate-400 md:text-right">
                                        {{ $itemDate ? $itemDate->diffForHumans() : '-' }}
                                    </div>
                                </a>
                            @empty
                                <div id="notification-empty-state" class="px-6 py-16 text-center">
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-slate-400">
                                        <i class="fa-regular fa-bell-slash text-2xl"></i>
                                    </div>
                                    <h3 class="mt-5 text-xl font-bold text-slate-900">Your admin inbox is quiet.</h3>
                                    <p class="mt-2 text-sm text-slate-500">New workforce updates will appear here as they come in.</p>
                                </div>
                            @endforelse
                            <div id="notification-filter-empty" class="hidden px-6 py-16 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-slate-400">
                                    <i class="fa-solid fa-magnifying-glass text-2xl"></i>
                                </div>
                                <h3 class="mt-5 text-xl font-bold text-slate-900">No notifications in this category.</h3>
                                <p class="mt-2 text-sm text-slate-500">Try another inbox filter to view other admin updates.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
    (function () {
        const filterButtons = Array.from(document.querySelectorAll('.notification-filter'));
        const items = Array.from(document.querySelectorAll('#notification-list > a[data-category]'));
        const resultsLabel = document.getElementById('notification-results-label');
        const filterEmpty = document.getElementById('notification-filter-empty');
        const defaultEmpty = document.getElementById('notification-empty-state');

        if (!filterButtons.length || !resultsLabel) {
            return;
        }

        const updateActiveButton = (activeFilter) => {
            filterButtons.forEach((button) => {
                const isActive = button.getAttribute('data-filter') === activeFilter;
                button.classList.toggle('bg-emerald-50', isActive);
                button.classList.toggle('text-emerald-700', isActive);
                button.classList.toggle('font-semibold', isActive);
                button.classList.toggle('text-slate-600', !isActive);
            });
        };

        const applyFilter = (filterValue) => {
            let visibleCount = 0;

            items.forEach((item) => {
                const matches = filterValue === 'all' || item.getAttribute('data-category') === filterValue;
                item.classList.toggle('hidden', !matches);
                if (matches) {
                    visibleCount += 1;
                }
            });

            resultsLabel.textContent = `${visibleCount} conversation${visibleCount === 1 ? '' : 's'}`;

            if (defaultEmpty) {
                defaultEmpty.classList.toggle('hidden', items.length > 0 || visibleCount !== 0);
            }

            if (filterEmpty) {
                filterEmpty.classList.toggle('hidden', visibleCount !== 0 || items.length === 0);
            }

            updateActiveButton(filterValue);
        };

        filterButtons.forEach((button) => {
            button.addEventListener('click', function () {
                applyFilter(this.getAttribute('data-filter') || 'all');
            });
        });

        applyFilter('all');
    })();

    (function () {
        const storageKey = 'admin_notifications_read_v1';
        const unreadKey = 'admin_notifications_unread_v1';
        const totalKey = 'admin_notifications_total_v1';
        const notificationRows = Array.from(document.querySelectorAll('.notification-row[data-notification-id]'));

        if (!notificationRows.length) {
            localStorage.setItem(unreadKey, '0');
            return;
        }

        const readLookup = (() => {
            try {
                const raw = localStorage.getItem(storageKey);
                const parsed = raw ? JSON.parse(raw) : {};
                return parsed && typeof parsed === 'object' ? parsed : {};
            } catch (error) {
                return {};
            }
        })();

        const saveLookup = () => {
            try {
                localStorage.setItem(storageKey, JSON.stringify(readLookup));
            } catch (error) {
            }
        };

        const updateUnreadCount = () => {
            const unreadCount = notificationRows.reduce((count, row) => {
                const id = row.getAttribute('data-notification-id');
                return count + (id && !readLookup[id] ? 1 : 0);
            }, 0);

            try {
                localStorage.setItem(unreadKey, String(unreadCount));
                localStorage.setItem(totalKey, String(notificationRows.length));
            } catch (error) {
            }

            document.querySelectorAll('[data-admin-notification-badge]').forEach((badge) => {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount > 99 ? '99+' : String(unreadCount);
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            });
        };

        const applyReadState = (row, isRead) => {
            row.classList.toggle('bg-emerald-50', !isRead);
            row.classList.toggle('hover:bg-emerald-100', !isRead);
            row.classList.toggle('border-l-4', !isRead);
            row.classList.toggle('border-emerald-400', !isRead);
            row.classList.toggle('bg-white', isRead);
            row.classList.toggle('opacity-90', isRead);

            const title = row.querySelector('.notification-title');
            if (title) {
                title.classList.toggle('font-black', !isRead);
                title.classList.toggle('font-semibold', isRead);
                title.classList.toggle('text-slate-900', !isRead);
                title.classList.toggle('text-slate-700', isRead);
            }
        };

        notificationRows.forEach((row) => {
            const id = row.getAttribute('data-notification-id');
            if (!id) {
                return;
            }

            applyReadState(row, Boolean(readLookup[id]));

            row.addEventListener('click', function () {
                readLookup[id] = true;
                saveLookup();
                applyReadState(row, true);
                updateUnreadCount();
            });
        });

        updateUnreadCount();
    })();
</script>
</body>
</html>
