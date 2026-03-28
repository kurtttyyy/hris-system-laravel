<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PeopleHub - HR Dashboard</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif; transition: margin-left 0.3s ease; }
    main { transition: margin-left 0.3s ease; }
    aside ~ main { margin-left: 16rem; }
  </style>
</head>
<body class="min-h-screen bg-[linear-gradient(180deg,#f8fbff_0%,#f1f5f9_45%,#eefbf6_100%)]">

@php
  $positionOptions = collect($applicant ?? [])
    ->map(fn($app) => trim((string) optional($app->position)->title))
    ->filter(fn($value) => $value !== '')
    ->unique()
    ->sort()
    ->values();

  $statusOptions = collect($applicant ?? [])
    ->map(fn($app) => trim((string) ($app->application_status ?? '')))
    ->filter(fn($value) => $value !== '')
    ->unique()
    ->sort()
    ->values();
@endphp

<div class="flex min-h-screen">
  @include('components.adminSideBar')

  <main class="flex-1 ml-16 transition-all duration-300">
    @include('components.adminHeader.applicantHeader')

    <div class="p-4 md:p-8 space-y-6 pt-20">
      <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[1.75rem] border border-white/80 bg-white/90 p-5 shadow-[0_18px_40px_rgba(15,23,42,0.06)] backdrop-blur">
          <div class="flex items-start justify-between gap-4">
            <div>
              <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Total Applicants</p>
              <p class="mt-3 text-4xl font-black tracking-tight text-slate-900">{{ $count_applicant }}</p>
              <p class="mt-1 text-sm text-slate-500">All candidate submissions</p>
            </div>
            <div class="text-right">
              <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                <i class="fa-solid fa-users"></i>
              </div>
              <span class="mt-3 inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-600">+12%</span>
            </div>
          </div>
        </div>

        <div class="rounded-[1.75rem] border border-white/80 bg-white/90 p-5 shadow-[0_18px_40px_rgba(15,23,42,0.06)] backdrop-blur">
          <div class="flex items-start justify-between gap-4">
            <div>
              <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Under Review</p>
              <p class="mt-3 text-4xl font-black tracking-tight text-slate-900">{{ $count_under_review }}</p>
              <p class="mt-1 text-sm text-slate-500">Applicants awaiting next step</p>
            </div>
            <div class="text-right">
              <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-100 text-yellow-600">
                <i class="fa-regular fa-clock"></i>
              </div>
              <span class="mt-3 inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-600">Pending</span>
            </div>
          </div>
        </div>

        <div class="rounded-[1.75rem] border border-white/80 bg-white/90 p-5 shadow-[0_18px_40px_rgba(15,23,42,0.06)] backdrop-blur">
          <div class="flex items-start justify-between gap-4">
            <div>
              <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Interviews Scheduled</p>
              <p class="mt-3 text-4xl font-black tracking-tight text-slate-900">{{ $count_final_interview }}</p>
              <p class="mt-1 text-sm text-slate-500">Candidates moved into interview stage</p>
            </div>
            <div class="text-right">
              <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-600">
                <i class="fa-regular fa-calendar"></i>
              </div>
              <span class="mt-3 inline-flex rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-semibold text-indigo-600">This Week</span>
            </div>
          </div>
        </div>

        <div class="rounded-[1.75rem] border border-white/80 bg-white/90 p-5 shadow-[0_18px_40px_rgba(15,23,42,0.06)] backdrop-blur">
          <div class="flex items-start justify-between gap-4">
            <div>
              <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Hired This Month</p>
              <p class="mt-3 text-4xl font-black tracking-tight text-slate-900">{{ $hired }}</p>
              <p class="mt-1 text-sm text-slate-500">Successful hires completed</p>
            </div>
            <div class="text-right">
              <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-100 text-purple-600">
                <i class="fa-solid fa-check"></i>
              </div>
              <span class="mt-3 inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-600">+8%</span>
            </div>
          </div>
        </div>
      </div>

      <div class="rounded-[2rem] border border-white/80 bg-white/92 p-6 shadow-[0_20px_50px_rgba(15,23,42,0.08)] backdrop-blur">
        <div class="flex flex-col gap-4 mb-6 xl:flex-row xl:items-end xl:justify-between">
          <div>
            <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-sky-700">
              Recruitment Pipeline
            </div>
            <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-900">Recent Applicants</h2>
            <p class="mt-1 text-sm text-slate-500">Review candidate details, filter the pipeline, and open actions directly from the list.</p>
          </div>

          <div class="grid gap-3 sm:grid-cols-3 xl:min-w-[720px]">
            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm">
              <i class="fa-solid fa-layer-group text-slate-400"></i>
              <select id="applicantPositionFilter" class="w-full bg-transparent outline-none text-slate-700">
                <option value="">All Positions</option>
                @foreach ($positionOptions as $positionOption)
                  <option value="{{ strtolower($positionOption) }}">{{ $positionOption }}</option>
                @endforeach
              </select>
            </label>
            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm">
              <i class="fa-solid fa-signal text-slate-400"></i>
              <select id="applicantStatusFilter" class="w-full bg-transparent outline-none text-slate-700">
                <option value="">All Status</option>
                @foreach ($statusOptions as $statusOption)
                  <option value="{{ strtolower($statusOption) }}">{{ $statusOption }}</option>
                @endforeach
              </select>
            </label>
            <button id="clearApplicantFilters" type="button" class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:text-slate-900">
              <i class="fa-solid fa-rotate-left text-xs"></i>
              Reset Filters
            </button>
          </div>
        </div>

        <div class="overflow-x-auto rounded-[1.5rem] border border-slate-200 bg-slate-50/60">
          <table class="w-full text-sm">
            <thead class="border-b border-slate-200 bg-white/80 text-left text-slate-400">
              <tr>
                <th class="px-5 py-4">APPLICANT</th>
                <th class="px-3 py-4">POSITION</th>
                <th class="px-3 py-4">APPLIED DATE</th>
                <th class="px-3 py-4">STATUS</th>
                <th class="px-3 py-4">RATING</th>
                <th class="px-5 py-4">ACTIONS</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200" id="applicantsTableBody"></tbody>
          </table>
        </div>

        <div id="applicantEmptyState" class="hidden mt-4 rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50/70 p-6 text-center text-sm text-slate-500">
          No applicants matched the current search or filters.
        </div>

        <div class="mt-4 flex justify-end items-center gap-2" id="paginationControls"></div>
      </div>
    </div>
  </main>
