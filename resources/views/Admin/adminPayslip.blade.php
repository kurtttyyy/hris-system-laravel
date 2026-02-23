<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Payslip</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    body { font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif; transition: margin-left 0.3s ease; }
    main { transition: margin-left 0.3s ease; }
    aside ~ main { margin-left: 16rem; }
  </style>
</head>
<body class="bg-slate-100">
<div class="flex min-h-screen">
  @include('components.adminSideBar')

  <main class="flex-1 ml-16 transition-all duration-300">
    <div class="p-4 md:p-8 pt-10 space-y-6">
      <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h1 class="text-2xl font-semibold text-slate-800">Payslip</h1>
        <p class="text-sm text-slate-500 mt-2">Admin payslip page is ready.</p>
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
      main.classList.add('ml-64');
    });
    sidebar.addEventListener('mouseleave', function() {
      main.classList.remove('ml-64');
      main.classList.add('ml-16');
    });
  }
</script>
</body>
</html>
