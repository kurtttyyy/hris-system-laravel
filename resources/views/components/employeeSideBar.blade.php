<aside
    class="group fixed left-0 top-0 h-screen bg-gray-900 border-r border-gray-700
           w-16 hover:w-56 transition-all duration-300 overflow-hidden z-50"
>

    <!-- Logo -->
    <div class="p-4 border-b border-gray-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" height="40">
            </div>

            <!-- Logo text -->
            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <h1 class="text-sm font-bold text-white">
                    Northeastern College
                </h1>
                <p class="text-xs text-gray-400">
                    Employee Portal
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-2 space-y-2">

        <!-- Dashboard -->
        <a href="{{ route('employee.employeeHome') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition
                  {{ request()->routeIs('employee.employeeHome')
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'text-gray-300 hover:bg-green-600/20 hover:text-white' }}">

            <i class="fa fa-dashboard text-lg w-6 text-center"></i>

            <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Dashboard
            </span>
        </a>

        <!-- My Profile -->
        <a href="{{ route('employee.employeeProfile') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition
                  {{ request()->routeIs('employee.employeeProfile')
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'text-gray-300 hover:bg-green-600/20 hover:text-white' }}">

            <i class="fa fa-user text-lg w-6 text-center"></i>

            <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                My Profile
            </span>
        </a>

        <!-- Leave Requests -->
        <a href="{{ route('employee.employeeLeave') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition
                  {{ request()->routeIs('employee.employeeLeave')
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'text-gray-300 hover:bg-green-600/20 hover:text-white' }}">

            <i class="fa fa-calendar text-lg w-6 text-center"></i>

            <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Leave Requests
            </span>
        </a>

        <!-- Payslips -->
        <a href="{{ route('employee.employeePayslip') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition
                  {{ request()->routeIs('employee.employeePayslip')
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'text-gray-300 hover:bg-green-600/20 hover:text-white' }}">

            <i class="fa fa-file-text-o text-lg w-6 text-center"></i>

            <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Payslips
            </span>
        </a>

        <!-- Documents -->
        <a href="{{ route('employee.employeeDocument') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition
                  {{ request()->routeIs('employee.employeeDocument')
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'text-gray-300 hover:bg-green-600/20 hover:text-white' }}">

            <i class="fa fa-folder text-lg w-6 text-center"></i>

            <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Documents
            </span>
        </a>

        <!-- Communication -->
        <a href="{{ route('employee.employeeCommunication') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition
                  {{ request()->routeIs('employee.employeeCommunication')
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'text-gray-300 hover:bg-green-600/20 hover:text-white' }}">

            <i class="fa fa-users text-lg w-6 text-center"></i>

            <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Communication
            </span>
        </a>

    </nav>

</aside>

<!-- Font Awesome -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
