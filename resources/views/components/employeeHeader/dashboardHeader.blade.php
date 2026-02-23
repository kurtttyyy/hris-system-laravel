<header class="bg-white border-b border-gray-200 sticky top-0 z-40 px-8 py-6">
    <div class="flex items-center justify-between">

        <!-- LEFT : STATIC TITLE -->
        <div>
            <h2 class="text-3xl font-bold text-gray-900">
                Welcome Back <span>👋</span>
            </h2>
            <p class="text-gray-600 mt-1">
                Welcome to the employee dashboard
            </p>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-2">

            <!-- Notifications (STATIC) -->
            <button class="relative p-3.5 text-gray-600 hover:bg-gray-100 rounded-lg">
                <span class="absolute top-1 right-1 flex items-center justify-center
                             text-xs font-bold text-white bg-red-600 rounded-full w-5 h-5">
                    3
                </span>
                <i class="fa fa-bell fa-2x"></i>
            </button>

            <!-- USER ICON WITH HOVER DROPDOWN -->
            <div class="relative group">
                <button class="p-2.5 text-gray-600 hover:bg-gray-100 rounded-full">
                    <i class="fa fa-user fa-2x"></i>
                </button>

                <!-- DROPDOWN -->
                <div
                    class="absolute right-0 mt-3 w-48 bg-white border border-gray-200
                           rounded-xl shadow-lg overflow-hidden
                           opacity-0 invisible group-hover:opacity-100
                           group-hover:visible transition-all duration-200 z-50">

                    <a href="#"
                       class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fa fa-user"></i>
                        My Profile
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fa fa-cog"></i>
                        Settings
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                        <i class="fa fa-sign-out"></i>
                        Logout
                    </a>
                </div>
            </div>

        </div>

    </div>
</header>
