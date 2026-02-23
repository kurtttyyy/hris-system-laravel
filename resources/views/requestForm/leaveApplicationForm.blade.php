<style>
    @page {
        size: legal portrait;
        margin-top: 2mm;
        margin-bottom: 2mm;
        margin-left: -80px;
        margin-right: -80px;

    }

    @media print {
        .print-row-two {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 1rem !important;
        }

        .print-row-three {
            display: grid !important;
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            gap: 1rem !important;
        }

        .print-details-two {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 2rem !important;
            align-items: stretch !important;
        }

        .print-right-divider {
            border-left: 1px solid #000 !important;
            padding-left: 1.25rem !important;
        }

        .print-action-two {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 2rem !important;
            align-items: stretch !important;
        }

        .print-signatory-margin {
            margin-top: 4.5rem !important;
        }

    }
</style>

@php
    $formEarnedVacationValue = (float) ($formEarnedVacation ?? $annualLimit ?? 0);
    $formEarnedSickValue = (float) ($formEarnedSick ?? $sickLimit ?? 0);
    $formEarnedTotalValue = (float) ($formEarnedTotal ?? $totalEarnedDays ?? 0);
    $availableVacationDays = max((float) ($beginningVacationBalance ?? 0) + $formEarnedVacationValue, 0);
    $availableSickDays = max((float) ($beginningSickBalance ?? 0) + $formEarnedSickValue, 0);
@endphp

