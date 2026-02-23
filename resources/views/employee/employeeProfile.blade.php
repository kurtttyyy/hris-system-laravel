<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | Northeastern College</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            transition: margin-left 0.3s ease;
        }

        main {
            transition: margin-left 0.3s ease;
        }

        aside:not(:hover) ~ main {
            margin-left: 4rem;
        }

        aside:hover ~ main {
            margin-left: 14rem;
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex min-h-screen">

    @include('components.employeeSideBar')

    <!-- Main Content -->
    <main class="flex-1 ml-16 transition-all duration-300">

    @include('components.employeeHeader.myProfileHeader')
            <!-- Content -->
            <div class="p-4 md:p-8 space-y-8 pt-20">

                <!-- Profile Card -->
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-3xl font-bold">
                            {{ $emp->initials}}
                        </div>

                        <div>
                            <h2 class="text-2xl font-semibold">{{ $emp->first_name }} {{ $emp->middle_name }} {{ $emp->last_name }}</h2>
                            <p class="text-gray-600">{{ $emp->employee->position}}</p>
                            <p class="text-sm text-gray-500">{{ $emp->employee->department }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-4 mt-6 text-center">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xl font-bold">{{ $serviceDurationText ?? '0Y 0M 0D' }}</p>
                            <p class="text-sm text-gray-500">Years</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xl font-bold">{{ number_format((float) ($attendanceRatePercent ?? 0), 1) }}%</p>
                            <p class="text-sm text-gray-500">Attendance</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xl font-bold">{{ rtrim(rtrim(number_format((float) ($leaveDaysUsed ?? 0), 1, '.', ''), '0'), '.') }}</p>
                            <p class="text-sm text-gray-500">Leave Days</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xl font-bold">A+</p>
                            <p class="text-sm text-gray-500">Performance</p>
                        </div>
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="grid grid-cols-2 gap-6">
                    <!-- Personal Info -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="font-semibold mb-4">Personal Information</h3>

                        <p class="text-xs text-gray-500">EMPLOYEE ID</p>
                        <p class="font-medium mb-3">{{ $emp->employee->employee_id }}</p>

                        <p class="text-xs text-gray-500">EMAIL</p>
                        <p class="font-medium mb-3">{{ $emp->email }}</p>

                        <p class="text-xs text-gray-500">PHONE</p>
                        <p class="font-medium mb-3">{{ $emp->employee->contact_number }}</p>

                        <p class="text-xs text-gray-500">ADDRESS</p>
                        <p class="font-medium">{{ $emp->employee->address}}</p>
                    </div>

                    <!-- Employment Info -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="font-semibold mb-4">Employment Details</h3>

                        <p class="text-xs text-gray-500">POSITION</p>
                        <p class="font-medium mb-3">{{ $emp->employee->position}}</p>

                        <p class="text-xs text-gray-500">DEPARTMENT</p>
                        <p class="font-medium mb-3">{{ $emp->employee->department }}</p>

                        <p class="text-xs text-gray-500">JOIN DATE</p>
                        <p class="font-medium mb-3">{{ $emp->employee->formatted_employement_date ?? $emp->applicant?->formatted_date_hired ?? '-' }}</p>

                        <p class="text-xs text-gray-500">REPORTING MANAGER</p>
                        <p class="font-medium">Michael Chen</p>
                    </div>
                </div>

            </div>

    </main>
</div>

<script>
    const sidebar = document.querySelector('aside');
    const main = document.querySelector('main');

    if (sidebar && main) {
        sidebar.addEventListener('mouseenter', function() {
            main.classList.remove('ml-16');
            main.classList.add('ml-56');
        });

        sidebar.addEventListener('mouseleave', function() {
            main.classList.remove('ml-56');
            main.classList.add('ml-16');
        });
    }
</script>

</body>
</html>

