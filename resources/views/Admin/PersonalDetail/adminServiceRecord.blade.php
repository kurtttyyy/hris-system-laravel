



<!-- ================= PERSONAL DETAILS ================= -->
<div x-show="tab==='record'" x-transition class="w-full p-6">

  <!-- Main Container -->
  <div class="max-w-7xl mx-auto space-y-6">

    <!-- Top Info Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white rounded-xl p-4 shadow">
        <p class="text-xs text-gray-400">EMPLOYEE ID</p>
        <p class="font-semibold mt-1" x-text="selectedEmployee?.employee?.employee_id ?? '-'"></p>
      </div>

      <div class="bg-white rounded-xl p-4 shadow">
        <p class="text-xs text-gray-400">DEPARTMENT</p>
        <p class="font-semibold mt-1" x-text="selectedEmployee?.applicant?.position?.department ?? selectedEmployee?.employee?.department ?? '-'"></p>
      </div>

      <div class="bg-white rounded-xl p-4 shadow">
        <p class="text-xs text-gray-400">DATE HIRED</p>
        <p
          class="font-semibold mt-1"
          x-text="(() => {
            const raw = selectedEmployee?.applicant?.date_hired || selectedEmployee?.employee?.employement_date;
            if (!raw) return '-';
            const datePart = raw.toString().split('T')[0];
            const [year, month, day] = datePart.split('-').map(Number);
            if (!year || !month || !day) return '-';
            const date = new Date(year, month - 1, day);
            if (Number.isNaN(date.getTime())) return '-';
            return date.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
          })()"
        ></p>
      </div>

      <div class="bg-white rounded-xl p-4 shadow">
        <p class="text-xs text-gray-400">STATUS</p>
        <span
          class="inline-block px-3 py-1 rounded-full text-sm mt-1"
          :class="(selectedEmployee?.account_status ?? '').toLowerCase() === 'active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600'"
          x-text="selectedEmployee?.account_status ?? '-'">
        </span>
      </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-indigo-50 text-indigo-600 rounded-xl p-6 text-center">
        <p
          class="text-3xl font-bold"
          x-html="(() => {
            const raw = selectedEmployee?.applicant?.date_hired || selectedEmployee?.employee?.employement_date;
            if (!raw) return `0<span class='text-sm align-baseline ml-1'>Y</span> 0<span class='text-sm align-baseline ml-1'>M</span> 0<span class='text-sm align-baseline ml-1'>D</span>`;
            const datePart = raw.toString().split('T')[0];
            const [year, month, day] = datePart.split('-').map(Number);
            if (!year || !month || !day) return `0<span class='text-sm align-baseline ml-1'>Y</span> 0<span class='text-sm align-baseline ml-1'>M</span> 0<span class='text-sm align-baseline ml-1'>D</span>`;
            const start = new Date(year, month - 1, day);
            if (Number.isNaN(start.getTime())) return `0<span class='text-sm align-baseline ml-1'>Y</span> 0<span class='text-sm align-baseline ml-1'>M</span> 0<span class='text-sm align-baseline ml-1'>D</span>`;
            const today = new Date();
            let years = today.getFullYear() - start.getFullYear();
            let months = today.getMonth() - start.getMonth();
            let days = today.getDate() - start.getDate();
            if (days < 0) {
              days += new Date(today.getFullYear(), today.getMonth(), 0).getDate();
              months -= 1;
            }
            if (months < 0) {
              months += 12;
              years -= 1;
            }
            if (years < 0) {
              years = 0;
              months = 0;
              days = 0;
            }
            return `${years}<span class='text-sm align-baseline ml-1'>Y</span> ${months}<span class='text-sm align-baseline ml-1'>M</span> ${days}<span class='text-sm align-baseline ml-1'>D</span>`;
          })()"
        ></p>
        <p class="text-sm">Service Length</p>
      </div>

      <div class="bg-green-50 text-green-600 rounded-xl p-6 text-center">
        <p class="text-3xl font-bold">3</p>
        <p class="text-sm">Promotions</p>
      </div>

      <div class="bg-yellow-50 text-yellow-600 rounded-xl p-6 text-center">
        <p class="text-3xl font-bold">248</p>
        <p class="text-sm">Training Hours</p>
      </div>

      <div class="bg-purple-50 text-purple-600 rounded-xl p-6 text-center">
        <p class="text-3xl font-bold">5</p>
        <p class="text-sm">Awards</p>
      </div>
    </div>

    <!-- Timeline + Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Service Timeline -->
      <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow">
        <h2 class="font-semibold mb-6">Service Timeline</h2>

        <div class="relative border-l-2 border-indigo-200 ml-3 space-y-6">

          <div class="relative pl-6">
            <span class="absolute -left-[9px] top-1 w-4 h-4 bg-indigo-500 rounded-full"></span>
            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">
              Date Hired
            </span>
            <div class="flex justify-between mt-2">
              <h3 class="font-semibold">Initial Employment Start</h3>
              <span class="text-xs text-gray-400">Mar 15, 2019</span>
            </div>
            <p class="text-sm text-gray-500">
              Employee was hired as Administrative Assistant.
            </p>
          </div>

          <div class="relative pl-6">
            <span class="absolute -left-[9px] top-1 w-4 h-4 bg-rose-500 rounded-full"></span>
            <span class="text-xs bg-rose-100 text-rose-700 px-2 py-1 rounded">
              Date Resigned
            </span>
            <div class="flex justify-between mt-2">
              <h3 class="font-semibold">Resignation Effective Date</h3>
              <span class="text-xs text-gray-400">Aug 30, 2023</span>
            </div>
            <p class="text-sm text-gray-500">
              Employee resigned from previous appointment.
            </p>
          </div>

          <div class="relative pl-6">
            <span class="absolute -left-[9px] top-1 w-4 h-4 bg-indigo-500 rounded-full"></span>
            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded">
              Applied Again
            </span>
            <div class="flex justify-between mt-2">
              <h3 class="font-semibold">Rehired Under New Application</h3>
              <span class="text-xs text-gray-400">Jan 08, 2025</span>
            </div>
            <p class="text-sm text-gray-500">
              Employee applied again and was rehired.
            </p>
          </div>

        </div>
      </div>

      <!-- Right Panel -->
      <div class="space-y-6">

        <!-- Leave Balance -->
        <div class="bg-white rounded-xl p-6 shadow">
          <h2 class="font-semibold mb-4">Leave Balance</h2>

          <div class="mb-4">
            <div class="flex justify-between text-sm mb-1">
              <span>Vacation</span>
              <span>12/15</span>
            </div>
            <div class="bg-gray-200 h-2 rounded-full">
              <div class="bg-indigo-500 h-2 rounded-full w-[80%]"></div>
            </div>
          </div>

          <div class="mb-4">
            <div class="flex justify-between text-sm mb-1">
              <span>Sick</span>
              <span>8/10</span>
            </div>
            <div class="bg-gray-200 h-2 rounded-full">
              <div class="bg-green-500 h-2 rounded-full w-[80%]"></div>
            </div>
          </div>

          <div>
            <div class="flex justify-between text-sm mb-1">
              <span>Personal</span>
              <span>2/3</span>
            </div>
            <div class="bg-gray-200 h-2 rounded-full">
              <div class="bg-yellow-500 h-2 rounded-full w-[66%]"></div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl p-6 shadow">
          <h2 class="font-semibold mb-4">Quick Actions</h2>
          <div class="space-y-3">

            <button
              @click="emailOpen = true"
              class="w-full border rounded-lg py-2 hover:bg-gray-50">
              Send Email
            </button>


            <button
              @click="editOpen = true"
              class="w-full border rounded-lg py-2 hover:bg-gray-50">
              Edit Record
            </button>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>