<form id="leave-application-form" method="POST" action="{{ route('employee.leaveApplication.store') }}" class="space-y-6">
    @csrf

    <div id="leave-application-print-area" class="space-y-5 rounded-lg border-2 border-black bg-white p-6 text-sm text-black">
        <h4 class="text-center text-base font-bold tracking-wide uppercase">Leave Application Form</h4>

        <div class="print-row-two grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block font-medium">Office / Department</label>
                <input name="office_department" type="text" class="w-full rounded border border-black px-3 py-2">
            </div>
            <div>
                <label class="mb-1 block font-medium">Name (Last, First, Middle)</label>
                <input
                    name="employee_name"
                    type="text"
                    value="{{ old('employee_name', $employeeFormName ?? $employeeDisplayName ?? '') }}"
                    class="w-full rounded border border-black px-3 py-2"
                >
            </div>
        </div>

        <div class="print-row-three grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="mb-1 block font-medium">Date of Filing</label>
                <input name="filing_date" type="date" class="w-full rounded border border-black px-3 py-2">
            </div>
            <div>
                <label class="mb-1 block font-medium">Position</label>
                <input
                    name="position"
                    type="text"
                    value="{{ old('position', $employeeFormPosition ?? '') }}"
                    class="w-full rounded border border-black px-3 py-2"
                >
            </div>
            <div>
                <label class="mb-1 block font-medium">Salary</label>
                <input name="salary" type="text" class="w-full rounded border border-black px-3 py-2">
            </div>
        </div>

        <section class="border-t border-black pt-5">
            <h5 class="mb-6 text-center font-bold tracking-wide uppercase">Details of Application</h5>

            <div class="print-details-two grid grid-cols-1 gap-8 md:grid-cols-2 md:items-stretch">
                <div class="space-y-4">
                    <div>
                        <p class="mb-2 font-medium">Type of Leave</p>
                        <div class="space-y-1">
                            <label class="block"><input id="leave-type-vacation" type="checkbox" class="mr-2">Vacation</label>
                            <label class="block"><input id="leave-type-sick" type="checkbox" class="mr-2">Sick</label>
                            <label class="block"><input type="checkbox" class="mr-2">Maternity</label>
                            <label class="block"><input type="checkbox" class="mr-2">Paternity</label>
                            <label class="block"><input type="checkbox" class="mr-2">Others (please specify)</label>
                            <input type="text" class="mt-1 w-full rounded border border-black px-2 py-1" placeholder="Specify other type of leave">
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block font-medium">Number of working days applied for</label>
                        <input id="leave-days-requested" name="number_of_working_days" type="number" min="0" step="0.5" class="w-full rounded border border-black px-3 py-2">
                    </div>

                    <div>
                        <label class="mb-1 block font-medium">Inclusive Dates</label>
                        <input id="leave-inclusive-dates" name="inclusive_dates" type="text" class="w-full rounded border border-black px-3 py-2" readonly>
                    </div>
                </div>

                <div class="print-right-divider border-l border-black pl-5">
                    <div>
                        <p class="mb-2 font-medium">Where leave will be spent</p>
                        <div class="space-y-1">
                            <label class="block"><input type="checkbox" class="mr-2">Within the Philippines</label>
                            <label class="block"><input type="checkbox" class="mr-2">Abroad (please specify)</label>
                            <input type="text" class="mt-1 w-full rounded border border-black px-2 py-1" placeholder="Specify country">
                        </div>
                    </div>

                    <div class="mt-4 space-y-4 border-t border-black pt-4">
                        <div>
                            <p class="mb-2 font-medium">In case of sick leave</p>
                            <div class="space-y-1">
                                <label class="block"><input type="checkbox" class="mr-2">In hospital (please specify)</label>
                                <input type="text" class="w-full rounded border border-black px-2 py-1" placeholder="Hospital name">
                                <label class="block"><input type="checkbox" class="mr-2">Outpatient (please specify)</label>
                                <input type="text" class="w-full rounded border border-black px-2 py-1" placeholder="Outpatient details">
                            </div>
                        </div>

                        <div>
                            <p class="mb-2 font-medium">Commutation</p>
                            <label class="block"><input type="radio" name="commutation" value="Requested" class="mr-2">Requested</label>
                            <label class="block"><input type="radio" name="commutation" value="Not Requested" class="mr-2">Not Requested</label>
                        </div>

                        <div class="pt-8 text-center">
                            <div class="mx-auto h-0.5 w-full max-w-xs border-b border-black"></div>
                            <p class="mt-2 font-medium">Signature of Applicant</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-t border-black pt-6">
            <h5 class="mb-6 text-center font-bold tracking-wide uppercase">Details on Action of Application</h5>

            <div class="print-action-two grid grid-cols-1 gap-8 md:grid-cols-2">
                <div>
                    <label class="block font-medium">
                        Certification of Leave Credits (As of)
                        <input type="text" value="{{ now()->format('F, Y') }}" class="mt-1 w-full border-0 border-b-2 border-black px-0 py-1 focus:outline-none focus:ring-0" readonly>
                    </label>

                    <table class="mt-3 w-full border-collapse border border-black text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-black px-2 py-1"></th>
                                <th class="border border-black px-2 py-1">Vacation</th>
                                <th class="border border-black px-2 py-1">Sick</th>
                                <th class="border border-black px-2 py-1">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-black px-2 py-1">Beginning Balance</td>
                                <td id="beginning-vacation-balance" class="border border-black px-2 py-1">{{ rtrim(rtrim(number_format((float) ($beginningVacationBalance ?? 0), 1, '.', ''), '0'), '.') }}</td>
                                <td id="beginning-sick-balance" class="border border-black px-2 py-1">{{ rtrim(rtrim(number_format((float) ($beginningSickBalance ?? 0), 1, '.', ''), '0'), '.') }}</td>
                                <td id="beginning-total-balance" class="border border-black px-2 py-1">{{ rtrim(rtrim(number_format((float) (($beginningVacationBalance ?? 0) + ($beginningSickBalance ?? 0)), 1, '.', ''), '0'), '.') }}</td>
                            </tr>
                            <tr>
                                <td class="border border-black px-2 py-2">
                                    <span class="block">Add: Earned Leave/s</span>
                                    <span class="block">Date: {{ $earnedRangeLabel ?? '-' }}</span>
                                </td>
                                <td id="earned-vacation-balance" class="border border-black px-2 py-1">{{ rtrim(rtrim(number_format($formEarnedVacationValue, 1, '.', ''), '0'), '.') }}</td>
                                <td id="earned-sick-balance" class="border border-black px-2 py-1">{{ rtrim(rtrim(number_format($formEarnedSickValue, 1, '.', ''), '0'), '.') }}</td>
                                <td id="earned-total-balance" class="border border-black px-2 py-1">{{ rtrim(rtrim(number_format($formEarnedTotalValue, 1, '.', ''), '0'), '.') }}</td>
                            </tr>
                            <tr>
                                <td class="border border-black px-2 py-1">Less: Applied Leave/s</td>
                                <td id="applied-vacation-balance" class="border border-black px-2 py-1">0</td>
                                <td id="applied-sick-balance" class="border border-black px-2 py-1">0</td>
                                <td id="applied-total-balance" class="border border-black px-2 py-1">0</td>
                            </tr>
                            <tr>
                                <td class="border border-black px-2 py-1">Ending Balance</td>
                                <td id="ending-vacation-balance" class="border border-black px-2 py-1">{{ rtrim(rtrim(number_format((float) (($beginningVacationBalance ?? 0) + $formEarnedVacationValue), 1, '.', ''), '0'), '.') }}</td>
                                <td id="ending-sick-balance" class="border border-black px-2 py-1">{{ rtrim(rtrim(number_format((float) (($beginningSickBalance ?? 0) + $formEarnedSickValue), 1, '.', ''), '0'), '.') }}</td>
                                <td id="ending-total-balance" class="border border-black px-2 py-1">{{ rtrim(rtrim(number_format((float) ((($beginningVacationBalance ?? 0) + $formEarnedVacationValue) + (($beginningSickBalance ?? 0) + $formEarnedSickValue)), 1, '.', ''), '0'), '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="space-y-4 border-l border-black pl-5">
                    <p class="font-medium">Recommendation</p>
                    <label class="block"><input type="checkbox" name="recommendation" class="mr-2">Approved</label>
                    <div>
                        <label class="block"><input type="checkbox" name="recommendation" class="mr-2">Disapproved due to:</label>
                        <div class="mt-2 h-0.5 w-full border-b border-black"></div>
                    </div>

                    <div class="pt-10 text-center">
                        <div class="mx-auto h-0.5 w-full max-w-xs border-b border-black"></div>
                        <p class="mt-2 font-medium">Authorized Signature</p>
                    </div>
                </div>
            </div>

            <div class="print-action-two mt-6 grid grid-cols-1 gap-8 border-t border-black pt-5 md:grid-cols-2">
                <div class="space-y-3">
                    <p class="font-medium">Approved for:</p>
                    <div class="flex items-end gap-2">
                        <div id="days-with-pay-value" class="min-w-[6rem] border-b border-black text-center">0</div>
                        <span>Day(s) with pay</span>
                    </div>
                    <div class="flex items-end gap-2">
                        <div id="days-without-pay-value" class="min-w-[6rem] border-b border-black text-center">0</div>
                        <span>Day(s) without pay</span>
                    </div>
                    <div class="flex items-end gap-2">
                        <div class="h-0.5 w-24 border-b border-black"></div>
                        <span>Others (please specify)</span>
                    </div>
                </div>

                <div>
                    <p class="font-medium">Disapproved due to:</p>
                    <div class="mt-2 h-0.5 w-full border-b border-black"></div>
                </div>
            </div>

            <div class="print-signatory-margin mt-10 grid grid-cols-2 gap-10 text-sm">
                <div class="text-center">
                    <div class="mx-auto h-0.5 w-72 border-b border-black"></div>
                    <p class="mt-2 font-semibold">President</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto h-0.5 w-72 border-b border-black"></div>
                    <p class="mt-2 font-semibold">Director of Human Resources</p>
                </div>
            </div>

            <div class="mt-10 text-center">
                <div class="mx-auto h-0.5 w-52 border-b border-black"></div>
                <p class="mt-2 font-semibold">Date</p>
            </div>
        </section>
    </div>

    <input type="hidden" name="leave_type" id="leave-type-hidden">
    <input type="hidden" name="as_of_label" value="{{ now()->format('F, Y') }}">
    <input type="hidden" name="earned_date_label" value="{{ $earnedRangeLabel ?? '-' }}">
    <input type="hidden" name="beginning_vacation" id="beginning-vacation-hidden" value="{{ (float) ($beginningVacationBalance ?? 0) }}">
    <input type="hidden" name="beginning_sick" id="beginning-sick-hidden" value="{{ (float) ($beginningSickBalance ?? 0) }}">
    <input type="hidden" name="beginning_total" id="beginning-total-hidden" value="{{ (float) (($beginningVacationBalance ?? 0) + ($beginningSickBalance ?? 0)) }}">
    <input type="hidden" name="earned_vacation" id="earned-vacation-hidden" value="{{ $formEarnedVacationValue }}">
    <input type="hidden" name="earned_sick" id="earned-sick-hidden" value="{{ $formEarnedSickValue }}">
    <input type="hidden" name="earned_total" id="earned-total-hidden" value="{{ $formEarnedTotalValue }}">
    <input type="hidden" name="applied_vacation" id="applied-vacation-hidden" value="0">
    <input type="hidden" name="applied_sick" id="applied-sick-hidden" value="0">
    <input type="hidden" name="applied_total" id="applied-total-hidden" value="0">
    <input type="hidden" name="ending_vacation" id="ending-vacation-hidden" value="{{ (float) (($beginningVacationBalance ?? 0) + $formEarnedVacationValue) }}">
    <input type="hidden" name="ending_sick" id="ending-sick-hidden" value="{{ (float) (($beginningSickBalance ?? 0) + $formEarnedSickValue) }}">
    <input type="hidden" name="ending_total" id="ending-total-hidden" value="{{ (float) ((($beginningVacationBalance ?? 0) + $formEarnedVacationValue) + (($beginningSickBalance ?? 0) + $formEarnedSickValue)) }}">
    <input type="hidden" name="days_with_pay" id="days-with-pay-hidden" value="0">
    <input type="hidden" name="days_without_pay" id="days-without-pay-hidden" value="0">

    <div class="flex justify-end">
        <button
            id="leave-application-download-button"
            type="button"
            onclick="downloadLeaveApplicationForm()"
            class="rounded-lg bg-blue-600 px-6 py-2 text-white hover:bg-blue-700"
        >
            Download Form
        </button>
    </div>
</form>

<script>
    const leaveBalanceState = {
        availableVacation: {{ json_encode((float) $availableVacationDays) }},
        availableSick: {{ json_encode((float) $availableSickDays) }},
        beginningVacation: {{ json_encode((float) ($beginningVacationBalance ?? 0)) }},
        beginningSick: {{ json_encode((float) ($beginningSickBalance ?? 0)) }},
        earnedVacation: {{ json_encode($formEarnedVacationValue) }},
        earnedSick: {{ json_encode($formEarnedSickValue) }},
    };

    function formatDayValue(value) {
        const safeValue = Number.isFinite(value) ? Math.max(0, value) : 0;
        return safeValue % 1 === 0 ? `${safeValue}` : safeValue.toFixed(1);
    }

    function validateLeaveRequestBalance() {
        const requestedDaysInput = document.getElementById('leave-days-requested');

        if (!requestedDaysInput) {
            return true;
        }

        const requestedDays = parseFloat(requestedDaysInput.value || '0');
        if (!Number.isFinite(requestedDays) || requestedDays <= 0) {
            return true;
        }
        return true;
    }

    function deriveLeaveTypeValue() {
        const vacationCheckbox = document.getElementById('leave-type-vacation');
        const sickCheckbox = document.getElementById('leave-type-sick');

        if (vacationCheckbox?.checked && sickCheckbox?.checked) {
            return 'Vacation/Sick';
        }
        if (vacationCheckbox?.checked) {
            return 'Annual Leave';
        }
        if (sickCheckbox?.checked) {
            return 'Sick Leave';
        }

        return '';
    }

    function updateLeaveSummaryTable() {
        const vacationCheckbox = document.getElementById('leave-type-vacation');
        const sickCheckbox = document.getElementById('leave-type-sick');
        const requestedDaysInput = document.getElementById('leave-days-requested');

        const requestedDaysRaw = parseFloat(requestedDaysInput?.value || '0');
        const requestedDays = Number.isFinite(requestedDaysRaw) && requestedDaysRaw > 0 ? requestedDaysRaw : 0;

        const availableVacation = Math.max(leaveBalanceState.beginningVacation + leaveBalanceState.earnedVacation, 0);
        const availableSick = Math.max(leaveBalanceState.beginningSick + leaveBalanceState.earnedSick, 0);

        let appliedVacation = 0;
        let appliedSick = 0;

        if (vacationCheckbox?.checked && !sickCheckbox?.checked) {
            appliedVacation = Math.min(requestedDays, availableVacation);
        } else if (sickCheckbox?.checked && !vacationCheckbox?.checked) {
            appliedSick = Math.min(requestedDays, availableSick);
        } else if (vacationCheckbox?.checked && sickCheckbox?.checked) {
            const splitHalf = requestedDays / 2;
            appliedVacation = Math.min(splitHalf, availableVacation);
            appliedSick = Math.min(splitHalf, availableSick);
        }

        const appliedTotal = appliedVacation + appliedSick;
        const endingVacation = Math.max(availableVacation - appliedVacation, 0);
        const endingSick = Math.max(availableSick - appliedSick, 0);
        const endingTotal = endingVacation + endingSick;
        const withoutPay = Math.max(requestedDays - appliedTotal, 0);

        const map = {
            'applied-vacation-balance': appliedVacation,
            'applied-sick-balance': appliedSick,
            'applied-total-balance': appliedTotal,
            'ending-vacation-balance': endingVacation,
            'ending-sick-balance': endingSick,
            'ending-total-balance': endingTotal,
            'days-with-pay-value': appliedTotal,
            'days-without-pay-value': withoutPay,
        };

        Object.keys(map).forEach((id) => {
            const el = document.getElementById(id);
            if (el) {
                el.textContent = formatDayValue(map[id]);
            }
        });

        const hiddenMap = {
            'leave-type-hidden': deriveLeaveTypeValue(),
            'applied-vacation-hidden': appliedVacation,
            'applied-sick-hidden': appliedSick,
            'applied-total-hidden': appliedTotal,
            'ending-vacation-hidden': endingVacation,
            'ending-sick-hidden': endingSick,
            'ending-total-hidden': endingTotal,
            'days-with-pay-hidden': appliedTotal,
            'days-without-pay-hidden': withoutPay,
        };

        Object.keys(hiddenMap).forEach((id) => {
            const el = document.getElementById(id);
            if (!el) {
                return;
            }

            const value = hiddenMap[id];
            if (typeof value === 'string') {
                el.value = value;
            } else {
                el.value = Number.isFinite(value) ? value.toFixed(1) : '0.0';
            }
        });
    }

    function formatInclusiveDateRange(startDate, endDate) {
        const startMonth = startDate.toLocaleString('en-US', { month: 'short' });
        const endMonth = endDate.toLocaleString('en-US', { month: 'short' });
        const startDay = startDate.getDate();
        const endDay = endDate.getDate();
        const startYear = startDate.getFullYear();
        const endYear = endDate.getFullYear();

        if (startYear === endYear && startMonth === endMonth) {
            if (startDay === endDay) {
                return `${startMonth} ${startDay}, ${startYear}`;
            }

            return `${startMonth} ${startDay}-${endDay}, ${startYear}`;
        }

        if (startYear === endYear) {
            return `${startMonth} ${startDay} - ${endMonth} ${endDay}, ${startYear}`;
        }

        return `${startMonth} ${startDay}, ${startYear} - ${endMonth} ${endDay}, ${endYear}`;
    }

    function updateInclusiveDatesFromRequestedDays() {
        const requestedDaysInput = document.getElementById('leave-days-requested');
        const inclusiveDatesInput = document.getElementById('leave-inclusive-dates');
        if (!requestedDaysInput || !inclusiveDatesInput) {
            return;
        }

        const requestedDays = parseFloat(requestedDaysInput.value || '0');
        if (!Number.isFinite(requestedDays) || requestedDays <= 0) {
            inclusiveDatesInput.value = '';
            return;
        }

        const wholeDays = Math.max(1, Math.ceil(requestedDays));
        const today = new Date();
        const startDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
        const endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + (wholeDays - 1));

        inclusiveDatesInput.value = formatInclusiveDateRange(startDate, endDate);
    }

    document.getElementById('leave-type-vacation')?.addEventListener('change', validateLeaveRequestBalance);
    document.getElementById('leave-type-sick')?.addEventListener('change', validateLeaveRequestBalance);
    document.getElementById('leave-type-vacation')?.addEventListener('change', updateLeaveSummaryTable);
    document.getElementById('leave-type-sick')?.addEventListener('change', updateLeaveSummaryTable);
    document.getElementById('leave-days-requested')?.addEventListener('input', function () {
        validateLeaveRequestBalance();
        updateInclusiveDatesFromRequestedDays();
        updateLeaveSummaryTable();
    });
    updateInclusiveDatesFromRequestedDays();
    updateLeaveSummaryTable();

    function buildLeaveApplicationPrintMarkup(printArea) {
        const clone = printArea.cloneNode(true);
        const originalFields = printArea.querySelectorAll('input, textarea, select');
        const clonedFields = clone.querySelectorAll('input, textarea, select');

        originalFields.forEach((field, index) => {
            const clonedField = clonedFields[index];
            if (!clonedField) {
                return;
            }

            if (field.tagName === 'INPUT') {
                const type = (field.getAttribute('type') || 'text').toLowerCase();
                if (type === 'checkbox' || type === 'radio') {
                    if (field.checked) {
                        clonedField.setAttribute('checked', 'checked');
                    } else {
                        clonedField.removeAttribute('checked');
                    }
                } else {
                    clonedField.setAttribute('value', field.value || '');
                }
            } else if (field.tagName === 'TEXTAREA') {
                clonedField.textContent = field.value || '';
            } else if (field.tagName === 'SELECT') {
                Array.from(clonedField.options).forEach((option, optionIndex) => {
                    const isSelected = field.options[optionIndex]?.selected;
                    if (isSelected) {
                        option.setAttribute('selected', 'selected');
                    } else {
                        option.removeAttribute('selected');
                    }
                });
            }
        });

        return clone.outerHTML;
    }

    async function saveLeaveApplicationRecord() {
        const form = document.getElementById('leave-application-form');
        if (!form) {
            return false;
        }

        const payload = new FormData(form);
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: payload,
        });

        return response.ok;
    }

    async function downloadLeaveApplicationForm() {
        if (!validateLeaveRequestBalance()) {
            return;
        }

        updateLeaveSummaryTable();
        const isSaved = await saveLeaveApplicationRecord();
        if (!isSaved) {
            console.error('Failed to save leave application');
            return;
        }

        const printArea = document.getElementById('leave-application-print-area');
        if (!printArea) {
            return;
        }

        const printWindow = window.open('', '_blank');
        if (!printWindow) {
            return;
        }

        const styles = Array.from(document.querySelectorAll('style, link[rel="stylesheet"]'))
            .map((node) => node.outerHTML)
            .join('');

        printWindow.document.open();
        printWindow.document.write(`
            <!doctype html>
            <html>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Leave Application Form</title>
                ${styles}
                <style>
                    body { margin: 0; padding: 0; background: #fff; }
                    #leave-application-print-area {
                        margin-left: 2px !important;
                        margin-right: 2px !important;
                        padding-left: 10px !important;
                        padding-right: 10px !important;
                    }
                </style>
            </head>
            <body>${buildLeaveApplicationPrintMarkup(printArea)}</body>
            </html>
        `);
        printWindow.document.close();

        printWindow.onload = function () {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
            window.location.reload();
        };
    }
</script>
