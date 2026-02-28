<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Payslip View</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    body { font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif; transition: margin-left 0.3s ease; }
    main { transition: margin-left 0.3s ease; }
    aside ~ main { margin-left: 16rem; }
    .payslip-paper {
      max-width: 900px;
      margin: 0 auto;
      background: #fff;
      border: 1px solid #d4d4d8;
      box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
    }
    .section-line { border-bottom: 1px solid #d4d4d8; }
    .summary-amount { border-top: 1px solid #111827; border-bottom: 2px solid #111827; }
  </style>
</head>
<body class="bg-slate-100">
@php
  $records = $records ?? collect();
  $selectedRecord = $selectedRecord ?? null;
  $money = function ($value) {
      return is_null($value) || $value === '' ? '-' : number_format((float) $value, 2);
  };
  $employeeName = $selectedRecord?->employee_name ?: '-';
  $employeeId = $selectedRecord?->employee_id ?: '-';
  $payDateText = $selectedRecord?->pay_date ? $selectedRecord->pay_date->format('m/d/Y') : ($selectedRecord?->pay_date_text ?: '-');
  $accountCredited = $selectedRecord?->account_credited ?: '-';
  $salaryFields = [
      'basic_salary',
      'living_allowance',
      'extra_load',
      'other_income',
  ];
  $computedTotalSalary = 0.0;
  $hasSalaryValue = false;
  if ($selectedRecord) {
      foreach ($salaryFields as $field) {
          $value = $selectedRecord->{$field} ?? null;
          if ($value !== null && $value !== '') {
              $computedTotalSalary += (float) $value;
              $hasSalaryValue = true;
          }
      }
  }
  $displayTotalSalary = $hasSalaryValue ? $computedTotalSalary : null;
  $deductionFields = [
      'absences_amount',
      'withholding_tax',
      'salary_vale',
      'pag_ibig_loan',
      'pag_ibig_premium',
      'sss_loan',
      'sss_premium',
      'peraa_loan',
      'peraa_premium',
      'philhealth_premium',
      'other_deduction',
  ];
  $computedTotalDeduction = 0.0;
  $hasDeductionValue = false;
  if ($selectedRecord) {
      foreach ($deductionFields as $field) {
          $value = $selectedRecord->{$field} ?? null;
          if ($value !== null && $value !== '') {
              $computedTotalDeduction += (float) $value;
              $hasDeductionValue = true;
          }
      }
  }
  $displayTotalDeduction = $hasDeductionValue ? $computedTotalDeduction : null;
@endphp

<div class="flex min-h-screen">
  @include('components.adminSideBar')

  <main class="flex-1 ml-16 transition-all duration-300">
    @include('components.adminHeader.dashboardHeader', [
      'headerTitle' => 'Payslip',
      'headerSubtitle' => 'Manage and review scanned employee payslips.',
      'headerSearchPlaceholder' => 'Search employees...'
    ])
    <div class="container mx-auto max-w-7xl p-4 md:p-8 pt-10 space-y-6">
      <div class="bg-white rounded-xl border border-slate-200 p-6 flex items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-semibold text-slate-800">Payslip File View</h1>
          <p class="text-sm text-slate-500 mt-1">Dynamic employee containers from scanned records.</p>
        </div>
        <a href="{{ route('admin.adminPayslip') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
          <i class="fa-solid fa-arrow-left"></i>
          Back
        </a>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-6xl mx-auto">
        @forelse ($records as $record)
          @php
            $isSelected = $selectedRecord && (int) $selectedRecord->id === (int) $record->id;
            $searchName = strtolower(trim((string) ($record->employee_name ?: '')));
            $searchId = strtolower(trim((string) ($record->employee_id ?: '')));
          @endphp
          <a
            href="{{ $isSelected ? route('admin.adminPaySlipView', ['upload_id' => ($uploadId ?? $record->payslip_upload_id)]) : route('admin.adminPaySlipView', ['upload_id' => ($uploadId ?? $record->payslip_upload_id), 'record_id' => $record->id]) }}"
            class="employee-card text-left rounded-xl border p-5 transition {{ $isSelected ? 'border-emerald-500 bg-emerald-50' : 'bg-white border-slate-200 hover:border-emerald-400 hover:bg-emerald-50' }}"
            data-employee-name="{{ $searchName }}"
            data-employee-id="{{ $searchId }}"
          >
            <div class="flex items-start justify-between gap-4">
              <div>
                <p class="text-base font-semibold text-slate-800">{{ $record->employee_name ?: 'Employee' }}</p>
                <p class="text-sm text-slate-500 mt-1">ID: {{ $record->employee_id ?: '-' }}</p>
                <p class="text-sm text-slate-500">Period: {{ $record->pay_date ? $record->pay_date->format('m/d/Y') : ($record->pay_date_text ?: '-') }}</p>
                <p class="text-xs text-slate-400 mt-2">{{ optional($record->scanned_at)->format('M d, Y h:i A') ?: 'Scanned' }}</p>
              </div>
              <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                {{ ($selectedRecord && (int) $selectedRecord->id === (int) $record->id) ? 'Selected' : 'View' }}
              </span>
            </div>
          </a>
        @empty
          <div class="col-span-full bg-white rounded-xl border border-dashed border-slate-300 p-8 text-center text-slate-500">
            No scanned payslip data found.
          </div>
        @endforelse
      </div>
      <div id="employee_search_empty" class="hidden max-w-6xl mx-auto bg-white rounded-xl border border-dashed border-slate-300 p-6 text-center text-slate-500 text-sm">
        No employee matched your search.
      </div>

      @if ($selectedRecord)
      <div class="bg-white rounded-xl border border-slate-200 p-6 max-w-6xl mx-auto">
        <div class="mb-4 flex items-center justify-between">
          <h2 class="text-lg font-semibold text-slate-800">Payslip Advice Preview</h2>
          <span class="text-sm text-slate-500">Record #{{ $selectedRecord->id }}</span>
        </div>

        <div class="payslip-paper">
          <div class="px-8 pt-6 pb-4 text-center section-line">
            <img src="{{ asset('images/logo.png') }}" alt="Northeastern College" class="mx-auto w-[420px] max-w-full h-auto object-contain" />
          </div>

          <div class="px-8 py-3 text-center section-line">
            <p class="text-lg font-semibold text-slate-900">PAY SLIP / ADVICE</p>
          </div>

          <div class="px-8 py-4 section-line text-sm text-slate-800">
            <p><span class="font-medium">Pay Date:</span> {{ $payDateText }}</p>
            <div class="mt-2 flex flex-wrap items-center gap-x-8 gap-y-2">
              <p><span class="font-medium">Emp ID No:</span> {{ $employeeId }}</p>
              <p><span class="font-medium">Acct #:</span> {{ $accountCredited }}</p>
            </div>
            <p class="mt-2"><span class="font-medium">Emp Name:</span> {{ $employeeName }}</p>
          </div>

          <div class="px-8 py-4 section-line">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div>
                <div class="space-y-1.5 text-sm text-slate-800">
                  <div class="flex justify-between"><span>Basic Salary</span><span>{{ $money($selectedRecord->basic_salary) }}</span></div>
                  <div class="flex justify-between"><span>Living Allowance</span><span>{{ $money($selectedRecord->living_allowance) }}</span></div>
                  <div class="flex justify-between"><span>Extra Load</span><span>{{ $money($selectedRecord->extra_load) }}</span></div>
                  <div class="flex justify-between"><span>Other Income</span><span>{{ $money($selectedRecord->other_income) }}</span></div>
                </div>
                <div class="mt-4 pt-2 summary-amount text-sm flex justify-between">
                  <span class="font-semibold">Total Salary</span>
                  <span class="font-semibold">{{ $money($displayTotalSalary) }}</span>
                </div>
              </div>

              <div>
                <div class="space-y-1.5 text-sm text-slate-800">
                  <div class="flex justify-between"><span>Absences Amount</span><span>{{ $money($selectedRecord->absences_amount) }}</span></div>
                  <div class="flex justify-between"><span>Withholding Tax</span><span>{{ $money($selectedRecord->withholding_tax) }}</span></div>
                  <div class="flex justify-between"><span>Salary Vale</span><span>{{ $money($selectedRecord->salary_vale) }}</span></div>
                  <div class="flex justify-between"><span>Pag-ibig Loan</span><span>{{ $money($selectedRecord->pag_ibig_loan) }}</span></div>
                  <div class="flex justify-between"><span>Pag-ibig Premium</span><span>{{ $money($selectedRecord->pag_ibig_premium) }}</span></div>
                  <div class="flex justify-between"><span>SSS Loan</span><span>{{ $money($selectedRecord->sss_loan) }}</span></div>
                  <div class="flex justify-between"><span>SSS Premium</span><span>{{ $money($selectedRecord->sss_premium) }}</span></div>
                  <div class="flex justify-between"><span>PERAA Loan</span><span>{{ $money($selectedRecord->peraa_loan) }}</span></div>
                  <div class="flex justify-between"><span>PERAA Premium</span><span>{{ $money($selectedRecord->peraa_premium) }}</span></div>
                  <div class="flex justify-between"><span>Philhealth Premium</span><span>{{ $money($selectedRecord->philhealth_premium) }}</span></div>
                  <div class="flex justify-between"><span>Other Deduction</span><span>{{ $money($selectedRecord->other_deduction) }}</span></div>
                </div>
                <div class="mt-4 pt-2 summary-amount text-sm flex justify-between">
                  <span class="font-semibold">Total Deduction</span>
                  <span class="font-semibold">{{ $money($displayTotalDeduction) }}</span>
                </div>
              </div>
            </div>

            <div class="mt-6 md:ml-auto md:w-1/2">
              <div class="summary-amount pt-2 text-base flex justify-between text-slate-900">
                <span class="font-bold">Net Pay</span>
                <span class="font-bold">{{ $money($selectedRecord->net_pay) }}</span>
              </div>
            </div>
          </div>

          <div class="px-8 py-4 section-line text-sm text-slate-800">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
              <div>
                <p class="font-medium">Prepared by:</p>
                <div class="mt-10 "></div>
                <p class="mt-2 text-sm text-center">ADELAIDA A. CERVANTES</p>
              </div>
              <div>
                <p class="font-medium">Noted by:</p>
                <div class="mt-10 "></div>
                <p class="mt-2 text-sm text-center">DANTE O. CLEMENTE</p>
              </div>
            </div>
          </div>

          <div class="px-8 py-4 text-center text-sm text-slate-800">
            I hereby acknowledge to have receive from the Treasurer of Northeastern College, Inc the sums herein specified, the same being full compensation of my services rendered during the period stated above, the correctness of which I hereby certify.
          </div>
        </div>
      </div>
      @endif
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

  const headerSearchInput = document.querySelector('header input[placeholder="Search employees..."]');
  const employeeCards = Array.from(document.querySelectorAll('.employee-card'));
  const emptySearchMessage = document.getElementById('employee_search_empty');

  if (headerSearchInput && employeeCards.length) {
    const filterCards = () => {
      const term = headerSearchInput.value.trim().toLowerCase();
      let visibleCount = 0;

      employeeCards.forEach((card) => {
        const name = (card.dataset.employeeName || '').toLowerCase();
        const id = (card.dataset.employeeId || '').toLowerCase();
        const matches = term === '' || name.includes(term) || id.includes(term);
        card.classList.toggle('hidden', !matches);
        if (matches) {
          visibleCount++;
        }
      });

      if (emptySearchMessage) {
        emptySearchMessage.classList.toggle('hidden', visibleCount > 0 || term === '');
      }
    };

    headerSearchInput.addEventListener('input', filterCards);
  }
</script>
</body>
</html>