</div>

<div id="applicantModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 px-4 py-6 backdrop-blur-sm">
  <div class="relative w-full max-w-6xl overflow-hidden rounded-[2rem] border border-white/70 bg-white shadow-[0_28px_80px_rgba(15,23,42,0.18)]">
    <div class="border-b border-slate-200 bg-[linear-gradient(135deg,rgba(14,165,233,0.08),rgba(16,185,129,0.08))] px-6 py-5">
      <div class="flex items-center justify-between gap-4">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-700">Applicant Profile</p>
          <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-900">Candidate Review Desk</h2>
        </div>
        <button type="button" onclick="closeApplicantModal()" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:text-slate-900">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
    </div>

    <div class="max-h-[82vh] overflow-y-auto p-6">
      <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
        <div class="space-y-6">
          <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50/70 p-6">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
              <div class="flex items-start gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-[1.35rem] bg-[linear-gradient(135deg,#0ea5e9,#2563eb)] text-xl font-bold text-white" id="applicantInitials">
                  AP
                </div>
                <div>
                  <h3 class="text-2xl font-black tracking-tight text-slate-900" id="name"></h3>
                  <p class="mt-1 text-sm text-slate-500" id="email"></p>
                  <div class="mt-3 flex flex-wrap gap-2">
                    <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700" id="title"></span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600" id="status"></span>
                  </div>
                  <div class="mt-4 flex flex-wrap gap-4 text-sm text-slate-500">
                    <span class="inline-flex items-center gap-2"><i class="fa-regular fa-calendar text-sky-500"></i><span id="one"></span></span>
                    <span class="inline-flex items-center gap-2"><i class="fa-solid fa-location-dot text-emerald-500"></i><span id="location"></span></span>
                  </div>
                </div>
              </div>

              <div class="flex flex-col items-stretch gap-3 sm:flex-row lg:flex-col">
                <button type="button" onclick="scheduleInterview()" class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                  <i class="fa-regular fa-calendar"></i>
                  Schedule Interview
                </button>

                <form action="{{ route('admin.updateStatus') }}" id="updateStatus" method="POST">
                  @csrf
                  <input type="hidden" name="reviewId" id="statusId">
                  <select
                    name="status"
                    class="w-full rounded-full border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-300"
                    onchange="confirmStatusChange(this)"
                  >
                    <option value="-- Choose Option --">-- Choose Option --</option>
                    <option value="Under Review">Under Review</option>
                    <option value="Initial Interview">Initial Interview</option>
                    <option value="Final Interview">Final Interview</option>
                    <option value="Demo Teaching" id="demoTeachingOption" class="hidden">Demo Teaching</option>
                    <option value="Hired">Hired</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Passing Document">Passing Document</option>
                    <option value="Completed">Completed</option>
                  </select>
                </form>
              </div>
            </div>
          </div>

          <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm">
            <h4 class="flex items-center gap-2 text-base font-bold text-slate-900">
              <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-sky-100 text-sky-600">
                <i class="fa-regular fa-user"></i>
              </span>
              Professional Summary
            </h4>
            <p class="mt-4 text-sm leading-7 text-slate-600" id="passionate"></p>
          </div>

          <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm">
              <h4 class="flex items-center gap-2 text-base font-bold text-slate-900">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                  <i class="fa-solid fa-briefcase"></i>
                </span>
                Work Experience
              </h4>
              <p class="mt-4 text-sm leading-7 text-slate-600" id="work_info"></p>
            </div>

            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm">
              <h4 class="flex items-center gap-2 text-base font-bold text-slate-900">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                  <i class="fa-solid fa-graduation-cap"></i>
                </span>
                Education
              </h4>
              <p class="mt-4 text-sm leading-7 text-slate-600" id="university_info"></p>
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <div class="rounded-[1.75rem] border border-emerald-200 bg-emerald-50/70 p-5">
            <h4 class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Skills</h4>
            <div class="mt-3 flex flex-wrap gap-2" id="skills"></div>
          </div>

          <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm">
            <h4 class="text-base font-bold text-slate-900">Contact Information</h4>
            <div class="mt-4 space-y-3 text-sm text-slate-600">
              <div class="flex items-start gap-3">
                <span class="mt-0.5 text-sky-500"><i class="fa-regular fa-envelope"></i></span>
                <p id="contact_email"></p>
              </div>
              <div class="flex items-start gap-3">
                <span class="mt-0.5 text-emerald-500"><i class="fa-solid fa-phone"></i></span>
                <p id="number"></p>
              </div>
            </div>
          </div>

          <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm">
            <h4 class="text-base font-bold text-slate-900">Documents</h4>
            <div id="rehireSummary" class="mt-3 hidden rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs font-medium text-amber-800"></div>
            <div id="documents" class="mt-4 space-y-3"></div>
          </div>

          <form action="{{ route('admin.adminStarStore') }}" method="POST" id="starRatings">
            @csrf
            <input type="hidden" name="ratingId" id="ratingStarId">
            <input type="hidden" name="rating" id="ratingValue">
          </form>

          <div class="rounded-[1.75rem] border border-amber-200 bg-amber-50/70 p-5">
            <div class="flex items-center justify-between gap-4">
              <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">Applicant Rating</p>
                <div class="mt-3 flex gap-1 text-xl text-amber-400" id="ratingStars">
                  <i class="fa-regular fa-star star cursor-pointer" data-value="1"></i>
                  <i class="fa-regular fa-star star cursor-pointer" data-value="2"></i>
                  <i class="fa-regular fa-star star cursor-pointer" data-value="3"></i>
                  <i class="fa-regular fa-star star cursor-pointer" data-value="4"></i>
                  <i class="fa-regular fa-star star cursor-pointer" data-value="5"></i>
                </div>
              </div>
              <div class="rounded-full bg-white px-3 py-1 text-sm font-semibold text-slate-600" id="ratingText">0 / 5</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="scheduleInterviewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 px-4 py-6 backdrop-blur-sm">
  <div class="w-full max-w-2xl overflow-hidden rounded-[2rem] border border-white/70 bg-white shadow-[0_28px_80px_rgba(15,23,42,0.18)]">
    <div class="bg-[linear-gradient(135deg,#0f172a,#1d4ed8)] px-6 py-5">
      <div class="flex items-center justify-between gap-4">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-200">Interview Planner</p>
          <h2 class="mt-2 text-2xl font-black tracking-tight text-white">Schedule Interview</h2>
        </div>
        <button type="button" onclick="closeScheduleModal()" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
    </div>

    <div class="p-6">
      <div class="mb-6 flex items-center gap-4 rounded-[1.5rem] border border-sky-100 bg-sky-50/80 p-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-sky-500 font-bold text-white" id="scheduleInitials">AP</div>
        <div>
          <p class="font-semibold text-slate-900" id="names"></p>
          <p class="text-sm text-slate-500" id="titles"></p>
        </div>
      </div>

      <form class="space-y-4" action="{{ route('admin.storeNewInterview') }}" method="POST" id="form">
        @csrf
        <input type="hidden" id="applicants_id" name="applicants_id">

        <div>
          <label class="mb-1 block text-sm font-medium text-slate-700">Interview Type</label>
          <select name="interview_type" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-300">
            <option value="HR Interview">HR Interview</option>
            <option value="Final Interview">Final Interview</option>
          </select>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Date</label>
            <input type="date" name="date" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-300">
          </div>
          <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Time</label>
            <input type="time" name="time" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-300">
          </div>
        </div>

        <div>
          <label class="mb-1 block text-sm font-medium text-slate-700">Duration</label>
          <select name="duration" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-300">
            <option value="5 minutes">5 minutes</option>
            <option value="30 minutes">30 minutes</option>
            <option value="45 minutes">45 minutes</option>
            <option value="60 minutes">60 minutes</option>
            <option value="90 minutes">90 minutes</option>
          </select>
        </div>

        <div>
          <label class="mb-1 block text-sm font-medium text-slate-700">Interviewer(s)</label>
          <input type="text" name="interviewers" placeholder="Enter interviewer name(s)" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-300">
        </div>

        <div>
          <label class="mb-1 block text-sm font-medium text-slate-700">Email Link</label>
          <input type="email" name="email_link" placeholder="Enter Email Address" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-300">
        </div>

        <div>
          <label class="mb-1 block text-sm font-medium text-slate-700">Meeting Link (Optional)</label>
          <input type="url" name="url" placeholder="https://meet.google.com/..." class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-300">
        </div>

        <div>
          <label class="mb-1 block text-sm font-medium text-slate-700">Notes (Optional)</label>
          <textarea name="notes" placeholder="Add any additional notes or instructions..." class="h-28 w-full resize-none rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-300"></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
          <button type="button" onclick="closeScheduleModal()" class="rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:text-slate-900">Cancel</button>
          <button type="submit" class="rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Schedule Interview</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  const applicants = @json($applicant);
  const rowsPerPage = 5;
  let currentPage = 1;
  let currentApplicantId = null;

  const statusClasses = {
    pending: 'bg-amber-100 text-amber-700',
    'under review': 'bg-sky-100 text-sky-700',
    'initial interview': 'bg-blue-100 text-blue-700',
    'final interview': 'bg-violet-100 text-violet-700',
    'demo teaching': 'bg-cyan-100 text-cyan-700',
    'passing document': 'bg-orange-100 text-orange-700',
    completed: 'bg-emerald-100 text-emerald-700',
    hired: 'bg-emerald-100 text-emerald-700',
    rejected: 'bg-rose-100 text-rose-700',
    default: 'bg-slate-100 text-slate-700'
  };

  function normalizeText(value) {
    return (value ?? '').toString().trim().toLowerCase();
  }

  function getInitials(firstName, lastName) {
    const source = `${firstName ?? ''} ${lastName ?? ''}`.trim();
    if (!source) {
      return 'AP';
    }

    return source
      .split(/\s+/)
      .slice(0, 2)
      .map(part => part.charAt(0).toUpperCase())
      .join('');
  }

  function formatDate(dateValue) {
    if (!dateValue) {
      return 'N/A';
    }

    const parsed = new Date(dateValue);
    if (Number.isNaN(parsed.getTime())) {
      return dateValue;
    }

    return parsed.toLocaleDateString(undefined, {
      month: 'numeric',
      day: 'numeric',
      year: 'numeric'
    });
  }

  function buildRatingStars(rating = 0) {
    return Array.from({ length: 5 }, (_, index) => {
      const filled = index < Number(rating || 0);
      return `<i class="${filled ? 'fa-solid text-amber-400' : 'fa-regular text-slate-300'} fa-star"></i>`;
    }).join('');
  }

  function getFilteredApplicants() {
    const searchTerm = normalizeText(document.getElementById('headerApplicantSearch')?.value);
    const positionFilter = normalizeText(document.getElementById('applicantPositionFilter')?.value);
    const statusFilter = normalizeText(document.getElementById('applicantStatusFilter')?.value);

    return applicants.filter(app => {
      const fullName = `${app.first_name ?? ''} ${app.last_name ?? ''}`.trim();
      const position = app.position?.title ?? '';
      const status = app.application_status ?? '';
      const email = app.email ?? '';
      const school = app.collage_name ?? '';
      const dateText = formatDate(app.created_at);

      const matchesSearch = !searchTerm || [
        fullName,
        email,
        position,
        status,
        school,
        dateText
      ].some(value => normalizeText(value).includes(searchTerm));

      const matchesPosition = !positionFilter || normalizeText(position) === positionFilter;
      const matchesStatus = !statusFilter || normalizeText(status) === statusFilter;

      return matchesSearch && matchesPosition && matchesStatus;
    });
  }

  function renderTable(page = 1) {
    const tbody = document.getElementById('applicantsTableBody');
    const emptyState = document.getElementById('applicantEmptyState');
    const filteredApplicants = getFilteredApplicants();
    const totalPages = Math.max(1, Math.ceil(filteredApplicants.length / rowsPerPage));

    currentPage = Math.min(page, totalPages);
    const start = (currentPage - 1) * rowsPerPage;
    const paginatedItems = filteredApplicants.slice(start, start + rowsPerPage);

    tbody.innerHTML = paginatedItems.map(app => {
      const statusKey = normalizeText(app.application_status);
      const badgeClass = statusClasses[statusKey] || statusClasses.default;
      const initials = getInitials(app.first_name, app.last_name);
      const position = app.position?.title ?? 'Unassigned Position';
      const appliedDate = formatDate(app.created_at);
      const fullName = `${app.first_name ?? ''} ${app.last_name ?? ''}`.trim();

      return `
        <tr class="transition hover:bg-white">
          <td class="px-5 py-4">
            <div class="flex items-center gap-3">
              <div class="flex h-11 w-11 items-center justify-center rounded-full bg-sky-500 font-semibold text-white">${initials}</div>
              <div>
                <p class="font-semibold text-slate-900">${fullName}</p>
                <p class="text-xs text-slate-400">${app.email ?? ''}</p>
              </div>
            </div>
          </td>
          <td class="px-3 py-4">
            <p class="font-semibold text-slate-800">${position}</p>
            <p class="text-xs text-slate-400">${app.collage_name ?? 'Not specified'}</p>
          </td>
          <td class="px-3 py-4 text-slate-600">${appliedDate}</td>
          <td class="px-3 py-4">
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ${badgeClass}">${app.application_status ?? 'Pending'}</span>
          </td>
          <td class="px-3 py-4">
            <div class="flex items-center gap-1 text-sm">${buildRatingStars(app.starRatings || 0)}</div>
          </td>
          <td class="px-5 py-4">
            <div class="flex items-center gap-2 text-slate-400">
              <button type="button" onclick="openApplicantModal(${app.id})" class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white transition hover:border-sky-200 hover:text-sky-600" title="View applicant">
                <i class="fa-regular fa-eye"></i>
              </button>
              <button type="button" onclick="openScheduleModal(${app.id})" class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white transition hover:border-indigo-200 hover:text-indigo-600" title="Schedule interview">
                <i class="fa-regular fa-calendar"></i>
              </button>
              <button type="button" class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white transition hover:border-rose-200 hover:text-rose-500" title="Remove action">
                <i class="fa-solid fa-xmark"></i>
              </button>
            </div>
          </td>
        </tr>
      `;
    }).join('');

    emptyState.classList.toggle('hidden', filteredApplicants.length !== 0);
    renderPagination(filteredApplicants.length);
  }

  function renderPagination(totalItems) {
    const pagination = document.getElementById('paginationControls');
    pagination.innerHTML = '';

    const pageCount = Math.ceil(totalItems / rowsPerPage);
    if (pageCount <= 1) {
      return;
    }

    for (let i = 1; i <= pageCount; i++) {
      const button = document.createElement('button');
      button.type = 'button';
      button.textContent = i;
      button.className = `flex h-10 w-10 items-center justify-center rounded-full border text-sm font-semibold transition ${
        i === currentPage
          ? 'border-slate-900 bg-slate-900 text-white'
          : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:text-slate-900'
      }`;
      button.addEventListener('click', () => renderTable(i));
      pagination.appendChild(button);
    }
  }

  function showFlexModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function hideFlexModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  function fillInterviewCard(name, title) {
    document.getElementById('names').innerText = name || 'Applicant';
    document.getElementById('titles').innerText = title || 'Position not specified';
    document.getElementById('scheduleInitials').innerText = getInitials(name, '');
  }

  function openScheduleModal(appId) {
    if (!appId) {
      return;
    }

    currentApplicantId = appId;
    document.getElementById('applicants_id').value = appId;

    fetch(`/system/applicants/ID/${appId}`)
      .then(res => res.json())
      .then(data => {
        fillInterviewCard(data.name, data.title);
        showFlexModal('scheduleInterviewModal');
      });
  }

  function scheduleInterview() {
    const appId = currentApplicantId || document.getElementById('statusId').value;
    if (!appId) {
      return;
    }

    openScheduleModal(appId);
  }

  function closeScheduleModal() {
    hideFlexModal('scheduleInterviewModal');
  }

  function renderSkills(skillsValue) {
    const skillsContainer = document.getElementById('skills');
    skillsContainer.innerHTML = '';

    const skills = (skillsValue || '')
      .split(',')
      .map(skill => skill.trim())
      .filter(Boolean);

    if (!skills.length) {
      skillsContainer.innerHTML = '<span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-500">No skills listed</span>';
      return;
    }

    skillsContainer.innerHTML = skills.map(skill =>
      `<span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-emerald-700">${skill}</span>`
    ).join('');
  }

  function openApplicantModal(applicantId) {
    currentApplicantId = applicantId;
    document.getElementById('statusId').value = applicantId;
    document.getElementById('ratingStarId').value = applicantId;

    fetch(`/system/applicants/ID/${applicantId}`)
      .then(res => res.json())
      .then(data => {
        document.getElementById('name').innerText = data.name;
        document.getElementById('email').innerText = data.email;
        document.getElementById('contact_email').innerText = data.email;
        document.getElementById('title').innerText = data.title;
        document.getElementById('status').innerText = data.status;
        document.getElementById('location').innerText = data.location;
        document.getElementById('one').innerText = data.one;
        document.getElementById('passionate').innerText = data.passionate;
        document.getElementById('number').innerText = data.number;
        document.getElementById('applicantInitials').innerText = getInitials(data.name, '');

        const demoTeachingOption = document.getElementById('demoTeachingOption');
        const normalizedJobType = normalizeText(data.job_type);
        const isTeaching = normalizedJobType.includes('teaching') && !normalizedJobType.includes('non');
        demoTeachingOption.classList.toggle('hidden', !isTeaching);

        const statusSelect = document.querySelector('form#updateStatus select[name="status"]');
        if (!isTeaching && statusSelect.value === 'Demo Teaching') {
          statusSelect.value = '-- Choose Option --';
        }

        const workInfo = [
          data.work_position,
          data.work_employer,
          data.work_location,
          data.work_duration
        ].filter(Boolean).join(' | ');

        const universityInfo = [
          data.university_name,
          data.university_address,
          data.university_year
        ].filter(Boolean).join(' | ');

        document.getElementById('work_info').innerText = workInfo || 'No work experience information provided.';
        document.getElementById('university_info').innerText = universityInfo || 'No education information provided.';

        renderSkills(data.skills);

        const docsContainer = document.getElementById('documents');
        const rehireSummary = document.getElementById('rehireSummary');
        docsContainer.innerHTML = '';
        if (rehireSummary) {
          const changedFields = Array.isArray(data?.comparison?.changed_fields) ? data.comparison.changed_fields : [];
          const changedDegrees = Array.isArray(data?.comparison?.changed_degree_levels) ? data.comparison.changed_degree_levels : [];
          if (data?.comparison?.is_rehire) {
            const count = changedFields.length + changedDegrees.length;
            rehireSummary.classList.remove('hidden');
            rehireSummary.innerText = count > 0
              ? `Rehire application detected. ${count} updated field${count === 1 ? '' : 's'} marked as new, and uploaded documents are labeled New.`
              : 'Rehire application detected. Uploaded documents are labeled New for this returning employee.';
          } else {
            rehireSummary.classList.add('hidden');
            rehireSummary.innerText = '';
          }
        }

        if (data.documents && data.documents.length > 0) {
          docsContainer.innerHTML = data.documents.map(doc => `
            <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
              <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-sky-100 text-sky-600">
                  <i class="fa-regular fa-file"></i>
                </div>
                <div>
                  <div class="flex flex-wrap items-center gap-2">
                    <p class="text-sm font-semibold text-slate-800">${doc.name}</p>
                    ${doc.is_new ? '<span class="rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.16em] text-amber-700">New</span>' : ''}
                  </div>
                  <p class="text-xs text-slate-400">${doc.type ?? ''}</p>
                </div>
              </div>
              <a href="${doc.url}" target="_blank" class="text-slate-400 transition hover:text-sky-600">
                <i class="fa-solid fa-download"></i>
              </a>
            </div>
          `).join('');
        } else {
          docsContainer.innerHTML = '<p class="text-sm text-slate-400">No documents uploaded.</p>';
        }

        setRating(data.star || 0);
        showFlexModal('applicantModal');
      });
  }

  function closeApplicantModal() {
    hideFlexModal('applicantModal');
  }

  const stars = document.querySelectorAll('.star');
  const ratingText = document.getElementById('ratingText');
  const ratingInput = document.getElementById('ratingValue');

  stars.forEach(star => {
    star.addEventListener('click', function () {
      const rating = parseInt(this.dataset.value, 10);

      let starsHtml = '';
      for (let i = 1; i <= 5; i++) {
        starsHtml += i <= rating
          ? '<i class="fa-solid fa-star text-amber-400 text-2xl mx-1"></i>'
          : '<i class="fa-regular fa-star text-slate-300 text-2xl mx-1"></i>';
      }

      Swal.fire({
        title: 'Confirm Rating',
        html: `
          <div class="mb-2 flex justify-center">
            ${starsHtml}
          </div>
          <p class="text-sm text-slate-600">Rate this applicant ${rating} / 5</p>
        `,
        showCancelButton: true,
        confirmButtonText: 'Yes, rate',
        cancelButtonText: 'Cancel'
      }).then(result => {
        if (result.isConfirmed) {
          setRating(rating);
          document.getElementById('starRatings').submit();
        }
      });
    });
  });

  function setRating(rating) {
    ratingInput.value = rating;

    stars.forEach(star => {
      if (Number(star.dataset.value) <= Number(rating)) {
        star.classList.remove('fa-regular');
        star.classList.add('fa-solid');
      } else {
        star.classList.remove('fa-solid');
        star.classList.add('fa-regular');
      }
    });

    ratingText.textContent = `${rating} / 5`;
  }

  function confirmStatusChange(select) {
    const newStatus = select.value;
    if (newStatus === '-- Choose Option --') {
      return;
    }

    Swal.fire({
      title: 'Are you sure?',
      text: `Change applicant status to "${newStatus}"?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      cancelButtonText: 'Cancel'
    }).then(result => {
      if (!result.isConfirmed) {
        select.value = '-- Choose Option --';
        return;
      }

      document.getElementById('updateStatus').submit();
    });
  }

  document.getElementById('headerApplicantSearch')?.addEventListener('input', () => renderTable(1));
  document.getElementById('applicantPositionFilter')?.addEventListener('change', () => renderTable(1));
  document.getElementById('applicantStatusFilter')?.addEventListener('change', () => renderTable(1));
  document.getElementById('clearApplicantFilters')?.addEventListener('click', () => {
    document.getElementById('headerApplicantSearch').value = '';
    document.getElementById('applicantPositionFilter').value = '';
    document.getElementById('applicantStatusFilter').value = '';
    renderTable(1);
  });

  document.querySelectorAll('#applicantModal, #scheduleInterviewModal').forEach(modal => {
    modal.addEventListener('click', event => {
      if (event.target === modal) {
        hideFlexModal(modal.id);
      }
    });
  });

  renderTable(1);
</script>

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
