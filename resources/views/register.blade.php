<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Northeastern College | HRMS - Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>

<div class="min-h-screen flex items-center justify-center
     bg-gradient-to-br from-green-900 via-green-700 to-green-500 p-6">

    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- LEFT CARD -->
        <div class="bg-white/95 rounded-3xl shadow-2xl p-10 flex flex-col justify-between">

            <!-- Logo -->
            <div class="flex items-center gap-3 mb-10">
                <div class="w-12 h-12 rounded-xl overflow-hidden bg-white flex items-center justify-center">
                    <img src="/images/logo.webp" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Northeastern College</h1>
                    <p class="text-sm text-gray-500">HR Management System</p>
                </div>
            </div>

            <!-- Content -->
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 mb-4">
                    Streamline Your Workforce
                </h2>

                <p class="text-gray-600 mb-8 leading-relaxed">
                    Powerful HRIS platform designed to simplify employee management,
                    attendance tracking, and HR operations.
                </p>

                <!-- Features -->
                <div class="space-y-5">
                    <div class="flex gap-4">
                        <span class="text-green-600 font-bold">✓</span>
                        <div>
                            <h3 class="font-semibold text-gray-900">Employee Management</h3>
                            <p class="text-sm text-gray-500">
                                Centralized employee records and profiles
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <span class="text-blue-600 font-bold">✓</span>
                        <div>
                            <h3 class="font-semibold text-gray-900">Attendance Tracking</h3>
                            <p class="text-sm text-gray-500">
                                Monitor time-in, time-out, and attendance logs
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <span class="text-purple-600 font-bold">✓</span>
                        <div>
                            <h3 class="font-semibold text-gray-900">Analytics & Reports</h3>
                            <p class="text-sm text-gray-500">
                                Real-time insights and HR reporting
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-6 mt-10 pt-8 border-t">
                <div>
                    <p class="text-2xl font-bold text-gray-900">50K+</p>
                    <p class="text-sm text-gray-500">Active Users</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">99.9%</p>
                    <p class="text-sm text-gray-500">Uptime</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">4.9/5</p>
                    <p class="text-sm text-gray-500">User Rating</p>
                </div>
            </div>
        </div>

        <!-- RIGHT CARD -->
        <div class="bg-white rounded-3xl shadow-2xl p-10 flex items-center">

            <div class="w-full max-w-md mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h2>
                <p class="text-gray-500 mb-8">Register to access the HRMS</p>

                <form class="space-y-5" method='POST' action = '{{ route("register.store")}}'>
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" placeholder="First Name" name = "first_name"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-600 focus:outline-none">
                        <input type="text" placeholder="Middle Name" name = "middle_name"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-600 focus:outline-none">
                        <input type="text" placeholder="Last Name" name = "last_name"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-600 focus:outline-none">
                    </div>

                    <input type="email" placeholder="Email Address" name = "email"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-600 focus:outline-none">

                    <input type="password" placeholder="Password" name = "password"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-600 focus:outline-none">

                    <input type="password" placeholder="Confirm Password" name = "confirmation_password"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-600 focus:outline-none">

                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-green-800 via-green-600 to-green-800 text-white font-semibold py-3 rounded-xl transition">
                        Create Account
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-8">
                    Already have an account?
                    <a href="{{ route('login')}}" class="text-green-700 font-semibold hover:underline">
                        Sign in
                    </a>
                </p>
            </div>

        </div>

    </div>
</div>

</body>
</html>
