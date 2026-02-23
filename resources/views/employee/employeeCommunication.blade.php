<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslips | Employee Portal</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
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

 @include('components.employeeSidebar')

    <!-- MAIN CONTENT -->
    <main class="flex-1 ml-16 transition-all duration-300">
    @include('components.employeeHeader.communicationHeader')
    <div class="p-4 md:p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 pt-20">
    @forelse($admins as $admin)
        @php
            $nameParts = array_filter([
                $admin->first_name ?? '',
                $admin->middle_name ?? '',
                $admin->last_name ?? '',
            ]);
            $fullName = trim(implode(' ', $nameParts));
            $initials = strtoupper(substr((string) ($admin->first_name ?? ''), 0, 1) . substr((string) ($admin->last_name ?? ''), 0, 1));
            $displayStatus = trim((string) ($admin->status ?? ''));
            if (strtolower($displayStatus) === 'approved') {
                $displayStatus = 'Available';
            }
            $isAvailable = strtolower($displayStatus) === 'available';
        @endphp

        <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
            <div class="mx-auto w-24 h-24 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700
                        flex items-center justify-center text-white text-3xl font-semibold mb-4">
                {{ $initials !== '' ? $initials : 'AD' }}
            </div>

            <h3 class="font-semibold text-lg">{{ $fullName !== '' ? $fullName : 'Admin User' }}</h3>
            <p class="text-sm text-gray-500">{{ $admin->job_role ?? 'Administrator' }}</p>
            <p class="text-sm text-gray-400 mb-4">{{ $admin->role }}</p>

            <span class="px-4 py-1 text-sm rounded-full {{ $isAvailable ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                {{ $displayStatus !== '' ? $displayStatus : 'No Status' }}
            </span>
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow-sm p-8 text-center md:col-span-2 lg:col-span-4">
            <p class="text-gray-600">No admin users found.</p>
        </div>
    @endforelse
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


