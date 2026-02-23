<div class="bg-white rounded-xl border border-gray-200 p-4">
  <h3 class="text-sm font-semibold text-gray-700 mb-3">Absent Employees (No Attendance Record)</h3>
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-100 text-slate-700">
        <tr>
          <th class="px-3 py-2 text-left">Employee ID</th>
          <th class="px-3 py-2 text-left">Name</th>
          <th class="px-3 py-2 text-left">Gate</th>
          <th class="px-3 py-2 text-left">Date</th>
          <th class="px-3 py-2 text-left">Remarks</th>
          <th class="px-3 py-2 text-left">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($rows as $row)
          <tr class="border-b border-slate-100">
            <td class="px-3 py-2">{{ $row->employee_id }}</td>
            <td class="px-3 py-2">{{ $row->employee_name ?? '-' }}</td>
            <td class="px-3 py-2">{{ $row->main_gate ?? '-' }}</td>
            <td class="px-3 py-2">{{ optional($row->attendance_date)->format('Y-m-d') ?? '-' }}</td>
            <td class="px-3 py-2">No attendance record</td>
            <td class="px-3 py-2">
              <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                Absent
              </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-3 py-4 text-center text-gray-500">No absent records found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
