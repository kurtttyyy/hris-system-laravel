<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Resignation - Northeastern College</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { transition: margin-left 0.3s ease; }
        main { transition: margin-left 0.3s ease; }
        aside:not(:hover) ~ main { margin-left: 4rem; }
        aside:hover ~ main { margin-left: 14rem; }
    </style>
</head>
<body class="bg-[radial-gradient(circle_at_top,_#ecfdf5,_#f8fafc_40%,_#eef2ff_100%)]">
@php
    $resignationCollection = collect($resignations ?? []);
    $pendingCount = $resignationCollection->filter(fn ($row) => strtolower(trim((string) ($row->status ?? 'pending'))) === 'pending')->count();
    $approvedCount = $resignationCollection->filter(fn ($row) => in_array(strtolower(trim((string) ($row->status ?? ''))), ['approved', 'completed'], true))->count();
    $rejectedCount = $resignationCollection->filter(fn ($row) => in_array(strtolower(trim((string) ($row->status ?? ''))), ['rejected', 'cancelled'], true))->count();
    $latestRequest = $resignationCollection->first();
    $latestStatus = trim((string) ($latestRequest?->status ?? 'No Request Yet'));
    $latestEffectiveDate = optional($latestRequest?->effective_date)->format('F d, Y') ?? 'Not scheduled';
@endphp

