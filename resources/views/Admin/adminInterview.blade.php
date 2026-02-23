<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PeopleHub – HR Dashboard</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
        body { font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif; transition: margin-left 0.3s ease; }
        main { transition: margin-left 0.3s ease; }
        aside ~ main { margin-left: 16rem; }
  </style>
</head>
<body class="bg-slate-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
        @include('components.adminSideBar')


    <!-- Main Content -->
  <main class="flex-1 ml-16 transition-all duration-300">
    <!-- Header -->
     @include('components.adminHeader.interviewHeader')

    <!-- Dashboard Content -->
    <div class="p-4 md:p-8 space-y-6 pt-20">

        <!-- STATS -->
<!-- STATS -->
<div class="grid grid-cols-4 gap-6 mb-8">

    <!-- Today -->
    <div class="relative bg-white p-6 rounded-xl border">
        <div class="absolute top-4 right-4 w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
            📅
        </div>
        <p class="text-sm text-slate-400">Today</p>
        <p id="todayCountValue" class="text-3xl font-bold mt-2">{{$count_daily}}</p>
        <p class="text-sm text-slate-400">Today's Interviews</p>
    </div>

    <!-- Completed -->
    <div class="relative bg-white p-6 rounded-xl border">
        <div class="absolute top-4 right-4 w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
            ✔️
        </div>
        <p class="text-sm text-green-500">+15%</p>
        <p id="monthCountValue" class="text-3xl font-bold mt-2">{{$count_month}}</p>
        <p class="text-sm text-slate-400">Completed This Month</p>
    </div>

    <!-- Upcoming -->
    <div class="relative bg-white p-6 rounded-xl border">
        <div class="absolute top-4 right-4 w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
            ⏰
        </div>
        <p class="text-sm text-orange-500">Yearly</p>
        <p id="yearCountValue" class="text-3xl font-bold mt-2">{{$count_year}}</p>
        <p class="text-sm text-slate-400">Completed this year</p>
    </div>

    <!-- Rating -->
    <div class="relative bg-white p-6 rounded-xl border">
        <div class="absolute top-4 right-4 w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-purple-600">
            ⏰
        </div>
        <p class="text-sm text-orange-500">Upcoming</p>
        <p id="upcomingCountValue" class="text-3xl font-bold mt-2">{{$count_upcoming}}</p>
        <p class="text-sm text-slate-400">Scheduled</p>
    </div>
