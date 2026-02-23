<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PeopleHub - Calendar</title>

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
    @include('components.adminHeader.calenderHeader')

    <div class="p-4 md:p-8 pt-20">
      <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <button id="calendarPrevBtn" type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="fa-solid fa-chevron-left"></i>
            Previous
          </button>
          <h3 id="calendarMonthLabel" class="text-xl font-semibold text-gray-800"></h3>
          <button id="calendarNextBtn" type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Next
            <i class="fa-solid fa-chevron-right"></i>
          </button>
        </div>

        <div class="mb-4 flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
          <p id="selectedDateLabel" class="text-sm text-slate-700">Select a date to add a custom event or holiday.</p>
          <div class="flex items-center gap-2">
            <button
              id="addCustomEventBtn"
              type="button"
              class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
              disabled
            >
              <i class="fa-solid fa-plus"></i>
              Add Event
            </button>
            <button
              id="addExamDayBtn"
              type="button"
              class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50"
              disabled
            >
              <i class="fa-solid fa-graduation-cap"></i>
              Add Exam Day
            </button>
            <button
              id="addCustomHolidayBtn"
              type="button"
              class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-700 disabled:cursor-not-allowed disabled:opacity-50"
              disabled
            >
              <i class="fa-solid fa-plus"></i>
              Add Holiday
            </button>
            <button
              id="convertEventToHolidayBtn"
              type="button"
              class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-amber-700 disabled:cursor-not-allowed disabled:opacity-50"
              disabled
            >
              <i class="fa-solid fa-arrows-rotate"></i>
              Event to Holiday
            </button>
            <button
              id="removeCustomEventBtn"
              type="button"
              class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-700 disabled:cursor-not-allowed disabled:opacity-50"
              disabled
            >
              <i class="fa-solid fa-trash"></i>
              Remove Event
            </button>
            <button
              id="removeCustomHolidayBtn"
              type="button"
              class="inline-flex items-center gap-2 rounded-lg bg-rose-800 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-900 disabled:cursor-not-allowed disabled:opacity-50"
              disabled
            >
              <i class="fa-solid fa-trash"></i>
              Remove Holiday
            </button>
          </div>
        </div>

        <div class="grid grid-cols-7 text-center text-xs md:text-sm font-semibold text-slate-600 border-b border-slate-200 pb-2 mb-2">
          <div>Sun</div>
          <div>Mon</div>
          <div>Tue</div>
          <div>Wed</div>
          <div>Thu</div>
          <div>Fri</div>
          <div>Sat</div>
        </div>

        <div id="calendarGrid" class="grid grid-cols-7 gap-2"></div>
        <div class="mt-4 flex flex-wrap items-center gap-4 text-xs text-slate-600">
          <span class="inline-flex items-center gap-2">
            <span class="inline-block h-2.5 w-2.5 rounded-full bg-rose-500"></span>
            <span>Employee Holiday</span>
          </span>
          <span class="inline-flex items-center gap-2">
            <span class="inline-block h-2.5 w-2.5 rounded-full bg-red-500"></span>
            <span>No Classes</span>
          </span>
          <span class="inline-flex items-center gap-2">
            <span class="inline-block h-2.5 w-2.5 rounded-full bg-red-500"></span>
            <span>Special Events</span>
          </span>
          <span class="inline-flex items-center gap-2">
            <span class="inline-block h-2.5 w-2.5 rounded-full bg-yellow-500"></span>
            <span>School Events/Activities</span>
          </span>
          <span class="inline-flex items-center gap-2">
            <span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
            <span>Exam Day</span>
          </span>
          <span id="holidayStatus" class="ml-auto text-slate-500"></span>
        </div>
      </div>
    </div>
  </main>
</div>

