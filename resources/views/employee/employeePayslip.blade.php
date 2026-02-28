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
@php
    $money = function ($value) {
        return is_null($value) || $value === '' ? 'PHP 0.00' : 'PHP '.number_format((float) $value, 2);
    };
    $moneyOrDash = function ($value) {
        return is_null($value) || $value === '' ? '-' : number_format((float) $value, 2);
    };
@endphp

<div class="flex min-h-screen">

 @include('components.employeeSidebar')

    <!-- MAIN CONTENT -->
    <main class="flex-1 ml-16 transition-all duration-300">
    @include('components.employeeHeader.payslipHeader')
<div class="p-4 md:p-8 space-y-8 pt-20">


        <div class="bg-gradient-to-b from-green-900 to-green-500 rounded-2xl p-8 text-white shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">

                <div>
                    <p class="text-sm opacity-80">Gross Salary</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $money($grossSalary ?? null) }}</h3>
                </div>

                <div>
                    <p class="text-sm opacity-80">Deductions</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $money($deductions ?? null) }}</h3>
                </div>

                <div>
                    <p class="text-sm opacity-80">Net Salary</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $money($netSalary ?? null) }}</h3>
                </div>

                <div>
                    <p class="text-sm opacity-80">Others</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $money($others ?? null) }}</h3>
                </div>

            </div>
        </div>

        <!-- RECENT PAYSLIPS -->
        <div class="mt-10 bg-white rounded-2xl shadow-sm border border-gray-200">
            <div class="p-6 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Recent Payslips</h3>
            </div>

            <div class="p-6 space-y-4">
                @forelse(($recentPayslips ?? collect()) as $payslip)
                    @php
                        $periodLabel = $payslip->pay_date
                            ? $payslip->pay_date->format('F Y')
                            : ($payslip->pay_date_text ?: 'Payslip');
                        $paidOnLabel = $payslip->pay_date
                            ? $payslip->pay_date->format('M d, Y')
                            : (optional($payslip->scanned_at)->format('M d, Y') ?: '-');
                        $totalSalary = (float) ($payslip->basic_salary ?? 0)
                            + (float) ($payslip->living_allowance ?? 0)
                            + (float) ($payslip->extra_load ?? 0)
                            + (float) ($payslip->other_income ?? 0);
                        $totalDeduction = (float) ($payslip->absences_amount ?? 0)
                            + (float) ($payslip->withholding_tax ?? 0)
                            + (float) ($payslip->salary_vale ?? 0)
                            + (float) ($payslip->pag_ibig_loan ?? 0)
                            + (float) ($payslip->pag_ibig_premium ?? 0)
                            + (float) ($payslip->sss_loan ?? 0)
                            + (float) ($payslip->sss_premium ?? 0)
                            + (float) ($payslip->peraa_loan ?? 0)
                            + (float) ($payslip->peraa_premium ?? 0)
                            + (float) ($payslip->philhealth_premium ?? 0)
                            + (float) ($payslip->other_deduction ?? 0);
                    @endphp
                    <div class="flex justify-between items-center border border-gray-200 rounded-xl p-5">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $periodLabel }}</p>
                            <p class="text-sm text-gray-500">Paid on {{ $paidOnLabel }}</p>
                        </div>

                        <div class="text-right">
                            <p class="font-bold text-gray-800">{{ $money($payslip->net_pay) }}</p>
                            <button
                                type="button"
                                data-open-modal="payslip-modal-{{ $payslip->id }}"
                                class="mt-2 inline-flex items-center justify-center rounded-md border border-green-200 bg-green-50 px-3 py-1 text-xs font-medium text-green-700 hover:bg-green-100"
                            >
                                View
                            </button>
                        </div>
                    </div>

                    <div id="payslip-modal-{{ $payslip->id }}" class="hidden fixed inset-0 z-50">
                        <div class="absolute inset-0 bg-black/50" data-close-modal></div>
                        <div class="relative z-10 flex min-h-screen items-center justify-center p-4">
                            <div class="w-full max-w-4xl bg-white border border-gray-300 shadow-2xl max-h-[90vh] overflow-y-auto">
                                <div class="flex justify-end p-2">
                                    <button type="button" class="rounded-md border border-gray-300 px-2 py-1 text-xs text-gray-600 hover:bg-gray-100" data-close-modal>
                                        Close
                                    </button>
                                </div>

                                <div class="border-y border-gray-300 px-8 py-4 text-center">
                                    <img src="{{ asset('images/logo.png') }}" alt="Northeastern College" class="mx-auto w-[380px] max-w-full h-auto object-contain" />
                                </div>

                                <div class="border-b border-gray-300 px-8 py-3 text-center">
                                    <p class="font-semibold text-gray-900">PAY SLIP / ADVICE</p>
                                </div>

                                <div class="border-b border-gray-300 px-8 py-4 text-sm text-gray-800">
                                    <p><span class="font-medium">Pay Date:</span> {{ $payslip->pay_date ? $payslip->pay_date->format('m/d/Y') : ($payslip->pay_date_text ?: '-') }}</p>
                                    <div class="mt-2 flex flex-wrap gap-x-8 gap-y-2">
                                        <p><span class="font-medium">Emp ID No:</span> {{ $payslip->employee_id ?: '-' }}</p>
                                        <p><span class="font-medium">Acct #:</span> {{ $payslip->account_credited ?: '-' }}</p>
                                    </div>
                                    <p class="mt-2"><span class="font-medium">Emp Name:</span> {{ $payslip->employee_name ?: '-' }}</p>
                                </div>

                                <div class="border-b border-gray-300 px-8 py-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm text-gray-800">
                                        <div>
                                            <div class="space-y-1.5">
                                                <div class="flex justify-between"><span>Basic Salary</span><span>{{ $moneyOrDash($payslip->basic_salary) }}</span></div>
                                                <div class="flex justify-between"><span>Living Allowance</span><span>{{ $moneyOrDash($payslip->living_allowance) }}</span></div>
                                                <div class="flex justify-between"><span>Extra Load</span><span>{{ $moneyOrDash($payslip->extra_load) }}</span></div>
                                                <div class="flex justify-between"><span>Other Income</span><span>{{ $moneyOrDash($payslip->other_income) }}</span></div>
                                            </div>
                                            <div class="mt-4 border-y border-gray-900 py-1 flex justify-between font-semibold">
                                                <span>Total Salary</span>
                                                <span>{{ number_format($totalSalary, 2) }}</span>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="space-y-1.5">
                                                <div class="flex justify-between"><span>Absences Amount</span><span>{{ $moneyOrDash($payslip->absences_amount) }}</span></div>
                                                <div class="flex justify-between"><span>Withholding Tax</span><span>{{ $moneyOrDash($payslip->withholding_tax) }}</span></div>
                                                <div class="flex justify-between"><span>Salary Vale</span><span>{{ $moneyOrDash($payslip->salary_vale) }}</span></div>
                                                <div class="flex justify-between"><span>Pag-ibig Loan</span><span>{{ $moneyOrDash($payslip->pag_ibig_loan) }}</span></div>
                                                <div class="flex justify-between"><span>Pag-ibig Premium</span><span>{{ $moneyOrDash($payslip->pag_ibig_premium) }}</span></div>
                                                <div class="flex justify-between"><span>SSS Loan</span><span>{{ $moneyOrDash($payslip->sss_loan) }}</span></div>
                                                <div class="flex justify-between"><span>SSS Premium</span><span>{{ $moneyOrDash($payslip->sss_premium) }}</span></div>
                                                <div class="flex justify-between"><span>PERAA Loan</span><span>{{ $moneyOrDash($payslip->peraa_loan) }}</span></div>
                                                <div class="flex justify-between"><span>PERAA Premium</span><span>{{ $moneyOrDash($payslip->peraa_premium) }}</span></div>
                                                <div class="flex justify-between"><span>Philhealth Premium</span><span>{{ $moneyOrDash($payslip->philhealth_premium) }}</span></div>
                                                <div class="flex justify-between"><span>Other Deduction</span><span>{{ $moneyOrDash($payslip->other_deduction) }}</span></div>
                                            </div>
                                            <div class="mt-4 border-y border-gray-900 py-1 flex justify-between font-semibold">
                                                <span>Total Deduction</span>
                                                <span>{{ number_format($totalDeduction, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-5 md:ml-auto md:w-1/2 border-y-2 border-gray-900 py-1 flex justify-between text-base font-bold text-gray-900">
                                        <span>Net Pay</span>
                                        <span>{{ number_format((float) ($payslip->net_pay ?? 0), 2) }}</span>
                                    </div>
                                </div>

                                <div class="border-b border-gray-300 px-8 py-4 text-sm text-gray-800">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                                        <div>
                                            <p class="font-medium">Prepared by:</p>
                                            <div class="mt-10"></div>
                                            <p class="mt-2 text-sm text-center">ADELAIDA A. CERVANTES</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">Noted by:</p>
                                            <div class="mt-10"></div>
                                            <p class="mt-2 text-sm text-center">DANTE O. CLEMENTE</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-8 py-4 text-center text-sm text-gray-800">
                                    I hereby acknowledge to have receive from the Treasurer of Northeastern College, Inc the sums herein specified, the same being full compensation of my services rendered during the period stated above, the correctness of which I hereby certify.
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="border border-dashed border-gray-300 rounded-xl p-5 text-sm text-gray-500 text-center">
                        No payslip data found yet. Ask admin to scan your payslip file.
                    </div>
                @endforelse
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

    const modalOpenButtons = document.querySelectorAll('[data-open-modal]');
    modalOpenButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const modalId = this.getAttribute('data-open-modal');
            const modal = modalId ? document.getElementById(modalId) : null;
            if (!modal) return;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            const closeButtons = modal.querySelectorAll('[data-close-modal]');
            closeButtons.forEach((closeButton) => {
                closeButton.addEventListener('click', function () {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                });
            });
        });
    });
</script>

</body>
</html>