<div class="flex min-h-screen">
    @include('components.employeeSideBar')

    <main class="flex-1 ml-16 transition-all duration-300">
        <div class="space-y-8 p-4 pt-4 md:p-8">
            <section class="relative overflow-hidden rounded-[2rem] border border-emerald-950/40 bg-gradient-to-br from-slate-950 via-emerald-950 to-emerald-800 p-6 text-white shadow-2xl md:p-8">
                <div class="absolute -right-10 -top-12 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute bottom-0 right-20 h-24 w-24 rounded-full bg-emerald-300/10 blur-2xl"></div>
                <div class="relative grid gap-6 xl:grid-cols-[1.55fr_0.95fr] xl:items-end">
                    <div class="space-y-5">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-emerald-100">
                            Exit Request Desk
                        </div>

                        <div>
                            <h1 class="max-w-3xl text-3xl font-black leading-tight md:text-5xl">Submit a resignation request with a clearer, more structured handover process.</h1>
                            <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50 md:text-base">
                                File your request, set the intended effective date, and track every status update from review to final completion.
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm">
                                <p class="text-xs uppercase tracking-wide text-emerald-100">Requests</p>
                                <p class="mt-2 text-2xl font-black">{{ $resignationCollection->count() }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm">
                                <p class="text-xs uppercase tracking-wide text-amber-100">Pending</p>
                                <p class="mt-2 text-2xl font-black">{{ $pendingCount }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm">
                                <p class="text-xs uppercase tracking-wide text-lime-100">Approved</p>
                                <p class="mt-2 text-2xl font-black">{{ $approvedCount }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm">
                                <p class="text-xs uppercase tracking-wide text-rose-100">Closed</p>
                                <p class="mt-2 text-2xl font-black">{{ $rejectedCount }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.75rem] border border-white/10 bg-white/10 p-5 backdrop-blur-sm">
                        <div class="mb-4 flex justify-end">
                            <div class="relative group">
                                <button class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-white backdrop-blur-sm transition hover:bg-white/20">
                                    <i class="fa fa-user"></i>
                                </button>

                                <div class="absolute right-0 z-50 mt-3 invisible w-48 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg opacity-0 transition-all duration-200 group-hover:visible group-hover:opacity-100">
                                    <a href="{{ route('employee.employeeProfile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fa fa-user"></i>
                                        My Profile
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm text-red-600 hover:bg-red-50">
                                            <i class="fa fa-sign-out"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-100">Latest Update</p>
                        <div class="mt-5 space-y-4">
                            <div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-emerald-50">Request progress</span>
                                    <span class="font-semibold">{{ $latestStatus !== '' ? $latestStatus : 'No Request Yet' }}</span>
                                </div>
                                <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-white/15">
                                    <div class="h-full rounded-full bg-emerald-300" style="width: {{ $resignationCollection->isEmpty() ? 0 : ($approvedCount > 0 ? 100 : ($pendingCount > 0 ? 55 : 30)) }}%;"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-2xl bg-white/10 p-4">
                                    <p class="text-xs uppercase tracking-wide text-emerald-100">Current Status</p>
                                    <p class="mt-2 text-sm font-bold text-white">{{ $latestStatus !== '' ? $latestStatus : 'No Request Yet' }}</p>
                                </div>
                                <div class="rounded-2xl bg-white/10 p-4">
                                    <p class="text-xs uppercase tracking-wide text-emerald-100">Effective Date</p>
                                    <p class="mt-2 text-sm font-bold text-white">{{ $latestEffectiveDate }}</p>
                                </div>
                            </div>

                            <p class="text-xs leading-5 text-emerald-50">
                                Submit only when your final schedule, endorsement, and turnover plan are already aligned with your department.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            @if (session('success'))
                <div class="rounded-[1.25rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-[1.25rem] border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <section class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-[1.75rem] border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                            <i class="fa fa-file-text-o fa-2x"></i>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Requests</span>
                    </div>
                    <h3 class="mt-8 text-4xl font-black text-slate-900">{{ $resignationCollection->count() }}</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600">Filed Resignations</p>
                    <p class="mt-4 text-xs leading-5 text-slate-500">All requests you have submitted, including pending, approved, completed, rejected, or cancelled records.</p>
                </article>

                <article class="rounded-[1.75rem] border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg shadow-amber-500/20">
                            <i class="fa fa-hourglass-half fa-2x"></i>
                        </div>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">In Review</span>
                    </div>
                    <h3 class="mt-8 text-4xl font-black text-slate-900">{{ $pendingCount }}</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600">Pending Requests</p>
                    <p class="mt-4 text-xs leading-5 text-slate-500">Requests that are still waiting for final HR or admin action.</p>
                </article>

                <article class="rounded-[1.75rem] border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-500 text-white shadow-lg shadow-blue-500/20">
                            <i class="fa fa-check-circle-o fa-2x"></i>
                        </div>
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Processed</span>
                    </div>
                    <h3 class="mt-8 text-4xl font-black text-slate-900">{{ $approvedCount }}</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600">Approved / Completed</p>
                    <p class="mt-4 text-xs leading-5 text-slate-500">Requests that have already moved forward or reached final processing status.</p>
                </article>

                <article class="rounded-[1.75rem] border border-rose-100 bg-gradient-to-br from-rose-50 to-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-500 text-white shadow-lg shadow-rose-500/20">
                            <i class="fa fa-ban fa-2x"></i>
                        </div>
                        <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Closed</span>
                    </div>
                    <h3 class="mt-8 text-4xl font-black text-slate-900">{{ $rejectedCount }}</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600">Rejected / Cancelled</p>
                    <p class="mt-4 text-xs leading-5 text-slate-500">Requests that were not approved or were withdrawn before completion.</p>
                </article>
            </section>

            <section class="grid grid-cols-1 gap-6 xl:grid-cols-[0.92fr_1.08fr]">
                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Submit Request</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-900">Resignation Form</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Provide the official submission date, intended effectivity date, and any supporting explanation for your request.
                            </p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                            <i class="fa fa-pencil-square-o fa-2x"></i>
                        </div>
                    </div>

                    <div class="mt-6 rounded-[1.5rem] border border-emerald-100 bg-gradient-to-r from-emerald-50 to-white p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-semibold text-emerald-900">Before you submit</p>
                                <p class="mt-1 text-xs leading-5 text-emerald-700">Confirm your effective date, department notice period, and turnover expectations.</p>
                            </div>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-emerald-700">HR Review</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('employee.storeResignation') }}" class="mt-6 space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Submitted Date</label>
                                <input
                                    type="date"
                                    name="submitted_at"
                                    value="{{ old('submitted_at', now()->toDateString()) }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    required
                                >
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Effective Date</label>
                                <input
                                    type="date"
                                    name="effective_date"
                                    value="{{ old('effective_date') }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    required
                                >
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Reason</label>
                            <textarea
                                name="reason"
                                rows="5"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="Explain the reason for your resignation, transition concerns, or turnover notes if needed."
                            >{{ old('reason') }}</textarea>
                        </div>

                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                            <i class="fa fa-paper-plane-o"></i>
                            Submit Resignation
                        </button>
                    </form>
                </div>

                <div id="resignation-timeline-section" class="rounded-[2rem] border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-700">Request History</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-900">My Resignation Timeline</h2>
                            <p class="mt-1 text-sm text-slate-500">Track status changes, effective dates, and any admin remarks attached to each request.</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Latest Status</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $latestStatus !== '' ? $latestStatus : 'No Request Yet' }}</p>
                        </div>
                    </div>

                    <div class="max-h-[42rem] space-y-4 overflow-y-auto p-6">
                        @forelse ($resignations as $row)
                            @php
                                $statusText = trim((string) ($row->status ?? 'Pending'));
                                $statusClass = match (strtolower($statusText)) {
                                    'approved' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-rose-100 text-rose-700',
                                    'cancelled' => 'bg-slate-200 text-slate-700',
                                    default => 'bg-amber-100 text-amber-700',
                                };
                                $iconClass = match (strtolower($statusText)) {
                                    'approved' => 'bg-blue-100 text-blue-600',
                                    'completed' => 'bg-emerald-100 text-emerald-600',
                                    'rejected' => 'bg-rose-100 text-rose-600',
                                    'cancelled' => 'bg-slate-200 text-slate-600',
                                    default => 'bg-amber-100 text-amber-600',
                                };
                            @endphp

                            <article class="rounded-[1.5rem] border border-slate-200 bg-gradient-to-r from-white to-slate-50 p-5 shadow-sm">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div class="flex items-start gap-4">
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl {{ $iconClass }}">
                                            <i class="fa fa-briefcase"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-3">
                                                <p class="text-lg font-bold text-slate-900">
                                                    Effective {{ optional($row->effective_date)->format('M d, Y') ?? '-' }}
                                                </p>
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ $statusText }}</span>
                                            </div>

                                            <p class="mt-1 text-sm text-slate-500">
                                                Submitted {{ optional($row->submitted_at)->format('M d, Y') ?? '-' }}
                                            </p>

                                            <div class="mt-4 grid gap-3 md:grid-cols-2">
                                                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3">
                                                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Reason</p>
                                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $row->reason ?: 'No reason provided.' }}</p>
                                                </div>
                                                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3">
                                                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Admin Note</p>
                                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $row->admin_note ?: 'No admin note yet.' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="flex flex-col items-center justify-center rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center">
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-200 text-slate-500">
                                    <i class="fa fa-folder-open fa-2x"></i>
                                </div>
                                <h4 class="mt-5 text-xl font-bold text-slate-900">No resignation requests yet</h4>
                                <p class="mt-2 max-w-md text-sm leading-6 text-slate-500">When you submit a resignation request, it will appear here with its review status and any admin notes.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
    const sidebar = document.querySelector('aside');
    const main = document.querySelector('main');

    (function () {
        const focusId = @json(request()->query('focus'));
        if (!focusId) return;
        const target = document.getElementById(focusId);
        if (!target) return;

        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        target.classList.add('ring-4', 'ring-emerald-300', 'ring-offset-4', 'ring-offset-slate-100', 'transition');

        setTimeout(() => {
            target.classList.remove('ring-4', 'ring-emerald-300', 'ring-offset-4', 'ring-offset-slate-100');
        }, 2200);
    })();

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
</script>
</body>
</html>