<script>
  const sidebar = document.querySelector('aside');
  const main = document.querySelector('main');
  if (sidebar && main) {
    sidebar.addEventListener('mouseenter', function () {
      main.classList.remove('ml-16');
      main.classList.add('ml-64');
    });
    sidebar.addEventListener('mouseleave', function () {
      main.classList.remove('ml-64');
      main.classList.add('ml-16');
    });
  }

  const monthLabel = document.getElementById('calendarMonthLabel');
  const grid = document.getElementById('calendarGrid');
  const prevBtn = document.getElementById('calendarPrevBtn');
  const nextBtn = document.getElementById('calendarNextBtn');
  const holidayStatus = document.getElementById('holidayStatus');
  const selectedDateLabel = document.getElementById('selectedDateLabel');
  const addCustomEventBtn = document.getElementById('addCustomEventBtn');
  const removeCustomEventBtn = document.getElementById('removeCustomEventBtn');
  const addExamDayBtn = document.getElementById('addExamDayBtn');
  const addCustomHolidayBtn = document.getElementById('addCustomHolidayBtn');
  const convertEventToHolidayBtn = document.getElementById('convertEventToHolidayBtn');
  const removeCustomHolidayBtn = document.getElementById('removeCustomHolidayBtn');

  const HOLIDAY_COUNTRY = 'US';
  const CUSTOM_EVENT_STORAGE_KEY = 'school_custom_events_v1';
  const CUSTOM_HOLIDAY_STORAGE_KEY = 'school_custom_holidays_v1';
  const RECURRING_EVENT_STORAGE_KEY = 'school_recurring_events_v1';
  const RECURRING_EXAM_STORAGE_KEY = 'school_recurring_exams_v1';
  const RECURRING_HOLIDAY_STORAGE_KEY = 'school_recurring_holidays_v1';
  const HIDDEN_OFFICIAL_HOLIDAY_STORAGE_KEY = 'hidden_official_holidays_v1';
  const HIDDEN_SPECIAL_EVENT_STORAGE_KEY = 'hidden_special_events_v1';
  const calendarHolidaySyncUrl = @json(route('admin.syncHiddenOfficialHolidays'));
  const csrfToken = @json(csrf_token());
  const holidayCache = {};
  const specialEventCache = {};
  let customEvents = loadCustomEvents();
  let customHolidays = loadCustomHolidays();
  let recurringEvents = loadRecurringEvents();
  let recurringExamDays = loadRecurringExams();
  let recurringHolidays = loadRecurringHolidays();
  let hiddenOfficialHolidays = loadHiddenOfficialHolidays();
  let hiddenSpecialEvents = loadHiddenSpecialEvents();
  let currentEventEntriesByDate = {};
  let currentHolidayEntriesByDate = {};
  let currentSpecialEventEntriesByDate = {};
  let selectedDateForCustomEvent = null;
  let calendarDate = new Date();

  function loadCustomEvents() {
    try {
      const raw = localStorage.getItem(CUSTOM_EVENT_STORAGE_KEY);
      if (!raw) return {};
      const parsed = JSON.parse(raw);
      return parsed && typeof parsed === 'object' ? parsed : {};
    } catch (error) {
      return {};
    }
  }

  function saveCustomEvents() {
    localStorage.setItem(CUSTOM_EVENT_STORAGE_KEY, JSON.stringify(customEvents));
  }

  function loadRecurringEvents() {
    try {
      const raw = localStorage.getItem(RECURRING_EVENT_STORAGE_KEY);
      if (!raw) return {};
      const parsed = JSON.parse(raw);
      return parsed && typeof parsed === 'object' ? parsed : {};
    } catch (error) {
      return {};
    }
  }

  function saveRecurringEvents() {
    localStorage.setItem(RECURRING_EVENT_STORAGE_KEY, JSON.stringify(recurringEvents));
  }

  function loadRecurringExams() {
    try {
      const raw = localStorage.getItem(RECURRING_EXAM_STORAGE_KEY);
      if (!raw) return {};
      const parsed = JSON.parse(raw);
      return parsed && typeof parsed === 'object' ? parsed : {};
    } catch (error) {
      return {};
    }
  }

  function saveRecurringExams() {
    localStorage.setItem(RECURRING_EXAM_STORAGE_KEY, JSON.stringify(recurringExamDays));
  }

  function loadCustomHolidays() {
    try {
      const raw = localStorage.getItem(CUSTOM_HOLIDAY_STORAGE_KEY);
      if (!raw) return {};
      const parsed = JSON.parse(raw);
      return parsed && typeof parsed === 'object' ? parsed : {};
    } catch (error) {
      return {};
    }
  }

  function saveCustomHolidays() {
    localStorage.setItem(CUSTOM_HOLIDAY_STORAGE_KEY, JSON.stringify(customHolidays));
    syncCalendarHolidaysWithServer();
  }

  function loadRecurringHolidays() {
    try {
      const raw = localStorage.getItem(RECURRING_HOLIDAY_STORAGE_KEY);
      if (!raw) return {};
      const parsed = JSON.parse(raw);
      return parsed && typeof parsed === 'object' ? parsed : {};
    } catch (error) {
      return {};
    }
  }

  function saveRecurringHolidays() {
    localStorage.setItem(RECURRING_HOLIDAY_STORAGE_KEY, JSON.stringify(recurringHolidays));
    syncCalendarHolidaysWithServer();
  }

  function loadHiddenOfficialHolidays() {
    try {
      const raw = localStorage.getItem(HIDDEN_OFFICIAL_HOLIDAY_STORAGE_KEY);
      if (!raw) return {};
      const parsed = JSON.parse(raw);
      return parsed && typeof parsed === 'object' ? parsed : {};
    } catch (error) {
      return {};
    }
  }

  function saveHiddenOfficialHolidays() {
    localStorage.setItem(HIDDEN_OFFICIAL_HOLIDAY_STORAGE_KEY, JSON.stringify(hiddenOfficialHolidays));
    syncCalendarHolidaysWithServer();
  }

  function loadHiddenSpecialEvents() {
    try {
      const raw = localStorage.getItem(HIDDEN_SPECIAL_EVENT_STORAGE_KEY);
      if (!raw) return {};
      const parsed = JSON.parse(raw);
      return parsed && typeof parsed === 'object' ? parsed : {};
    } catch (error) {
      return {};
    }
  }

  function saveHiddenSpecialEvents() {
    localStorage.setItem(HIDDEN_SPECIAL_EVENT_STORAGE_KEY, JSON.stringify(hiddenSpecialEvents));
  }

  function syncCalendarHolidaysWithServer() {
    if (!calendarHolidaySyncUrl) return;

    fetch(calendarHolidaySyncUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({
        hidden_official_holidays: hiddenOfficialHolidays,
        custom_holidays: customHolidays,
        recurring_holidays: recurringHolidays,
      }),
    }).catch((error) => {
      console.error('Unable to sync calendar holidays.', error);
    });
  }

  function getCustomEventNamesForDate(isoDate) {
    const events = customEvents[isoDate];
    return Array.isArray(events) ? events : [];
  }

  function getMonthDayKey(isoDate) {
    return isoDate.slice(5);
  }

  function getRecurringEventNamesForDate(isoDate) {
    const events = recurringEvents[getMonthDayKey(isoDate)];
    return Array.isArray(events) ? events : [];
  }

  function getRecurringExamNamesForDate(isoDate) {
    const exams = recurringExamDays[getMonthDayKey(isoDate)];
    return Array.isArray(exams) ? exams : [];
  }

  function getCustomHolidayNamesForDate(isoDate) {
    const holidays = customHolidays[isoDate];
    return Array.isArray(holidays) ? holidays : [];
  }

  function getRecurringHolidayNamesForDate(isoDate) {
    const holidays = recurringHolidays[getMonthDayKey(isoDate)];
    return Array.isArray(holidays) ? holidays : [];
  }

  function setSelectedDate(isoDate) {
    selectedDateForCustomEvent = isoDate;
    const parsedDate = new Date(`${isoDate}T00:00:00`);
    const readable = parsedDate.toLocaleDateString('en-US', {
      weekday: 'long',
      month: 'long',
      day: 'numeric',
      year: 'numeric',
    });
    selectedDateLabel.textContent = `Selected: ${readable}`;
    addCustomEventBtn.disabled = false;
    removeCustomEventBtn.disabled = (currentEventEntriesByDate[isoDate] || []).length === 0;
    addExamDayBtn.disabled = false;
    addCustomHolidayBtn.disabled = false;
    convertEventToHolidayBtn.disabled = (currentSpecialEventEntriesByDate[isoDate] || []).length === 0;
    removeCustomHolidayBtn.disabled = (currentHolidayEntriesByDate[isoDate] || []).length === 0;
  }

  async function getHolidaysForYear(year) {
    if (holidayCache[year]) {
      return holidayCache[year];
    }

    if (holidayStatus) {
      holidayStatus.textContent = 'Loading holidays...';
    }

    try {
      const response = await fetch(`https://date.nager.at/api/v3/PublicHolidays/${year}/${HOLIDAY_COUNTRY}`);
      if (!response.ok) {
        throw new Error('Failed to load holidays');
      }

      const holidays = await response.json();
      const normalized = {};
      holidays.forEach((holiday) => {
        if (!holiday?.date) return;
        normalized[holiday.date] = holiday.localName || holiday.name || 'Holiday';
      });

      holidayCache[year] = normalized;
      if (holidayStatus) {
        holidayStatus.textContent = `${Object.keys(normalized).length} holidays loaded`;
      }
      return normalized;
    } catch (error) {
      holidayCache[year] = {};
      if (holidayStatus) {
        holidayStatus.textContent = 'Unable to load holidays';
      }
      return {};
    }
  }

  async function renderCalendar() {
    const year = calendarDate.getFullYear();
    const month = calendarDate.getMonth();
    const holidays = await getHolidaysForYear(year);
    const specialEvents = getSpecialEventsForYear(year);
    currentEventEntriesByDate = {};
    currentHolidayEntriesByDate = {};
    currentSpecialEventEntriesByDate = {};

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    const today = new Date();

    monthLabel.textContent = calendarDate.toLocaleDateString('en-US', {
      month: 'long',
      year: 'numeric',
    });

    grid.innerHTML = '';

    for (let i = firstDay - 1; i >= 0; i--) {
      const cell = document.createElement('div');
      cell.className = 'rounded-lg border border-slate-100 bg-slate-50 p-3 text-xs md:text-sm text-slate-400 min-h-[84px]';
      cell.textContent = String(daysInPrevMonth - i);
      grid.appendChild(cell);
    }

    for (let day = 1; day <= daysInMonth; day++) {
      const isToday =
        day === today.getDate() &&
        month === today.getMonth() &&
        year === today.getFullYear();
      const isoDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
      const holidayEntries = [];
      const officialHolidayName = holidays[isoDate] || null;
      const hiddenOfficialForDate = Array.isArray(hiddenOfficialHolidays[isoDate]) ? hiddenOfficialHolidays[isoDate] : [];
      if (officialHolidayName && !hiddenOfficialForDate.includes(officialHolidayName)) {
        holidayEntries.push({ name: officialHolidayName, type: 'official' });
      }
      getCustomHolidayNamesForDate(isoDate).forEach((name) => {
        holidayEntries.push({ name, type: 'custom_exact' });
      });
      getRecurringHolidayNamesForDate(isoDate).forEach((name) => {
        holidayEntries.push({ name, type: 'custom_recurring' });
      });

      const dayOfWeek = new Date(year, month, day).getDay();
      const isHoliday = holidayEntries.length > 0;
      const isNoClassSunday = dayOfWeek === 0;
      const noClassLabel = isHoliday ? 'No Classes (Holiday)' : (isNoClassSunday ? 'No Classes (Sunday)' : null);
      const hiddenEventsForDate = Array.isArray(hiddenSpecialEvents[isoDate]) ? hiddenSpecialEvents[isoDate] : [];
      const events = (specialEvents[isoDate] || []).filter((eventName) => !hiddenEventsForDate.includes(eventName));
      const hasSpecialEvent = events.length > 0;
      const customEventEntries = [
        ...getCustomEventNamesForDate(isoDate).map((name) => ({ name, type: 'custom_exact' })),
        ...getRecurringEventNamesForDate(isoDate).map((name) => ({ name, type: 'custom_recurring' })),
      ];
      const examEntries = getRecurringExamNamesForDate(isoDate);
      const hasCustomEvent = customEventEntries.length > 0;
      const hasExamDay = examEntries.length > 0;

      const cell = document.createElement('div');
      cell.className = [
        'rounded-lg border p-3 min-h-[84px] text-xs md:text-sm flex flex-col',
        isToday
          ? 'border-blue-300 bg-blue-50 text-blue-700 font-semibold'
          : 'border-slate-200 bg-white text-slate-700',
        isHoliday ? 'ring-1 ring-rose-300 bg-rose-50/60' : '',
        !isHoliday && isNoClassSunday ? 'ring-1 ring-red-300 bg-red-50/60' : '',
        !isHoliday && !isNoClassSunday && hasSpecialEvent ? 'ring-1 ring-red-300 bg-red-50/60' : '',
        hasCustomEvent ? 'ring-2 ring-yellow-400 bg-yellow-50/60' : '',
        hasExamDay ? 'ring-2 ring-emerald-400 bg-emerald-50/70' : '',
        'cursor-pointer hover:border-indigo-300',
      ].join(' ');
      cell.innerHTML = `<span>${day}</span>`;
      cell.addEventListener('click', function () {
        setSelectedDate(isoDate);
      });

      holidayEntries.forEach((holidayEntry) => {
        const employeeHolidayEl = document.createElement('span');
        employeeHolidayEl.className = 'mt-1 inline-flex items-center gap-1 text-[10px] leading-tight font-medium text-rose-700';
        employeeHolidayEl.innerHTML = `<span class="inline-block h-1.5 w-1.5 rounded-full bg-rose-500"></span>${holidayEntry.name}`;
        cell.appendChild(employeeHolidayEl);
      });

      if (noClassLabel) {
        const schoolNoClassEl = document.createElement('span');
        schoolNoClassEl.className = 'mt-1 inline-flex items-center gap-1 text-[10px] leading-tight font-medium text-red-700';
        schoolNoClassEl.innerHTML = `<span class="inline-block h-1.5 w-1.5 rounded-full bg-red-500"></span>${noClassLabel}`;
        cell.appendChild(schoolNoClassEl);
      }

      events.forEach((eventName) => {
        const eventEl = document.createElement('span');
        eventEl.className = 'mt-1 inline-flex items-center gap-1 text-[10px] leading-tight font-medium text-red-700';
        eventEl.innerHTML = `<span class="inline-block h-1.5 w-1.5 rounded-full bg-red-500"></span>${eventName}`;
        cell.appendChild(eventEl);
      });

      customEventEntries.forEach((eventEntry) => {
        const customEventEl = document.createElement('span');
        customEventEl.className = 'mt-1 inline-flex items-center gap-1 text-[10px] leading-tight font-medium text-yellow-700';
        customEventEl.innerHTML = `<span class="inline-block h-1.5 w-1.5 rounded-full bg-yellow-500"></span>${eventEntry.name}`;
        cell.appendChild(customEventEl);
      });

      examEntries.forEach((examName) => {
        const examEl = document.createElement('span');
        examEl.className = 'mt-1 inline-flex items-center gap-1 text-[10px] leading-tight font-medium text-emerald-700';
        examEl.innerHTML = `<span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500"></span>${examName}`;
        cell.appendChild(examEl);
      });

      grid.appendChild(cell);
      currentEventEntriesByDate[isoDate] = customEventEntries;
      currentHolidayEntriesByDate[isoDate] = holidayEntries;
      currentSpecialEventEntriesByDate[isoDate] = events;
    }

    const totalCells = firstDay + daysInMonth;
    const trailing = (7 - (totalCells % 7)) % 7;
    for (let day = 1; day <= trailing; day++) {
      const cell = document.createElement('div');
      cell.className = 'rounded-lg border border-slate-100 bg-slate-50 p-3 text-xs md:text-sm text-slate-400 min-h-[84px]';
      cell.textContent = String(day);
      grid.appendChild(cell);
    }
  }

  prevBtn?.addEventListener('click', function () {
    calendarDate.setMonth(calendarDate.getMonth() - 1);
    renderCalendar();
  });

  nextBtn?.addEventListener('click', function () {
    calendarDate.setMonth(calendarDate.getMonth() + 1);
    renderCalendar();
  });

  addCustomEventBtn?.addEventListener('click', function () {
    if (!selectedDateForCustomEvent) return;

    const eventTitle = window.prompt('Enter custom school event title:');
    if (eventTitle === null) return;

    const trimmed = eventTitle.trim();
    if (!trimmed) return;

    const monthDay = getMonthDayKey(selectedDateForCustomEvent);
    const current = Array.isArray(recurringEvents[monthDay]) ? recurringEvents[monthDay] : [];
    recurringEvents[monthDay] = [...current, trimmed];
    saveRecurringEvents();
    renderCalendar();
    removeCustomEventBtn.disabled = false;
  });

  removeCustomEventBtn?.addEventListener('click', function () {
    if (!selectedDateForCustomEvent) return;

    const eventEntries = currentEventEntriesByDate[selectedDateForCustomEvent] || [];
    if (!eventEntries.length) return;

    const optionsText = eventEntries
      .map((eventEntry, index) => `${index + 1}. ${eventEntry.name} (${eventEntry.type === 'custom_recurring' ? 'Recurring' : 'One-time'})`)
      .join('\n');
    const selected = window.prompt(`Select event number to remove:\n${optionsText}`);
    if (selected === null) return;

    const selectedIndex = parseInt(selected, 10) - 1;
    if (Number.isNaN(selectedIndex) || selectedIndex < 0 || selectedIndex >= eventEntries.length) return;

    const target = eventEntries[selectedIndex];
    if (target.type === 'custom_recurring') {
      const monthDay = getMonthDayKey(selectedDateForCustomEvent);
      const recurringList = Array.isArray(recurringEvents[monthDay]) ? recurringEvents[monthDay] : [];
      const nextRecurring = recurringList.filter((eventName) => eventName !== target.name);
      if (nextRecurring.length > 0) {
        recurringEvents[monthDay] = nextRecurring;
      } else {
        delete recurringEvents[monthDay];
      }
      saveRecurringEvents();
    } else {
      const exactList = getCustomEventNamesForDate(selectedDateForCustomEvent);
      const nextExact = exactList.filter((eventName) => eventName !== target.name);
      if (nextExact.length > 0) {
        customEvents[selectedDateForCustomEvent] = nextExact;
      } else {
        delete customEvents[selectedDateForCustomEvent];
      }
      saveCustomEvents();
    }

    renderCalendar();
    removeCustomEventBtn.disabled = (currentEventEntriesByDate[selectedDateForCustomEvent] || []).length === 0;
  });

  addCustomHolidayBtn?.addEventListener('click', function () {
    if (!selectedDateForCustomEvent) return;

    const holidayTitle = window.prompt('Enter custom holiday title:');
    if (holidayTitle === null) return;

    const trimmed = holidayTitle.trim();
    if (!trimmed) return;

    const monthDay = getMonthDayKey(selectedDateForCustomEvent);
    const current = Array.isArray(recurringHolidays[monthDay]) ? recurringHolidays[monthDay] : [];
    recurringHolidays[monthDay] = [...current, trimmed];
    saveRecurringHolidays();
    renderCalendar();
    removeCustomHolidayBtn.disabled = false;
  });

  convertEventToHolidayBtn?.addEventListener('click', function () {
    if (!selectedDateForCustomEvent) return;

    const eventEntries = currentSpecialEventEntriesByDate[selectedDateForCustomEvent] || [];
    if (!eventEntries.length) {
      window.alert('No special events available on the selected date.');
      return;
    }

    const optionsText = eventEntries
      .map((eventName, index) => `${index + 1}. ${eventName}`)
      .join('\n');
    const selected = window.prompt(`Select special event number to convert into holiday:\n${optionsText}`);
    if (selected === null) return;

    const selectedIndex = parseInt(selected, 10) - 1;
    if (Number.isNaN(selectedIndex) || selectedIndex < 0 || selectedIndex >= eventEntries.length) return;

    const targetEvent = eventEntries[selectedIndex];
    const monthDay = getMonthDayKey(selectedDateForCustomEvent);
    const holidayList = Array.isArray(recurringHolidays[monthDay]) ? recurringHolidays[monthDay] : [];

    if (!holidayList.includes(targetEvent)) {
      recurringHolidays[monthDay] = [...holidayList, targetEvent];
      saveRecurringHolidays();
    }

    const hiddenList = Array.isArray(hiddenSpecialEvents[selectedDateForCustomEvent])
      ? hiddenSpecialEvents[selectedDateForCustomEvent]
      : [];
    hiddenSpecialEvents[selectedDateForCustomEvent] = [...new Set([...hiddenList, targetEvent])];
    saveHiddenSpecialEvents();

    renderCalendar();
    removeCustomHolidayBtn.disabled = false;
  });

  addExamDayBtn?.addEventListener('click', function () {
    if (!selectedDateForCustomEvent) return;

    const examTitle = window.prompt('Enter exam day title (e.g., Midterm Exam):');
    if (examTitle === null) return;

    const trimmed = examTitle.trim();
    if (!trimmed) return;

    const monthDay = getMonthDayKey(selectedDateForCustomEvent);
    const current = Array.isArray(recurringExamDays[monthDay]) ? recurringExamDays[monthDay] : [];
    recurringExamDays[monthDay] = [...current, trimmed];
    saveRecurringExams();
    renderCalendar();
  });

  removeCustomHolidayBtn?.addEventListener('click', function () {
    if (!selectedDateForCustomEvent) return;

    const holidayEntries = currentHolidayEntriesByDate[selectedDateForCustomEvent] || [];
    if (!holidayEntries.length) return;

    const optionsText = holidayEntries
      .map((holidayEntry, index) => {
        const label = holidayEntry.type === 'official'
          ? 'Official'
          : (holidayEntry.type === 'custom_recurring' ? 'Recurring Custom' : 'One-time Custom');
        return `${index + 1}. ${holidayEntry.name} (${label})`;
      })
      .join('\n');
    const selected = window.prompt(`Select holiday number to remove:\n${optionsText}`);
    if (selected === null) return;

    const selectedIndex = parseInt(selected, 10) - 1;
    if (Number.isNaN(selectedIndex) || selectedIndex < 0 || selectedIndex >= holidayEntries.length) return;

    const target = holidayEntries[selectedIndex];
    if (target.type === 'custom_recurring') {
      const monthDay = getMonthDayKey(selectedDateForCustomEvent);
      const recurringList = Array.isArray(recurringHolidays[monthDay]) ? recurringHolidays[monthDay] : [];
      const nextRecurring = recurringList.filter((holidayName) => holidayName !== target.name);
      if (nextRecurring.length > 0) {
        recurringHolidays[monthDay] = nextRecurring;
      } else {
        delete recurringHolidays[monthDay];
      }
      saveRecurringHolidays();
    } else if (target.type === 'custom_exact') {
      const customList = getCustomHolidayNamesForDate(selectedDateForCustomEvent);
      const nextHolidays = customList.filter((holidayName) => holidayName !== target.name);
      if (nextHolidays.length > 0) {
        customHolidays[selectedDateForCustomEvent] = nextHolidays;
      } else {
        delete customHolidays[selectedDateForCustomEvent];
      }
      saveCustomHolidays();
    } else {
      const hiddenList = Array.isArray(hiddenOfficialHolidays[selectedDateForCustomEvent])
        ? hiddenOfficialHolidays[selectedDateForCustomEvent]
        : [];
      hiddenOfficialHolidays[selectedDateForCustomEvent] = [...new Set([...hiddenList, target.name])];
      saveHiddenOfficialHolidays();
    }

    renderCalendar();
    removeCustomHolidayBtn.disabled = (currentHolidayEntriesByDate[selectedDateForCustomEvent] || []).length === 0;
  });

  function getSpecialEventsForYear(year) {
    if (specialEventCache[year]) {
      return specialEventCache[year];
    }

    const events = {};
    const add = (date, label) => {
      if (!events[date]) events[date] = [];
      events[date].push(label);
    };

    // Fixed-date observances
    add(`${year}-02-14`, "Valentine's Day");
    add(`${year}-03-08`, "International Women's Day");
    add(`${year}-04-22`, "Earth Day");
    add(`${year}-10-31`, "Halloween");
    add(`${year}-11-01`, "All Saints' Day");
    add(`${year}-11-02`, "All Souls' Day");
    add(`${year}-12-24`, "Christmas Eve");
    add(`${year}-12-31`, "New Year's Eve");

    // Chinese New Year (mapped dates)
    const chineseNewYearByYear = {
      2024: '2024-02-10',
      2025: '2025-01-29',
      2026: '2026-02-17',
      2027: '2027-02-06',
      2028: '2028-01-26',
      2029: '2029-02-13',
      2030: '2030-02-03',
      2031: '2031-01-23',
      2032: '2032-02-11',
      2033: '2033-01-31',
      2034: '2034-02-19',
      2035: '2035-02-08',
    };
    if (chineseNewYearByYear[year]) {
      add(chineseNewYearByYear[year], 'Chinese New Year');
    }

    specialEventCache[year] = events;
    return events;
  }

  syncCalendarHolidaysWithServer();
  renderCalendar();
</script>

</body>
</html>
