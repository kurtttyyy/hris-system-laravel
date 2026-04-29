<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PeopleHub - Create Position</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    :root {
      --page-bg: #edf4f1;
      --panel-bg: rgba(255, 255, 255, 0.92);
      --panel-border: rgba(148, 163, 184, 0.24);
      --text-strong: #0f172a;
      --text-soft: #475569;
      --brand: #0f766e;
      --brand-deep: #115e59;
      --accent: #f59e0b;
    }

    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      transition: margin-left 0.3s ease;
      background:
        radial-gradient(circle at top left, rgba(20, 184, 166, 0.14), transparent 26%),
        radial-gradient(circle at top right, rgba(251, 191, 36, 0.14), transparent 24%),
        linear-gradient(180deg, #f6fbf9 0%, var(--page-bg) 100%);
    }

    main {
      transition: margin-left 0.3s ease;
    }

    aside ~ main {
      margin-left: 16rem;
    }

    .position-shell {
      max-width: 84rem;
      margin: 0 auto;
    }

    .position-hero {
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(15, 118, 110, 0.18);
      border-radius: 1.9rem;
      background: linear-gradient(135deg, #052f2a 0%, #0b4c43 42%, #1d7a61 100%);
      box-shadow: 0 28px 70px rgba(15, 23, 42, 0.14);
    }

    .position-hero::before {
      content: "";
      position: absolute;
      inset: 0;
      background:
        radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 28%),
        radial-gradient(circle at bottom left, rgba(20, 184, 166, 0.22), transparent 24%);
      pointer-events: none;
    }

    .position-panel {
      border: 1px solid var(--panel-border);
      border-radius: 1.45rem;
      background: var(--panel-bg);
      box-shadow: 0 18px 44px rgba(15, 23, 42, 0.08);
      backdrop-filter: blur(12px);
    }

    .section-kicker {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      border-radius: 999px;
      padding: 0.45rem 0.8rem;
      font-size: 0.72rem;
      font-weight: 800;
      letter-spacing: 0.22em;
      text-transform: uppercase;
    }

    .section-kicker--teal {
      background: rgba(15, 118, 110, 0.1);
      color: var(--brand);
    }

    .section-kicker--gold {
      background: rgba(245, 158, 11, 0.12);
      color: #b45309;
    }

    .section-title {
      margin-top: 1rem;
      font-size: 1.55rem;
      font-weight: 900;
      letter-spacing: -0.03em;
      color: var(--text-strong);
    }

    .section-copy {
      margin-top: 0.45rem;
      font-size: 0.95rem;
      line-height: 1.7;
      color: var(--text-soft);
    }

    .field-label {
      display: block;
      margin-bottom: 0.45rem;
      font-size: 0.8rem;
      font-weight: 700;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      color: #64748b;
    }

    .helper-text {
      margin-top: 0.7rem;
      font-size: 0.78rem;
      line-height: 1.55;
      color: #64748b;
    }

    .cta-row {
      position: sticky;
      bottom: 1.2rem;
      z-index: 10;
    }

    .input {
      width: 100%;
      border: 1px solid rgba(148, 163, 184, 0.45);
      border-radius: 1rem;
      padding: 0.9rem 1rem;
      background: rgba(255, 255, 255, 0.96);
      color: #0f172a;
      transition: border-color 160ms ease, box-shadow 160ms ease, transform 160ms ease;
    }

    .input:focus {
      outline: none;
      border-color: rgba(15, 118, 110, 0.65);
      box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.12);
      transform: translateY(-1px);
    }

    .input::placeholder {
      color: #94a3b8;
    }

    textarea.input {
      min-height: 10rem;
      line-height: 1.7;
    }
  </style>
</head>
<body>

