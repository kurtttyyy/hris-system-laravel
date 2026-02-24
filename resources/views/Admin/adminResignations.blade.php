<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Resignations</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    body { font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif; transition: margin-left 0.3s ease; }
    main { transition: margin-left 0.3s ease; }
    aside ~ main { margin-left: 16rem; }
  </style>
</head>
<body class="bg-slate-100">
<div class="flex min-h-screen">
  @include('components.adminSideBar')

  <main class="flex-1 ml-16 transition-all duration-300">
    <div class="p-4 md:p-8 pt-10 space-y-6">
      <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h1 class="text-2xl font-semibold text-slate-800">Resignations</h1>
        <p class="text-sm text-slate-500 mt-2">Create and manage employee resignation records.</p>
      </div>

      @if (session('success'))
        <div class="rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-700">
          {{ session('success') }}
        </div>
      @endif
      @if (session('error'))
        <div class="rounded-lg border border-rose-300 bg-rose-50 px-4 py-3 text-rose-700">
          {{ session('error') }}
        </div>
      @endif
      @if ($errors->any())
        <div class="rounded-lg border border-rose-300 bg-rose-50 px-4 py-3 text-rose-700">
          {{ $errors->first() }}
        </div>
      @endif

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4">
          <p class="text-xs text-slate-500">Pending</p>
          <p id="resignation-count-pending" class="text-2xl font-semibold text-amber-600">{{ (int) ($statusCounts['Pending'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
          <p class="text-xs text-slate-500">Approved</p>
          <p id="resignation-count-approved" class="text-2xl font-semibold text-blue-600">{{ (int) ($statusCounts['Approved'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
          <p class="text-xs text-slate-500">Rejected</p>
          <p id="resignation-count-rejected" class="text-2xl font-semibold text-rose-600">{{ (int) ($statusCounts['Rejected'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
          <p class="text-xs text-slate-500">Cancelled</p>
          <p id="resignation-count-cancelled" class="text-2xl font-semibold text-slate-700">{{ (int) ($statusCounts['Cancelled'] ?? 0) }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6 xl:col-span-1">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-slate-800">Pending Requests</h2>
            <span id="pending-requests-badge" class="px-2 py-1 rounded-full text-xs bg-amber-100 text-amber-700">
              {{ $pendingResignations->count() }}
            </span>
          </div>

          <div id="pending-requests-list" class="space-y-3 max-h-[560px] overflow-y-auto pr-1">
            @forelse ($pendingResignations as $pending)
              <div class="rounded-lg border border-slate-200 p-3" data-pending-item="{{ $pending->id }}">
                <p class="font-medium text-slate-800">{{ $pending->employee_name }}</p>
                <p class="text-xs text-slate-500 mt-1">
                  {{ optional($pending->submitted_at)->format('M d, Y') ?? '-' }}
                  to
                  {{ optional($pending->effective_date)->format('M d, Y') ?? '-' }}
                </p>
                <p class="text-xs text-slate-500 mt-1">
                  {{ $pending->employee_id ?: '-' }} - {{ $pending->department ?: 'N/A' }}
                </p>
                <p class="text-xs text-slate-600 mt-2">{{ $pending->reason ?: 'No reason provided.' }}</p>

                <div class="grid grid-cols-2 gap-2 mt-3">
                  <form method="POST" action="{{ route('admin.updateResignationStatus', $pending->id) }}" class="js-pending-action-form">
                    @csrf
                    <input type="hidden" name="status" value="Approved">
                    <input type="hidden" name="admin_note" value="">
                    <button type="submit" class="w-full rounded bg-emerald-600 text-white px-2 py-1.5 text-xs hover:bg-emerald-700">
                      Approve
                    </button>
                  </form>
                  <form method="POST" action="{{ route('admin.updateResignationStatus', $pending->id) }}" class="js-pending-action-form">
                    @csrf
                    <input type="hidden" name="status" value="Rejected">
                    <input type="hidden" name="admin_note" value="">
                    <button type="submit" class="w-full rounded bg-rose-600 text-white px-2 py-1.5 text-xs hover:bg-rose-700">
                      Reject
                    </button>
                  </form>
                </div>
              </div>
            @empty
              <div id="pending-empty-state" class="rounded-lg border border-slate-200 p-4 text-sm text-slate-500">
                No pending resignation requests.
              </div>
            @endforelse
          </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 xl:col-span-2">
          <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
            <h2 class="text-lg font-semibold text-slate-800">Resignation Records</h2>
            <form method="GET" action="{{ route('admin.adminResignations') }}" class="flex gap-2">
              <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search employee, ID, dept..."
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm w-56"
              >
              <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                @foreach (['All', 'Pending', 'Approved', 'Completed', 'Rejected', 'Cancelled'] as $statusOption)
                  <option value="{{ $statusOption }}" {{ strcasecmp((string) $selectedStatus, $statusOption) === 0 ? 'selected' : '' }}>
                    {{ $statusOption }}
                  </option>
                @endforeach
              </select>
              <button type="submit" class="rounded-lg bg-slate-900 text-white px-4 py-2 text-sm">Filter</button>
            </form>
          </div>

          <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="text-left text-slate-500 border-b">
                <tr>
                  <th class="py-2 pr-4">Employee</th>
                  <th class="py-2 pr-4">Submitted</th>
                  <th class="py-2 pr-4">Effective</th>
                  <th class="py-2 pr-4">Status</th>
                  <th class="py-2 pr-4">Action</th>
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
                      'cancelled' => 'bg-slate-200 text-slate-700',
                      default => 'bg-amber-100 text-amber-700',
                    };
                  @endphp
                  <tr>
                    <td class="py-3 pr-4">
                      <p class="font-medium text-slate-800">{{ $row->employee_name }}</p>
                      <p class="text-xs text-slate-500">
                        {{ $row->employee_id ?: '-' }} • {{ $row->department ?: 'N/A' }} • {{ $row->position ?: 'N/A' }}
                      </p>
                    </td>
                    <td class="py-3 pr-4">{{ optional($row->submitted_at)->format('M d, Y') ?? '-' }}</td>
                    <td class="py-3 pr-4">{{ optional($row->effective_date)->format('M d, Y') ?? '-' }}</td>
                    <td class="py-3 pr-4">
                      <span class="px-2 py-1 rounded-full text-xs {{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                    <td class="py-3 pr-4">
                      <form method="POST" action="{{ route('admin.updateResignationStatus', $row->id) }}" class="flex gap-2 items-center">
                        @csrf
                        <select name="status" class="rounded border border-slate-300 px-2 py-1 text-xs">
                          @foreach (['Pending', 'Approved', 'Completed', 'Rejected', 'Cancelled'] as $option)
                            <option value="{{ $option }}" {{ strcasecmp($statusText, $option) === 0 ? 'selected' : '' }}>{{ $option }}</option>
                          @endforeach
                        </select>
                        <input type="text" name="admin_note" value="{{ $row->admin_note }}" placeholder="Note" class="rounded border border-slate-300 px-2 py-1 text-xs w-36">
                        <button type="submit" class="rounded bg-emerald-600 text-white px-2 py-1 text-xs">Update</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="py-6 text-center text-slate-500">No resignation records found.</td>
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
      main.classList.add('ml-64');
    });
    sidebar.addEventListener('mouseleave', function() {
      main.classList.remove('ml-64');
      main.classList.add('ml-16');
    });
  }

  const pendingActionForms = Array.from(document.querySelectorAll('.js-pending-action-form'));
  const pendingBadge = document.getElementById('pending-requests-badge');
  const pendingList = document.getElementById('pending-requests-list');
  const pendingCardCount = document.getElementById('resignation-count-pending');
  const approvedCardCount = document.getElementById('resignation-count-approved');
  const rejectedCardCount = document.getElementById('resignation-count-rejected');
  const cancelledCardCount = document.getElementById('resignation-count-cancelled');

  function applyStatusCounts(counts) {
    if (!counts || typeof counts !== 'object') return;
    if (pendingCardCount && counts.Pending !== undefined) pendingCardCount.textContent = String(counts.Pending);
    if (approvedCardCount && counts.Approved !== undefined) approvedCardCount.textContent = String(counts.Approved);
    if (rejectedCardCount && counts.Rejected !== undefined) rejectedCardCount.textContent = String(counts.Rejected);
    if (cancelledCardCount && counts.Cancelled !== undefined) cancelledCardCount.textContent = String(counts.Cancelled);
    if (pendingBadge && counts.Pending !== undefined) pendingBadge.textContent = String(counts.Pending);
  }

  function ensurePendingEmptyState() {
    if (!pendingList) return;
    const pendingItems = pendingList.querySelectorAll('[data-pending-item]');
    let emptyState = document.getElementById('pending-empty-state');
    if (pendingItems.length > 0) {
      if (emptyState) emptyState.remove();
      return;
    }

    if (!emptyState) {
      emptyState = document.createElement('div');
      emptyState.id = 'pending-empty-state';
      emptyState.className = 'rounded-lg border border-slate-200 p-4 text-sm text-slate-500';
      emptyState.textContent = 'No pending resignation requests.';
      pendingList.appendChild(emptyState);
    }
  }

  pendingActionForms.forEach((form) => {
    form.addEventListener('submit', async function (event) {
      event.preventDefault();
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
          body: new FormData(form),
        });

        if (!response.ok) {
          throw new Error('Failed to update resignation status.');
        }

        const payload = await response.json();
        applyStatusCounts(payload.statusCounts || null);

        const pendingItem = form.closest('[data-pending-item]');
        if (pendingItem) {
          pendingItem.remove();
          ensurePendingEmptyState();
        }
      } catch (error) {
        window.location.reload();
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }
    });
  });

</script>
</body>
</html>
