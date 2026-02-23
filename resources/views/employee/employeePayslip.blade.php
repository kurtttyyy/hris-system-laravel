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
    @include('components.employeeHeader.payslipHeader')
<div class="p-4 md:p-8 space-y-8 pt-20">


        <div class="bg-gradient-to-b from-green-900 to-green-500 rounded-2xl p-8 text-white shadow-lg">

            <div class="grid grid-cols-4 gap-6 text-center">

                <div>
                    <p class="text-sm opacity-80">Gross Salary</p>
                    <h3 class="text-3xl font-bold mt-2">₱6,500</h3>
                </div>

                <div>
                    <p class="text-sm opacity-80">Deductions</p>
                    <h3 class="text-3xl font-bold mt-2">₱1,250</h3>
                </div>

                <div>
                    <p class="text-sm opacity-80">Net Salary</p>
                    <h3 class="text-3xl font-bold mt-2">₱5,250</h3>
                </div>

                <div>
                    <p class="text-sm opacity-80">others</p>
                    <h3 class="text-3xl font-bold mt-2">₱3,000</h3>
                </div>

            </div>
        </div>

        <!-- RECENT PAYSLIPS -->
        <div class="mt-10 bg-white rounded-2xl shadow-sm border border-gray-200">
            <div class="p-6 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Recent Payslips</h3>
            </div>

            <div class="p-6">
                <div class="flex justify-between items-center border border-gray-200 rounded-xl p-5">
                    <div>
                        <p class="font-semibold text-gray-800">January 2025</p>
                        <p class="text-sm text-gray-500">Paid on Jan 31, 2025</p>
                    </div>

                    <div class="text-right">
                        <p class="font-bold text-gray-800">₱5,250</p>
                        <a href="#" class="text-purple-600 text-sm font-medium hover:underline">
                            View
                        </a>
                    </div>
                </div>
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
