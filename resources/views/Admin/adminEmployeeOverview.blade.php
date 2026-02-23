    <!-- ================= PROFILE MODAL ================= -->
    <div
      x-show="openProfile"
      x-transition
      @click.self="openProfile = false"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      style="display: none;"
    >
      <div class="bg-white rounded-2xl w-full max-w-3xl overflow-hidden">

        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-purple-500 to-indigo-500 p-6 text-white relative">
          <button
            @click="openProfile = false"
            class="absolute top-4 right-4 text-2xl">
            &times;
          </button>

          <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold">
              JD
            </div>

            <div>
              <h2 class="text-xl font-semibold">John Doe</h2>
              <p class="text-sm opacity-90">
                Senior Software Engineer<br>Engineering
              </p>
            </div>

            <span class="ml-auto bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">
              Active
            </span>
          </div>
        </div>

        <!-- Tabs -->
        <div class="flex gap-6 px-6 pt-4 border-b text-sm">
          <span class="text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-2">
            Overview
          </span>
          <span class="text-gray-500">Personal Details</span>
          <span class="text-gray-500">Performance</span>
          <span class="text-gray-500">Documents</span>
        </div>

        <!-- Modal Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">

          <div class="bg-slate-50 p-4 rounded-xl">
            <h4 class="font-semibold mb-3">Contact Information</h4>
            <p class="text-sm">Email: john.doe@company.com</p>
            <p class="text-sm">Phone: +1 (555) 123-4567</p>
            <p class="text-sm">Location: San Francisco, CA</p>
          </div>

          <div class="bg-slate-50 p-4 rounded-xl">
            <h4 class="font-semibold mb-3">Employment Details</h4>
            <p class="text-sm">Employee ID: EMP-2024-1234</p>
            <p class="text-sm">Join Date: Jan 15, 2022</p>
            <p class="text-sm">Manager: Sarah Williams</p>
          </div>

          <div class="bg-slate-50 p-4 rounded-xl">
            <h4 class="font-semibold mb-3">Skills</h4>
            <div class="flex flex-wrap gap-2">
              <span class="px-3 py-1 text-xs bg-indigo-100 text-indigo-700 rounded-full">JavaScript</span>
              <span class="px-3 py-1 text-xs bg-indigo-100 text-indigo-700 rounded-full">React</span>
              <span class="px-3 py-1 text-xs bg-indigo-100 text-indigo-700 rounded-full">Node.js</span>
              <span class="px-3 py-1 text-xs bg-indigo-100 text-indigo-700 rounded-full">Python</span>
              <span class="px-3 py-1 text-xs bg-indigo-100 text-indigo-700 rounded-full">AWS</span>
            </div>
          </div>

          <div class="bg-slate-50 p-4 rounded-xl">
            <h4 class="font-semibold mb-3">Recent Activity</h4>
            <ul class="text-sm space-y-2">
              <li>✅ Completed project milestone (2 days ago)</li>
              <li>📅 Attended team meeting (5 days ago)</li>
              <li>✏️ Updated profile (1 week ago)</li>
            </ul>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex gap-3 p-6 border-t">
          <button class="flex-1 bg-indigo-600 text-white py-2 rounded-lg">
            Send Message
          </button>
          <button class="flex-1 bg-slate-100 py-2 rounded-lg">
            Schedule Meeting
          </button>
          <button class="flex-1 bg-slate-100 py-2 rounded-lg">
            Edit Profile
          </button>
        </div>

      </div>
    </div>
