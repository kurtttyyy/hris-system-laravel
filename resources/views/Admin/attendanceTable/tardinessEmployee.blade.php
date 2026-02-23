<div class="bg-white rounded-xl border border-gray-200 p-4">
  <h3 class="text-sm font-semibold text-gray-700 mb-3">Tardiness Employees</h3>
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-100 text-slate-700">
        <tr>
          <th class="px-3 py-2 text-left">Employee ID</th>
          <th class="px-3 py-2 text-left">Name</th>
          <th class="px-3 py-2 text-left">Gate</th>
          <th class="px-3 py-2 text-left">Date</th>
          <th class="px-3 py-2 text-left">AM In</th>
          <th class="px-3 py-2 text-left">AM Out</th>
          <th class="px-3 py-2 text-left">PM In</th>
          <th class="px-3 py-2 text-left">PM Out</th>
          <th class="px-3 py-2 text-left">Late Duration</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($rows as $row)
          <tr class="border-b border-slate-100">
            <td class="px-3 py-2">{{ $row->employee_id }}</td>
            <td class="px-3 py-2">{{ $row->employee_name ?? '-' }}</td>
            <td class="px-3 py-2">{{ $row->main_gate ?? '-' }}</td>
            <td class="px-3 py-2">{{ optional($row->attendance_date)->format('Y-m-d') ?? '-' }}</td>
            <td class="px-3 py-2">{{ $row->morning_in ? \Carbon\Carbon::parse($row->morning_in)->format('h:i A') : '-' }}</td>
            <td class="px-3 py-2">{{ $row->morning_out ? \Carbon\Carbon::parse($row->morning_out)->format('h:i A') : '-' }}</td>
            <td class="px-3 py-2">{{ $row->afternoon_in ? \Carbon\Carbon::parse($row->afternoon_in)->format('h:i A') : '-' }}</td>
            <td class="px-3 py-2">{{ $row->afternoon_out ? \Carbon\Carbon::parse($row->afternoon_out)->format('h:i A') : '-' }}</td>
            <td class="px-3 py-2 font-semibold text-amber-700">
              @php
                $lateMinutes = (int) ($row->late_minutes ?? 0);
                $lateHours = intdiv($lateMinutes, 60);
                $remainingMinutes = $lateMinutes % 60;
                $hourText = $lateHours === 1 ? 'hour' : 'hours';
                $minuteText = $remainingMinutes === 1 ? 'minute' : 'minutes';
              @endphp
              @if ($lateMinutes <= 0)
                -
              @elseif ($lateHours > 0 && $remainingMinutes > 0)
                {{ $lateHours }} {{ $hourText }} {{ $remainingMinutes }} {{ $minuteText }} late
              @elseif ($lateHours > 0)
                {{ $lateHours }} {{ $hourText }} late
              @else
                {{ $remainingMinutes }} {{ $minuteText }} late
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="px-3 py-4 text-center text-gray-500">No tardiness records found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