<!-- SEND EMAIL MODAL -->
<div
  x-show="emailOpen"
  x-transition
  x-cloak
  @keydown.escape.window="emailOpen = false"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
>

  <!-- Modal Card -->
  <div
    @click.outside="emailOpen = false"
    class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden"
  >

    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3 text-white">
        <div class="bg-white/20 p-2 rounded-lg">
          ✉️
        </div>
        <div>
          <h2 class="text-lg font-semibold">Send Email</h2>
          <p class="text-sm opacity-90">To: Jennifer Williams</p>
        </div>
      </div>

      <button
        @click="emailOpen = false"
        class="text-white/80 hover:text-white text-xl"
      >
        ✕
      </button>
    </div>

    <!-- Body -->
    <div class="p-6 space-y-5">

      <!-- To -->
      <div>
        <label class="text-sm text-gray-600">To</label>
        <input
          type="email"
          value="jennifer.williams@techcorp.com"
          class="mt-1 w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
        >
      </div>

      <!-- Subject -->
      <div>
        <label class="text-sm text-gray-600">Subject</label>
        <input
          type="text"
          placeholder="Enter email subject"
          class="mt-1 w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
        >
      </div>

      <!-- Message -->
      <div>
        <label class="text-sm text-gray-600">Message</label>
        <textarea
          rows="5"
          placeholder="Type your message here..."
          class="mt-1 w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
        ></textarea>
      </div>

    </div>

    <!-- Footer -->
    <div class="px-6 py-4 border-t flex justify-between items-center">
      <button
        class="flex-1 mr-3 bg-indigo-600 text-white py-2.5 rounded-lg font-medium hover:bg-indigo-700 flex items-center justify-center gap-2"
      >
        ⚠️ Send Email
      </button>

      <button
        @click="emailOpen = false"
        class="px-5 py-2.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200"
      >
        Cancel
      </button>
    </div>

  </div>
