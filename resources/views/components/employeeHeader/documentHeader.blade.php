<header class="bg-white border-b border-gray-200 sticky top-0 z-40 px-8 py-6 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold text-gray-900">Documents</h2>
        <p class="text-gray-600 mt-1">Access your work-related documents</p>
    </div>

    <div class="flex items-center gap-2">
        <a href="{{ route('employee.employeeDocument') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            Upload Document
        </a>

        <div class="relative group">
            <button class="p-2.5 text-gray-600 hover:bg-gray-100 rounded-full">
                <i class="fa fa-user fa-2x"></i>
            </button>

            <div class="absolute right-0 mt-3 w-48 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <a href="{{ route('employee.employeeProfile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fa fa-user"></i>
                    My Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                        <i class="fa fa-sign-out"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
