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
<body class="bg-gray-50">
<div class="flex min-h-screen">
    @include('components.employeeSideBar')

    <main class="flex-1 ml-16 transition-all duration-300">
        @include('components.employeeHeader.dashboardHeader', ['name' => Auth::user()?->first_name ?? 'Employee', 'notifications' => 0])

        <div class="p-4 md:p-8 pt-20 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h1 class="text-2xl font-bold text-gray-900">Resignation</h1>
                <p class="text-sm text-gray-600 mt-1">Submit your resignation request and track status updates.</p>
            </div>

            @if (session('success'))
                <div class="rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg border border-rose-300 bg-rose-50 px-4 py-3 text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 lg:col-span-1">
                    <h2 class="text-lg font-semibold text-gray-900">Submit Request</h2>
                    <form method="POST" action="{{ route('employee.storeResignation') }}" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Submitted Date</label>
                            <input type="date" name="submitted_at" value="{{ old('submitted_at', now()->toDateString()) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Effective Date</label>
                            <input type="date" name="effective_date" value="{{ old('effective_date') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Reason</label>
                            <textarea name="reason" rows="4" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="Optional reason...">{{ old('reason') }}</textarea>
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-green-600 text-white px-4 py-2.5 text-sm hover:bg-green-700">
                            Submit Resignation
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-6 lg:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-900">My Requests</h2>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500 border-b">
                                <tr>
                                    <th class="py-2 pr-4">Submitted</th>
                                    <th class="py-2 pr-4">Effective</th>
                                    <th class="py-2 pr-4">Reason</th>
                                    <th class="py-2 pr-4">Status</th>
                                    <th class="py-2 pr-4">Admin Note</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse ($resignations as $row)
                                    @php
                                        $statusText = trim((string) ($row->status ?? 'Pending'));
                                        $statusClass = match (strtolower($statusText)) {
                                            'approved' => 'bg-blue-100 text-blue-700',
                                            'completed' => 'bg-emerald-100 text-emerald-700',
                                            'rejected' => 'bg-rose-100 text-rose-700',
                                            'cancelled' => 'bg-gray-200 text-gray-700',
                                            default => 'bg-amber-100 text-amber-700',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="py-3 pr-4">{{ optional($row->submitted_at)->format('M d, Y') ?? '-' }}</td>
                                        <td class="py-3 pr-4">{{ optional($row->effective_date)->format('M d, Y') ?? '-' }}</td>
                                        <td class="py-3 pr-4 text-gray-700">{{ $row->reason ?: '-' }}</td>
                                        <td class="py-3 pr-4">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td class="py-3 pr-4 text-gray-700">{{ $row->admin_note ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-gray-500">No resignation requests yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
</script>
</body>
</html>