</div>
        <div class="bg-white rounded-xl border p-6 interview-wrapper">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-lg">Interview Schedule</h2>
                <p class="text-sm text-slate-400">TODAY – {{ now()->format('F j, Y') }}</p>
            </div>

            <h3 class="text-sm font-semibold text-indigo-700 mb-3">Upcoming Interviews</h3>
            <div id="upcomingInterviewList">
            @forelse($upcomingInterviews as $inter)
                <div
                    class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 mb-4 flex justify-between interview-card"
                    data-scheduled-date="{{ $inter->date->format('Y-m-d') }}"
                    data-scheduled-time="{{ \Carbon\Carbon::parse($inter->time)->format('H:i:s') }}"
                    data-duration-minutes="{{ (int) filter_var($inter->duration, FILTER_SANITIZE_NUMBER_INT) }}"
                >
                    <div class="flex gap-6">
                        <div class="text-indigo-600 font-bold text-xl">
                            {{ \Carbon\Carbon::parse($inter->time)->format('h:i') }}
                            <p class="text-xs font-normal">{{ \Carbon\Carbon::parse($inter->time)->format('A') }}</p>
                        </div>

                        <div>
                            <p class="font-semibold">{{$inter->interview_type}} – {{$inter->applicant->first_name}} {{$inter->applicant->last_name}}</p>
                            <p class="text-sm text-slate-500">{{ $inter->applicant->position->title ?? $inter->applicant->applied_position ?? '-' }}</p>
                            <p class="text-sm text-slate-400 mt-1">⏱ {{$inter->duration}} · 👥 {{$inter->interviewers}}</p>
                            <p class="text-xs text-indigo-600 mt-1" data-role="time-remaining"></p>

                            <div class="mt-3 flex gap-3">
                                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm" onclick="scheduleInterview({{ $inter->applicant_id }})">
                                    Reschedule
                                </button>
                                <form action="{{ route('admin.interviewCancel', $inter->applicant_id)}}" method="POST">
                                    @csrf
                                    <button class="border px-4 py-2 rounded-lg text-sm" type='submit'>Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <span class="hidden next-interview-label bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs">Next Interview</span>
                        <span class="bg-indigo-100 text-indigo-600 px-4 py-1 rounded-full text-sm h-fit">
                            @if($inter->date->isToday())
                                Today
                            @else
                                Upcoming
                            @endif
                        </span>
                    </div>
                </div>
            @empty
                <div id="upcomingEmptyState" class="rounded-lg border border-dashed border-slate-300 p-4 text-sm text-slate-500 mb-6">
                    No upcoming interviews.
                </div>
            @endforelse
            </div>

            <h3 class="text-sm font-semibold text-emerald-700 mb-3 mt-6">Completed Interviews</h3>
            <div id="completedInterviewList">
            @forelse($completedInterviews as $inter)
                <div
                    class="bg-emerald-50 border border-emerald-100 rounded-xl p-5 mb-4 flex justify-between opacity-80 completed-card"
                    data-scheduled-date="{{ $inter->date->format('Y-m-d') }}"
                    data-scheduled-time="{{ \Carbon\Carbon::parse($inter->time)->format('H:i:s') }}"
                    data-duration-minutes="{{ (int) filter_var($inter->duration, FILTER_SANITIZE_NUMBER_INT) }}"
                >
                    <div class="flex gap-6">
                        <div class="text-emerald-700 font-bold text-xl">
                            {{ \Carbon\Carbon::parse($inter->time)->format('h:i') }}
                            <p class="text-xs font-normal">{{ \Carbon\Carbon::parse($inter->time)->format('A') }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">{{$inter->interview_type}} – {{$inter->applicant->first_name}} {{$inter->applicant->last_name}}</p>
                            <p class="text-sm text-slate-500">{{ $inter->applicant->position->title ?? $inter->applicant->applied_position ?? '-' }}</p>
                            <p class="text-sm text-slate-400 mt-1">⏱ {{$inter->duration}} · 👥 {{$inter->interviewers}}</p>
                            <p class="text-xs text-emerald-700 mt-1">Completed</p>
                        </div>
                    </div>
                    <span class="bg-emerald-100 text-emerald-700 px-4 py-1 rounded-full text-sm h-fit">Completed</span>
                </div>
            @empty
                <div id="completedEmptyState" class="rounded-lg border border-dashed border-slate-300 p-4 text-sm text-slate-500">
                    No completed interviews yet.
                </div>
            @endforelse
            </div>
        </div>
        <!-- ===================== SCHEDULE INTERVIEW MODAL ===================== -->
        <div id="scheduleInterviewModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center">

            <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden">

                <!-- Header -->
                <div class="bg-purple-600 px-6 py-4 flex justify-between items-center">
                <h2 class="text-white font-semibold text-lg">Schedule Interview</h2>
                <button onclick="closeScheduleModal()" class="text-white text-xl hover:text-gray-200">&times;</button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-4">

                <!-- Applicant Info -->
                <div class="flex items-center gap-4 bg-purple-50 p-4 rounded-lg">
                    <div class="w-12 h-12 bg-blue-400 text-white rounded-full flex items-center justify-center font-bold">SM</div>
                    <div>
                    <p class="font-medium text-gray-800" id ="name"></p>
                    <p class="text-sm text-gray-500" id ="title"></p>
                    </div>
                </div>

                <!-- Form -->
                <form class="space-y-4" action = "{{ route('admin.storeUpdatedInterview') }}" method="POST" id="form">
                    @csrf
                    <!-- Interview Type -->
                    <input type="hidden" id="interview_id" name="interviewId">
                    <input type="hidden" id="applicants_id" name="applicantId">
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Interview Type</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
                    id = "interview_type"
                    name = "interview_type">
                        <option value="HR Interview">HR Interview</option>
                        <option value="Final Interview">Final Interview</option>
                    </select>
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
                        name="date" id="date">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                        <input type="time" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
                        name="time" id="time">
                    </div>
                    </div>

                    <!-- Duration -->
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
                    name="duration"
                    id="duration">
                        <option value="5 minutes">5 minutes</option>
                        <option value="30 minutes">30 minutes</option>
                        <option value="45 minutes">45 minutes</option>
                        <option value="60 minutes">60 minutes</option>
                        <option value="90 minutes">90 minutes</option>
                    </select>
                    </div>

                    <!-- Interviewers -->
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Interviewer(s)</label>
                    <input type="text" placeholder="Enter interviewer name(s)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
                    name="interviewers" id="interviewers"
                    >
                    </div>

                    <!-- Email Link -->
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Link</label>
                    <input type="email" placeholder="Enter Email Address: " class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
                    name="email_link" id="email_link">
                    </div>

                    <!-- Meeting Link -->
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Link (Optional)</label>
                    <input type="url" placeholder="https://meet.google.com/..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
                    name="url" id="url">
                    </div>

                    <!-- Notes -->
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                    <textarea placeholder="Add any additional notes or instructions..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500 h-24 resize-none"
                    name="notes" id="notes"></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 mt-2">
                    <button type="button" onclick="closeScheduleModal()" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700">Schedule Interview</button>
                    </div>

                </form>

                </div>

            </div>
        </div>
    </div>
  </main>
</div>

</body>
</html>
<script>
    function scheduleInterview(appId) {

    if (!appId) {
        alert('Please select an applicant first.');
        return;
    }

    fetch(`/system/interviewers/ID/${appId}`)
        .then(res => res.json())
        .then(data => {
        // Basic applicant info
        document.getElementById('interview_id').value = data.id;
        document.getElementById('applicants_id').value = data.applicant_id;
        document.getElementById('name').innerText = data.name;
        document.getElementById('title').innerText = data.title;
        document.getElementById('interview_type').value = data.interview_type;
        document.getElementById('date').value = data.date;
        document.getElementById('interviewers').value = data.interviewers;
        document.getElementById('duration').value = data.duration;
        document.getElementById('time').value = data.time;
        document.getElementById('email_link').value = data.email_link;
        document.getElementById('url').value = data.url;
        document.getElementById('notes').value = data.notes;
        });

    document.getElementById('scheduleInterviewModal').classList.remove('hidden');
    }


  function closeScheduleModal() {
    document.getElementById('scheduleInterviewModal').classList.add('hidden');
  }
</script>

<script>
  function ensureEmptyState(containerId, emptyId, message) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const hasCards = container.querySelectorAll('.interview-card, .completed-card').length > 0;
    let emptyState = document.getElementById(emptyId);

    if (!hasCards && !emptyState) {
      emptyState = document.createElement('div');
      emptyState.id = emptyId;
      emptyState.className = 'rounded-lg border border-dashed border-slate-300 p-4 text-sm text-slate-500 mb-6';
      emptyState.textContent = message;
      container.appendChild(emptyState);
    }

    if (hasCards && emptyState) {
      emptyState.remove();
    }
  }

  function moveCardToCompleted(card) {
    const completedList = document.getElementById('completedInterviewList');
    if (!completedList || card.dataset.movedToCompleted === '1') return;

    card.dataset.movedToCompleted = '1';
    card.classList.remove('bg-indigo-50', 'border-indigo-100');
    card.classList.add('bg-emerald-50', 'border-emerald-100', 'opacity-80', 'completed-card');

    const remainingEl = card.querySelector('[data-role="time-remaining"]');
    if (remainingEl) {
      remainingEl.textContent = 'Completed';
      remainingEl.classList.remove('text-indigo-600');
      remainingEl.classList.add('text-emerald-700');
    }

    const nextLabel = card.querySelector('.next-interview-label');
    if (nextLabel) nextLabel.classList.add('hidden');

    const actionWrap = card.querySelector('.mt-3.flex.gap-3');
    if (actionWrap) actionWrap.remove();

    const statusBadge = card.querySelector('.bg-indigo-100.text-indigo-600');
    if (statusBadge) {
      statusBadge.classList.remove('bg-indigo-100', 'text-indigo-600');
      statusBadge.classList.add('bg-emerald-100', 'text-emerald-700');
      statusBadge.textContent = 'Completed';
    }

    completedList.prepend(card);
  }

  function refreshUpcomingCount() {
    const upcomingCountEl = document.getElementById('upcomingCountValue');
    const upcomingList = document.getElementById('upcomingInterviewList');
    if (!upcomingCountEl || !upcomingList) return;
    const count = upcomingList.querySelectorAll('.interview-card').length;
    upcomingCountEl.textContent = String(count);
  }

  function refreshCompletedStats() {
    const todayCountEl = document.getElementById('todayCountValue');
    const monthCountEl = document.getElementById('monthCountValue');
    const yearCountEl = document.getElementById('yearCountValue');
    if (!todayCountEl || !monthCountEl || !yearCountEl) return;

    const now = new Date();
    const nowMs = now.getTime();
    const completedCards = Array.from(document.querySelectorAll('[data-scheduled-date][data-scheduled-time][data-duration-minutes]'))
      .filter((card) => {
        const scheduledDate = card.dataset.scheduledDate;
        const scheduledTime = card.dataset.scheduledTime;
        const durationMinutes = parseInt(card.dataset.durationMinutes || '0', 10);
        if (!scheduledDate || !scheduledTime || Number.isNaN(durationMinutes)) return false;
        const startMs = new Date(`${scheduledDate}T${scheduledTime}`).getTime();
        if (Number.isNaN(startMs)) return false;
        const endMs = startMs + (durationMinutes * 60 * 1000);
        return nowMs >= endMs;
      });

    let todayCount = 0;
    let monthCount = 0;
    let yearCount = 0;

    completedCards.forEach((card) => {
      const scheduledDate = card.dataset.scheduledDate;
      const scheduledTime = card.dataset.scheduledTime;
      const durationMinutes = parseInt(card.dataset.durationMinutes || '0', 10);
      const startMs = new Date(`${scheduledDate}T${scheduledTime}`).getTime();
      const endDate = new Date(startMs + (durationMinutes * 60 * 1000));

      if (
        endDate.getFullYear() === now.getFullYear() &&
        endDate.getMonth() === now.getMonth() &&
        endDate.getDate() === now.getDate()
      ) {
        todayCount += 1;
      }

      if (
        endDate.getFullYear() === now.getFullYear() &&
        endDate.getMonth() === now.getMonth()
      ) {
        monthCount += 1;
      }

      if (endDate.getFullYear() === now.getFullYear()) {
        yearCount += 1;
      }
    });

    todayCountEl.textContent = String(todayCount);
    monthCountEl.textContent = String(monthCount);
    yearCountEl.textContent = String(yearCount);
  }

  function formatRemainingTime(ms) {
    const totalSeconds = Math.max(0, Math.floor(ms / 1000));
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;

    if (hours > 0) return `${hours}h ${minutes}m ${seconds}s`;
    if (minutes > 0) return `${minutes}m ${seconds}s`;
    return `${seconds}s`;
  }

  function updateInterviewCards() {
    const nowMs = Date.now();
    const cards = Array.from(document.querySelectorAll('.interview-card'));
    let nextCard = null;
    let nextStartMs = Number.POSITIVE_INFINITY;

    cards.forEach((card) => {
      const scheduledDate = card.dataset.scheduledDate;
      const scheduledTime = card.dataset.scheduledTime;
      const durationMinutes = parseInt(card.dataset.durationMinutes || '0', 10);
      const remainingEl = card.querySelector('[data-role="time-remaining"]');
      const nextLabel = card.querySelector('.next-interview-label');

      if (nextLabel) nextLabel.classList.add('hidden');
      if (!scheduledDate || !scheduledTime) return;

      // Build local datetime from stored schedule values to avoid timezone offset issues.
      const scheduledMs = new Date(`${scheduledDate}T${scheduledTime}`).getTime();
      if (Number.isNaN(scheduledMs)) return;
      if (Number.isNaN(durationMinutes)) return;

      const endMs = scheduledMs + (durationMinutes * 60 * 1000);

      if (nowMs >= endMs) {
        moveCardToCompleted(card);
      } else {
        card.classList.remove('hidden');
        card.classList.remove('opacity-70');

        if (nowMs < scheduledMs) {
          if (remainingEl) {
            remainingEl.textContent = `Starts in ${formatRemainingTime(scheduledMs - nowMs)}`;
          }

          if (scheduledMs < nextStartMs) {
            nextStartMs = scheduledMs;
            nextCard = card;
          }
        } else if (remainingEl) {
          remainingEl.textContent = 'In progress';
        }
      }

      const wrapper = card.closest('.interview-wrapper');
      if (wrapper) {
        wrapper.classList.remove('hidden');
      }
    });

    if (nextCard) {
      const nextLabel = nextCard.querySelector('.next-interview-label');
      if (nextLabel) nextLabel.classList.remove('hidden');
    }

    refreshUpcomingCount();
    refreshCompletedStats();
    ensureEmptyState('upcomingInterviewList', 'upcomingEmptyState', 'No upcoming interviews.');
    ensureEmptyState('completedInterviewList', 'completedEmptyState', 'No completed interviews yet.');
  }

  updateInterviewCards();
  setInterval(updateInterviewCards, 1000);

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

</html>