</div>


<!-- ================= EDIT MODAL ================= -->
<div
  x-show="editOpen"
  x-transition
  x-cloak
  @keydown.escape.window="editOpen = false"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
>

  <div
    @click.outside="editOpen = false"
    class="w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden"
  >

    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 flex justify-between items-center text-white">
      <div>
        <h2 class="text-lg font-semibold">Edit Employee Record</h2>
        <p class="text-sm opacity-90">Sarah Rodriguez</p>
      </div>
      <button @click="editOpen = false" class="text-xl">✕</button>
    </div>

    <!-- Body -->
    <div class="p-6 space-y-6">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <input class="rounded-lg border p-2" value="Sarah Rodriguez">
        <input class="rounded-lg border p-2" value="Senior Software Engineer">

        <select class="rounded-lg border p-2">
          <option>Engineering</option>
        </select>

        <select class="rounded-lg border p-2">
          <option>Active</option>
          <option>Inactive</option>
        </select>

        <input class="rounded-lg border p-2" value="Mar 15, 2019">
        <input disabled class="rounded-lg border p-2 bg-gray-100" value="EMP-2019-0847">

      </div>

      <div class="bg-indigo-50 rounded-xl p-4">
        <h3 class="font-semibold text-indigo-700 mb-4">
          Performance Metrics
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <input class="rounded-lg border text-center" value="5.8">
          <input class="rounded-lg border text-center" value="3">
          <input class="rounded-lg border text-center" value="248">
          <input class="rounded-lg border text-center" value="5">
        </div>
      </div>

    </div>

    <!-- Footer -->
    <div class="px-6 py-4 border-t flex justify-between">
      <button class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700">
        ✔ Save Changes
      </button>
      <button
        @click="editOpen = false"
        class="bg-gray-100 px-5 py-2 rounded-lg hover:bg-gray-200">
        Cancel
      </button>
    </div>

  </div>
</div>


