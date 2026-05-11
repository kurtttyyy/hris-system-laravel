<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Activity Logs | HRIS</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    body { font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif; }
  </style>
</head>
<body class="bg-slate-100 text-slate-900">
@php
  $tabSession = trim((string) request()->query('tab_session', ''));
  $event = $event ?? '';
@endphp

<div class="flex min-h-screen">
  @include('components.adminSideBar')

  <main class="ml-16 min-h-screen flex-1 transition-all duration-300">
    <section class="border-b border-slate-200 bg-white px-6 py-6 md:px-8">
      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700">System Records</p>
          <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-950">Activity Logs</h1>
          <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
            Review admin and employee activity with exact dates, times, and HR notes.
          </p>
        </div>
        <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
          {{ number_format($activityLogs->total()) }} recorded activit{{ $activityLogs->total() === 1 ? 'y' : 'ies' }}
        </div>
      </div>
    </section>

    <section class="space-y-6 p-6 md:p-8">
      @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
          {{ session('success') }}
        </div>
      @endif

      <form method="GET" action="{{ route('admin.activityLogs') }}" class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-[1fr_180px_180px_180px_auto]">
        @if ($tabSession !== '')
          <input type="hidden" name="tab_session" value="{{ $tabSession }}">
        @endif
        <input
          type="search"
          name="search"
          value="{{ $search }}"
          placeholder="Search user, email, action, or note"
          class="rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
        >
        <select name="role" class="rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
          <option value="">All roles</option>
          <option value="Admin" @selected(strtolower($role) === 'admin')>Admin</option>
          <option value="Department Head" @selected(strtolower($role) === 'department head')>Department Head</option>
          <option value="Employee" @selected(strtolower($role) === 'employee')>Employee</option>
        </select>
        <select name="event" class="rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
          <option value="">All types</option>
          <option value="Login" @selected(strtolower($event) === 'login')>Login</option>
          <option value="Logout" @selected(strtolower($event) === 'logout')>Logout</option>
          <option value="Scanned" @selected(strtolower($event) === 'scanned')>Scanned File</option>
          <option value="Downloaded" @selected(strtolower($event) === 'downloaded')>Downloaded File</option>
          <option value="Inserted" @selected(strtolower($event) === 'inserted')>Inserted</option>
          <option value="Updated" @selected(strtolower($event) === 'updated')>Updated</option>
          <option value="Deleted" @selected(strtolower($event) === 'deleted')>Deleted</option>
          <option value="POST" @selected(strtolower($event) === 'post')>Submitted</option>
        </select>
        <input
          type="date"
          name="date"
          value="{{ $date }}"
          class="rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
        >
        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
          <i class="fa-solid fa-filter"></i>
          Filter
        </button>
      </form>

      <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
              <tr>
                <th class="px-4 py-3">Date & Time</th>
                <th class="px-4 py-3">User</th>
                <th class="px-4 py-3">Activity</th>
                <th class="px-4 py-3">Location</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @forelse ($activityLogs as $log)
                @php
                  $method = strtolower((string) $log->method);
                  $activityBadge = match ($method) {
                    'login' => 'bg-blue-100 text-blue-700',
                    'logout' => 'bg-violet-100 text-violet-700',
                    'scanned' => 'bg-cyan-100 text-cyan-700',
                    'downloaded' => 'bg-indigo-100 text-indigo-700',
                    'inserted' => 'bg-emerald-100 text-emerald-700',
                    'updated' => 'bg-amber-100 text-amber-700',
                    'deleted' => 'bg-rose-100 text-rose-700',
                    default => 'bg-slate-100 text-slate-600',
                  };
                  $activityIcon = match ($method) {
                    'login' => 'fa-right-to-bracket',
                    'logout' => 'fa-right-from-bracket',
                    'scanned' => 'fa-file-circle-check',
                    'downloaded' => 'fa-download',
                    'inserted' => 'fa-plus',
                    'updated' => 'fa-pen-to-square',
                    'deleted' => 'fa-trash',
                    default => 'fa-circle-info',
                  };
                @endphp
                <tr class="align-top">
                  <td class="whitespace-nowrap px-4 py-4 text-slate-600">
                    <div class="font-semibold text-slate-900">{{ optional($log->created_at)->format('M j, Y') }}</div>
                    <div class="text-xs text-slate-500">{{ optional($log->created_at)->format('g:i:s A') }}</div>
                  </td>
                  <td class="px-4 py-4">
                    <div class="font-semibold text-slate-900">{{ $log->user_name ?: 'Unknown user' }}</div>
                    <div class="break-all text-xs text-slate-500">{{ $log->user_email ?: 'No email' }}</div>
                    <span class="mt-2 inline-flex rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700">
                      {{ $log->user_role ?: 'User' }}
                    </span>
                  </td>
                  <td class="px-4 py-4">
                    <div class="font-semibold text-slate-900">{{ $log->action ?: 'System activity' }}</div>
                    <div class="mt-1 text-xs leading-5 text-slate-500">{{ $log->description }}</div>
                    <div class="mt-2 inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-xs font-semibold {{ $activityBadge }}">
                      <i class="fa-solid {{ $activityIcon }}"></i>
                      {{ $log->method }}
                    </div>
                  </td>
                  <td class="px-4 py-4">
                    <div class="break-all text-xs font-semibold text-slate-700">{{ $log->path }}</div>
                    <div class="mt-1 break-all text-xs text-slate-500">{{ $log->route_name }}</div>
                    <div class="mt-1 text-xs text-slate-400">{{ $log->ip_address }}</div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="px-4 py-12 text-center text-sm text-slate-500">
                    No activity logs found.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div>
        {{ $activityLogs->links() }}
      </div>
    </section>
  </main>
</div>

<script>
  const sidebar = document.querySelector('aside');
  const main = document.querySelector('main');
  if (sidebar && main) {
    sidebar.addEventListener('mouseenter', function() {
      main.classList.remove('ml-16');
      main.classList.add('ml-64');
    });
    sidebar.addEventListener('mouseleave', function() {
      main.classList.remove('ml-64');
      main.classList.add('ml-16');
    });
  }
</script>
</body>
</html>
