<header class="bg-white border-b border-gray-200 sticky top-0 z-40 px-4 md:px-8 py-4 md:py-5 backdrop-blur-sm">
    <div class="flex items-center justify-between gap-6">

        <!-- LEFT : Title -->
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Employees</h2>
            <p class="text-gray-600 text-sm mt-1">
                Manage all employees and their status
            </p>
            <p class="text-xs text-slate-500 mt-1">{{ now()->format('l, F j, Y') }}</p>
        </div>

        <!-- RIGHT : Search + Department -->
        <div class="flex items-center gap-4">

            <!-- Search Bar -->
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <i class="fa fa-search"></i>
                </span>
                <input
                    type="text"
                    x-model="search"
                    placeholder="Search employee name..."
                    class="pl-10 pr-4 py-2 w-64 border border-gray-300 rounded-lg
                           focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                >
            </div>

            <!-- Department Filter -->
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-filter text-gray-400"></i>
                <select
                    x-model="department"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                >
                    <option value="All">All Departments</option>
                    @foreach (($departmentOptions ?? collect()) as $dept)
                        <option value="{{ $dept }}">{{ $dept }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Buttons -->
            <div class="flex items-center bg-gray-100 rounded-lg p-1">
                <button
                    type="button"
                    @click="statusFilter = 'Active'"
                    :class="statusFilter === 'Active'
                        ? 'px-4 py-2 text-sm rounded-md font-medium bg-white text-green-600 shadow'
                        : 'px-4 py-2 text-sm rounded-md font-medium text-green-600 hover:bg-white hover:shadow'">
                    Active
                </button>

                <button
                    type="button"
                    @click="statusFilter = 'On Leave'"
                    :class="statusFilter === 'On Leave'
                        ? 'px-4 py-2 text-sm rounded-md font-medium bg-white text-yellow-600 shadow'
                        : 'px-4 py-2 text-sm rounded-md font-medium text-yellow-600 hover:bg-white hover:shadow'">
                    On Leave
                </button>

                <button
                    type="button"
                    @click="statusFilter = 'Inactive'"
                    :class="statusFilter === 'Inactive'
                        ? 'px-4 py-2 text-sm rounded-md font-medium bg-white text-red-600 shadow'
                        : 'px-4 py-2 text-sm rounded-md font-medium text-red-600 hover:bg-white hover:shadow'">
                    Inactive
                </button>

                <button
                    type="button"
                    @click="statusFilter = 'All'"
                    :class="statusFilter === 'All'
                        ? 'px-4 py-2 text-sm rounded-md font-medium bg-white text-slate-700 shadow'
                        : 'px-4 py-2 text-sm rounded-md font-medium text-slate-700 hover:bg-white hover:shadow'">
                    All
                </button>
            </div>

        </div>
    </div>
</header>