<div class="flex min-h-screen">
  @include('components.adminSideBar')

  <main class="flex-1 ml-16 transition-all duration-300">
    <div class="p-4 pt-20 md:p-8">
      <div class="position-shell space-y-6">
        @if (session('position_created') || request()->boolean('created'))
          <div
            id="position-success-modal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 px-4 py-6 backdrop-blur-sm"
            role="dialog"
            aria-modal="true"
            aria-labelledby="position-success-title"
          >
            <div class="w-full max-w-md rounded-3xl border border-emerald-100 bg-white p-6 shadow-2xl">
              <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                <i class="fa-solid fa-check text-xl"></i>
              </div>
              <div class="mt-5 text-center">
                <h2 id="position-success-title" class="text-2xl font-black text-slate-900">Successfully created</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Do you want to add more positions?</p>
              </div>
              <div class="mt-6 grid grid-cols-2 gap-3">
                <button
                  type="button"
                  id="position-add-more-yes"
                  class="inline-flex items-center justify-center rounded-2xl bg-emerald-700 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-800"
                >
                  Yes
                </button>
                <a
                  href="{{ route('admin.adminPosition') }}"
                  class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
                >
                  No
                </a>
              </div>
            </div>
          </div>
        @endif

        <section class="position-hero px-6 py-7 text-white md:px-8 md:py-8">
          <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
              <div class="section-kicker border border-white/10 bg-white/10 text-emerald-50">
                <span class="h-2 w-2 rounded-full bg-amber-300"></span>
                Hiring Setup
              </div>
              <h1 class="mt-5 text-4xl font-black tracking-tight md:text-5xl">Add New Position</h1>
              <p class="mt-3 max-w-2xl text-sm leading-7 text-emerald-50/85 md:text-base">
                Build a job opening with clearer role details, better structure, and a posting layout that is easier for HR to review later.
              </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
              <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 backdrop-blur-sm">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-100">Checklist</p>
                <p class="mt-1 text-sm font-semibold text-white">Role, details, requirements, benefits</p>
              </div>
              <a href="{{ route('admin.adminPosition') }}" class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-white/15">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Cancel
              </a>
            </div>
          </div>
        </section>

        <form action="{{ route('admin.createPositionStore') }}" method="POST">
          @csrf

          <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1.8fr)_minmax(20rem,0.9fr)]">
            <div class="space-y-6">
              <div class="position-panel p-6 md:p-7">
                <div class="section-kicker section-kicker--teal">Role Foundation</div>
                <h2 class="section-title">Job Overview</h2>
                <p class="section-copy">Start with the core identity of the opening so the rest of the posting stays consistent.</p>

                <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div>
                    <label class="field-label">Job Title</label>
                    <input class="input" placeholder="Dean of Student Affairs" name="title">
                  </div>
                  <div>
                    <label class="field-label">College Name</label>
                    <input class="input" placeholder="College of Nursing" name="collage_name">
                  </div>
                  <div>
                    <label class="field-label">Department</label>
                    <input class="input" placeholder="Library Department" name="department">
                  </div>
                  <div>
                    <label class="field-label">Employment Type</label>
                    <select class="input" name="employment">
                      <option>Employment Type</option>
                      <option value="Full-Time">Full-Time</option>
                      <option value="Part-Time">Part-Time</option>
                    </select>
                  </div>
                  <div class="md:col-span-2">
                    <label class="field-label">Work Mode</label>
                    <select class="input" name="mode">
                      <option>Work Mode</option>
                      <option value="Remote">Remote</option>
                      <option value="Onsite">Onsite</option>
                      <option value="Hybrid">Hybrid</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="position-panel p-6 md:p-7">
                <div class="section-kicker section-kicker--gold">Role Story</div>
                <h2 class="section-title">Job Description</h2>
                <p class="section-copy">Describe the purpose of the role, the team context, and the kind of impact the hire is expected to make.</p>
                <textarea
                  rows="6"
                  name="description"
                  class="input resize-none bullet-textarea"
                  placeholder="- Describe the position"
                ></textarea>
              </div>

              <div class="position-panel p-6 md:p-7">
                <div class="section-kicker section-kicker--teal">Culture Signal</div>
                <h2 class="section-title">Passionate</h2>
                <p class="section-copy">Capture the mindset, motivation, or mission-fit you want applicants to connect with.</p>
                <textarea
                  rows="6"
                  name="passionate"
                  class="input resize-none bullet-textarea"
                  placeholder="- Describe the passionate qualities you are looking for"
                ></textarea>
              </div>

              <div class="position-panel p-6 md:p-7">
                <div class="section-kicker section-kicker--gold">Delivery Scope</div>
                <h2 class="section-title">Responsibilities</h2>
                <p class="section-copy">List the work the employee will own day to day so the role feels concrete and actionable.</p>
                <textarea
                  rows="5"
                  name="responsibilities"
                  class="input resize-none bullet-textarea"
                  placeholder="- Lead departmental planning and coordination"
                ></textarea>
              </div>

              <div class="position-panel p-6 md:p-7">
                <div class="section-kicker section-kicker--teal">Candidate Fit</div>
                <h2 class="section-title">Requirements</h2>
                <p class="section-copy">Clarify what qualifications, certifications, and experience level are expected before shortlisting.</p>
                <textarea
                  rows="5"
                  name="requirements"
                  class="input resize-none bullet-textarea"
                  placeholder="- 5+ years of related experience"
                ></textarea>
              </div>
            </div>

            <div class="space-y-6">
              <div class="position-panel p-6 md:p-7">
                <div class="section-kicker section-kicker--gold">Posting Settings</div>
                <h2 class="section-title">Job Details</h2>
                <p class="section-copy">Set the practical details that define how and when this opening will be published.</p>

                <div class="mt-6 space-y-4">
                  <div>
                    <label class="field-label">Experience Level</label>
                    <select class="input" name="level">
                      <option>Experience Level</option>
                      <option value="Junior">Junior</option>
                      <option value="Mid">Mid</option>
                      <option value="Senior">Senior</option>
                    </select>
                  </div>

                  <div>
                    <label class="field-label">Job Type</label>
                    <select class="input" name="job_type">
                      <option>Job Type</option>
                      <option value="Teaching">Teaching</option>
                      <option value="Non-Teaching">Non-Teaching</option>
                    </select>
                  </div>

                  <div>
                    <label class="field-label">Location</label>
                    <input class="input" placeholder="Santiago City Campus" name="location">
                  </div>

                  <div>
                    <label class="field-label">Start Date</label>
                    <input type="date" class="input" name="start_date">
                  </div>

                  <div>
                    <label class="field-label">Close Date</label>
                    <input type="date" class="input" name="end_date">
                  </div>

                  <p class="helper-text">
                    Tip: use the closing date to keep the hiring board clean and help applicants understand urgency.
                  </p>
                </div>
              </div>

              <div class="position-panel p-6 md:p-7">
                <div class="section-kicker section-kicker--teal">Capability Focus</div>
                <h2 class="section-title">Required Skills</h2>
                <p class="section-copy">Highlight the strongest practical strengths the role needs from day one.</p>
                <div class="mt-5">
                  <label class="field-label">Skills</label>
                  <input class="input" placeholder="Type skill and press Enter" name="skills">
                </div>
              </div>

              <div class="position-panel p-6 md:p-7">
                <div class="section-kicker section-kicker--gold">Offer Highlights</div>
                <h2 class="section-title">Benefits & Perks</h2>
                <p class="section-copy">Show the practical value of the role so the posting feels attractive, not just demanding.</p>
                <textarea
                  rows="4"
                  name="benefits"
                  class="input resize-none bullet-textarea"
                  placeholder="- Health insurance"
                ></textarea>
              </div>

              <div class="cta-row">
                <div class="position-panel p-4">
                  <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('admin.adminPosition') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 sm:w-auto">
                      <i class="fa-solid fa-xmark text-xs"></i>
                      Cancel
                    </a>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[linear-gradient(135deg,#0f766e,#0f172a)] px-5 py-3 text-sm font-semibold text-white shadow-[0_16px_30px_rgba(15,118,110,0.24)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_34px_rgba(15,118,110,0.30)]">
                      <i class="fa-solid fa-briefcase text-xs"></i>
                      Create Position
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>
</div>

<script>
  const bullet = '- ';

  document.querySelectorAll('.bullet-textarea').forEach((textarea) => {
    textarea.addEventListener('focus', () => {
      if (textarea.value.trim() === '') {
        textarea.value = bullet;
      }
    });

    textarea.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        const start = this.selectionStart;
        this.value =
          this.value.substring(0, start) + '\n' + bullet +
          this.value.substring(this.selectionEnd);
        this.selectionStart = this.selectionEnd = start + bullet.length + 1;
      }
    });
  });

  const positionSuccessModal = document.getElementById('position-success-modal');
  const addMoreButton = document.getElementById('position-add-more-yes');
  if (positionSuccessModal && addMoreButton) {
    if (window.history.replaceState) {
      const cleanUrl = new URL(window.location.href);
      cleanUrl.searchParams.delete('created');
      window.history.replaceState({}, document.title, cleanUrl.toString());
    }

    addMoreButton.addEventListener('click', () => {
      positionSuccessModal.classList.add('hidden');
      document.querySelector('input[name="title"]')?.focus();
    });
  }
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
