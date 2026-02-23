<style>
    @page {
        size: legal portrait;
        margin-top: 2mm;
        margin-bottom: 2mm;
        margin-left: -70px;
        margin-right: -70px;

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
    $obfAvailableVacationDays = max((float) ($beginningVacationBalance ?? 0) + $formEarnedVacationValue, 0);
    $obfAvailableSickDays = max((float) ($beginningSickBalance ?? 0) + $formEarnedSickValue, 0);
@endphp

        <form id="application-obf-form" method="POST" action="{{ route('employee.leaveApplication.store') }}" class="space-y-6">
            @csrf

            <!-- APPLICATION FORM FOR OFFICIAL BUSINESS AND OFFICIAL TIME -->
            <div id="application-obf-print-area" class="border-2 border-black p-6 rounded-lg space-y-4 ">

                <h4 class="text-center font-semibold text-gray-800 mb-6 tracking-wide uppercase">
                    APPLICATION FORM FOR OFFICIAL BUSINESS AND OFFICIAL TIME
                </h4>

                <!-- Top Information -->
                <div class="print-row-two grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium ">Office / Department</label>
                        <input name="office_department" type="text" class="w-full border rounded px-3 py-2 border-black">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Name (Last, First, Middle)</label>
                        <input
                            name="employee_name"
                            type="text"
                            value="{{ $employeeFormName ?? $employeeDisplayName ?? '' }}"
                            class="w-full border rounded px-3 py-2 border-black"
                        >
                    </div>
                </div>

                <div class="print-row-three grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium">Date of Filing</label>
                        <input id="obf-filing-date" name="filing_date" type="date" class="w-full border rounded px-3 py-2 border-black">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Position</label>
                        <input
                            name="position"
                            type="text"
                            value="{{ $employeeFormPosition ?? '' }}"
                            class="w-full border rounded px-3 py-2 border-black"
                        >
                    </div>

                    <div>
                        <label class="text-sm font-medium">Salary</label>
                        <input name="salary" type="text" class="w-full border rounded px-3 py-2 border-black  ">
                    </div>
                </div>

                <!-- DETAILS OF APPLICATION -->
                <div class="border-t pt-4 border-black">
                    <h5 class="font-semibold mb-8 text-center tracking-wide uppercase">DETAILS OF APPLICATION</h5>

                    <div class="print-details-two grid grid-cols-1 md:grid-cols-2 gap-6 ">

                        <!-- Left Column -->
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium">Type of Leave</label>
                                <div class="space-y-1 text-sm">
                                    <label><input id="obf-type-business" type="checkbox" class="mr-2">Official Business</label><br>
                                    <label><input id="obf-type-time" type="checkbox" class="mr-2">Official Time</label><br>
                                    <label class="block">
                                        <input id="obf-type-others" type="checkbox" class="mr-2">
                                        Others (please specify):
                                    </label>
                                    <input id="obf-other-type" type="text" class="w-full border rounded px-2 py-1 mt-1 border-black" placeholder="Specify other type">
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-medium">
                                    Number of working days applied for:
                                </label>
                                <input id="obf-working-days" name="number_of_working_days" type="number" min="0" step="0.5" class="w-full border border-black rounded px-3 py-2">
                            </div>


                            <div>
                                <label class="text-sm font-medium">Inclusive Dates</label>
                                <input id="obf-inclusive-dates" name="inclusive_dates" type="text" class="w-full border rounded px-3 py-2 border-black" readonly>
                            </div>
                        </div>


                        <!-- Right Column -->
                        <div class="print-right-divider space-y-3 pl-4 border-l border-black">
                            <div>
                                <label class="text-sm ">
                                    Purpose of Business
                                </label>
                                <div class="space-y-1 text-sm">
                                    <input type="text" class="w-full border rounded px-2 py-1 mt-1 border-black" placeholder="Purpose of Business">
                                </div>
                            </div>

                            <div>
                                <label class="text-sm">Venue</label>
                                <div class="space-y-1 text-sm">
                                    <input type="text" class="w-full border rounded px-2 py-1 mt-1 border-black" placeholder="Venue location">
                                    <label class="flex items-center gap-2">
                                        Inclusive Dates
                                    </label>
                                    <input type="text" class="w-full border rounded px-2 py-1 mt-1 border-black" placeholder="Inclusive Dates">
                                </div>
                            </div>



                            <!-- Signature (CENTERED ONLY) -->
                            <div class="flex justify-center mt-6">
                                <div class="w-full md:w-1/2 text-center">
                                            <!-- Signature Line -->
                                    <div class="border-b border-gray-600 w-full h-0.5 mt-20"></div>
                                    <label class="text-sm font-medium block mb-2">Signature of Applicant</label>
                                </div>
                            </div>


                        </div>


                    </div>
                </div>



                <!-- DETAILS ON ACTION OF APPLICATION -->
                <div class="border-t pt-6 space-y-4 border-black">
                    <h5 class="font-semibold mb-8 text-center tracking-wide uppercase">DETAILS ON ACTION OF APPLICATION</h5>
                    <div class="print-action-two grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Leave Credits (Left Column) -->
                        <div>
                            <label class="text-sm font-medium">Recommendation</label>

                            <label class="block text-sm">
                                <input type="checkbox" name="recommendation" class="mr-2">
                                Approved
                            </label>

                            <label class="block text-sm">
                                <input type="checkbox" name="recommendation" class="mr-2">
                                Disapproved due to:
                                    <div class="w-full text-center" style="width: 120px; margin-left: 155px; margin-top: -35px;">
                                    <!-- Signature Line -->
                                    <div class="border-b border-gray-600 w-full h-0.5 mt-8"></div>
                                    </div>
                            </label>



                            <div class="flex justify-left" style="margin-top: -20px;">
                                <div class="w-full text-left">
                                    <!-- Signature Line -->
                                    <div class="border-b border-gray-600 h-0.5 mt-20" style="width:275px"></div>
                                    <label class="text-sm  block mb-2">Immediate Supervisor</label>
                                </div>
                            </div>
                            
                            <div class="flex justify-left" style="margin-top: 40px;">
                                <div class="w-full text-left">

                                    <!-- Name -->
                                    <h1 class="text-sm font-bold" style="margin-bottom: -6px; font-size: 17px;">
                                        DR. DIONICIO D. VILORIA, ACP
                                    </h1>

                                    <!-- Signature Line -->
                                    <div class="border-b border-gray-600 h-0.5 mt-1 mb-1" style="width:275px"></div>

                                    <!-- Position -->
                                    <label class="text-sm  block">
                                        Human Resources Director
                                    </label>

                                </div>
                            </div>
                        </div>
                    </div>



                     <!-- Final Approval -->
                    <div class="border-t pt-4 grid grid-cols-1 gap-6 border-black ">

                    <!-- Approved for (Left Column) -->
                    <div class="space-y-4">
                            <label class="block text-sm" style="margin-bottom: -18px;">
                                <input type="checkbox" name="recommendation" class="mr-2">
                                Approved
                            </label>

                            <label class="block text-sm">
                                <input type="checkbox" name="recommendation" class="mr-2">
                                Disapproved due to:
                                    <div class="w-full text-center" style="width: 120px; margin-left: 155px; margin-top: -35px;">
                                    <!-- Signature Line -->
                                    <div class="border-b border-gray-600 w-full h-0.5 mt-8"></div>
                                    </div>
                            </label>
                    </div>
                            <div class="flex justify-center mt-6" >
                                <div class="w-full text-center" style="width: 240px;">
                                    <h1 class="text-sm font-bold" style="margin-bottom: -80px; font-size: 17px;">
                                        TOMAS C. BAUTISTA, PhD
                                    </h1>
                                    <div class="border-b border-gray-600 w-full h-0.5 mt-20"></div>
                                    <label class="text-sm font-medium block mb-2">Presindent</label>
                                </div>
                            </div>
                            <label class="block text-sm">
                                Attachments:
                                    <div class="w-full text-center" style="width: 120px; margin-left: 85px; margin-top: -38px;">
                                    <!-- Signature Line -->
                                    <div class="border-b border-gray-600 w-full h-0.5 mt-8"></div>
                                    </div>
                            </label>
                            <label class="block text-sm" style="margin-top: -20px;">
                                Date:
                                    <div class="w-full text-center" style="width: 120px; margin-left: 35px; margin-top: -38px;">
                                    <!-- Signature Line -->
                                    <div class="border-b border-gray-600 w-full h-0.5 mt-8"></div>
                                    </div>
                            </label>

                    </div>


            </div>

            <input type="hidden" name="leave_type" id="obf-leave-type-hidden">
            <input type="hidden" name="as_of_label" value="{{ now()->format('F, Y') }}">
            <input type="hidden" name="earned_date_label" value="{{ $earnedRangeLabel ?? '-' }}">
            <input type="hidden" name="beginning_vacation" id="obf-beginning-vacation-hidden" value="{{ (float) ($beginningVacationBalance ?? 0) }}">
            <input type="hidden" name="beginning_sick" id="obf-beginning-sick-hidden" value="{{ (float) ($beginningSickBalance ?? 0) }}">
            <input type="hidden" name="beginning_total" id="obf-beginning-total-hidden" value="{{ (float) (($beginningVacationBalance ?? 0) + ($beginningSickBalance ?? 0)) }}">
            <input type="hidden" name="earned_vacation" id="obf-earned-vacation-hidden" value="{{ $formEarnedVacationValue }}">
            <input type="hidden" name="earned_sick" id="obf-earned-sick-hidden" value="{{ $formEarnedSickValue }}">
            <input type="hidden" name="earned_total" id="obf-earned-total-hidden" value="{{ $formEarnedTotalValue }}">
            <input type="hidden" name="applied_vacation" id="obf-applied-vacation-hidden" value="0">
            <input type="hidden" name="applied_sick" id="obf-applied-sick-hidden" value="0">
            <input type="hidden" name="applied_total" id="obf-applied-total-hidden" value="0">
            <input type="hidden" name="ending_vacation" id="obf-ending-vacation-hidden" value="{{ $obfAvailableVacationDays }}">
            <input type="hidden" name="ending_sick" id="obf-ending-sick-hidden" value="{{ $obfAvailableSickDays }}">
            <input type="hidden" name="ending_total" id="obf-ending-total-hidden" value="{{ (float) ($obfAvailableVacationDays + $obfAvailableSickDays) }}">
            <input type="hidden" name="days_with_pay" id="obf-days-with-pay-hidden" value="0">
            <input type="hidden" name="days_without_pay" id="obf-days-without-pay-hidden" value="0">

            <!-- Download Button -->
            <div class="flex justify-end">
                <button
                    id="application-obf-download-button"
                    type="button"
                    onclick="downloadApplicationOBFForm()"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    Download Form
                </button>
            </div>
            
        </form>

        <script>
            const obfLeaveBalanceState = {
                beginningVacation: {{ json_encode((float) ($beginningVacationBalance ?? 0)) }},
                beginningSick: {{ json_encode((float) ($beginningSickBalance ?? 0)) }},
                earnedVacation: {{ json_encode($formEarnedVacationValue) }},
                earnedSick: {{ json_encode($formEarnedSickValue) }},
            };

            function buildApplicationOBFPrintMarkup(printArea) {
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

            function deriveOBFLeaveTypeValue() {
                const types = [];
                const businessCheckbox = document.getElementById('obf-type-business');
                const timeCheckbox = document.getElementById('obf-type-time');
                const othersCheckbox = document.getElementById('obf-type-others');
                const otherTypeInput = document.getElementById('obf-other-type');

                if (businessCheckbox?.checked) {
                    types.push('Official Business');
                }
                if (timeCheckbox?.checked) {
                    types.push('Official Time');
                }
                if (othersCheckbox?.checked) {
                    const otherLabel = (otherTypeInput?.value || '').trim();
                    types.push(otherLabel !== '' ? `Others: ${otherLabel}` : 'Others');
                }

                return types.join(', ');
            }

            function formatOBFInclusiveDateRange(startDate, endDate) {
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

            function updateOBFInclusiveDates() {
                const daysInput = document.getElementById('obf-working-days');
                const filingDateInput = document.getElementById('obf-filing-date');
                const inclusiveDatesInput = document.getElementById('obf-inclusive-dates');

                if (!daysInput || !inclusiveDatesInput) {
                    return;
                }

                const requestedDays = parseFloat(daysInput.value || '0');
                if (!Number.isFinite(requestedDays) || requestedDays <= 0) {
                    inclusiveDatesInput.value = '';
                    return;
                }

                const wholeDays = Math.max(1, Math.ceil(requestedDays));
                const baseDate = filingDateInput?.value
                    ? new Date(`${filingDateInput.value}T00:00:00`)
                    : new Date();
                const startDate = new Date(baseDate.getFullYear(), baseDate.getMonth(), baseDate.getDate());
                const endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + (wholeDays - 1));

                inclusiveDatesInput.value = formatOBFInclusiveDateRange(startDate, endDate);
            }

            function updateOBFPayDays() {
                const daysInput = document.getElementById('obf-working-days');
                const appliedVacationInput = document.getElementById('obf-applied-vacation-hidden');
                const appliedSickInput = document.getElementById('obf-applied-sick-hidden');
                const appliedTotalInput = document.getElementById('obf-applied-total-hidden');
                const endingVacationInput = document.getElementById('obf-ending-vacation-hidden');
                const endingSickInput = document.getElementById('obf-ending-sick-hidden');
                const endingTotalInput = document.getElementById('obf-ending-total-hidden');
                const withPayInput = document.getElementById('obf-days-with-pay-hidden');
                const withoutPayInput = document.getElementById('obf-days-without-pay-hidden');

                if (
                    !daysInput
                    || !appliedVacationInput
                    || !appliedSickInput
                    || !appliedTotalInput
                    || !endingVacationInput
                    || !endingSickInput
                    || !endingTotalInput
                    || !withPayInput
                    || !withoutPayInput
                ) {
                    return;
                }

                const availableVacation = Math.max(obfLeaveBalanceState.beginningVacation + obfLeaveBalanceState.earnedVacation, 0);
                const availableSick = Math.max(obfLeaveBalanceState.beginningSick + obfLeaveBalanceState.earnedSick, 0);
                const requestedDays = parseFloat(daysInput.value || '0');
                if (!Number.isFinite(requestedDays) || requestedDays <= 0) {
                    appliedVacationInput.value = '0.0';
                    appliedSickInput.value = '0.0';
                    appliedTotalInput.value = '0.0';
                    endingVacationInput.value = availableVacation.toFixed(1);
                    endingSickInput.value = availableSick.toFixed(1);
                    endingTotalInput.value = (availableVacation + availableSick).toFixed(1);
                    withPayInput.value = '0.0';
                    withoutPayInput.value = '0.0';
                    return;
                }

                // OBF types are treated as work-with-pay and do not consume leave credits.
                appliedVacationInput.value = '0.0';
                appliedSickInput.value = '0.0';
                appliedTotalInput.value = '0.0';
                endingVacationInput.value = availableVacation.toFixed(1);
                endingSickInput.value = availableSick.toFixed(1);
                endingTotalInput.value = (availableVacation + availableSick).toFixed(1);
                withPayInput.value = requestedDays.toFixed(1);
                withoutPayInput.value = '0.0';
            }

            async function saveApplicationOBFRecord() {
                const form = document.getElementById('application-obf-form');
                if (!form) {
                    return false;
                }

                const leaveTypeInput = document.getElementById('obf-leave-type-hidden');
                if (leaveTypeInput) {
                    leaveTypeInput.value = deriveOBFLeaveTypeValue();
                }
                updateOBFPayDays();

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

            document.getElementById('obf-working-days')?.addEventListener('input', () => {
                updateOBFInclusiveDates();
                updateOBFPayDays();
            });
            document.getElementById('obf-filing-date')?.addEventListener('change', updateOBFInclusiveDates);
            updateOBFInclusiveDates();
            updateOBFPayDays();

            async function downloadApplicationOBFForm() {
                const isSaved = await saveApplicationOBFRecord();
                if (!isSaved) {
                    console.error('Failed to save OBF application');
                    return;
                }

                const printArea = document.getElementById('application-obf-print-area');
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
                        <title>Application for Official Business / Official Time</title>
                        ${styles}
                        <style>
                            body {
                                margin: 0;
                                padding: 0;
                                background: #fff;
                            }
                            #obf-print-fit-wrapper {
                                width: 100%;
                            }
                            #application-obf-print-area {
                                width: 100% !important;
                                margin: 0 !important;
                                box-sizing: border-box !important;
                                min-height: 100vh !important;
                                padding-left: 12px !important;
                                padding-right: 12px !important;
                                border-radius: 0 !important;
                                font-size: 1.1rem !important;
                                line-height: 1.4 !important;
                            }
                        </style>
                    </head>
                    <body><div id="obf-print-fit-wrapper">${buildApplicationOBFPrintMarkup(printArea)}</div></body>
                    </html>
                `);
                printWindow.document.close();

                printWindow.onload = function () {
                    const wrapper = printWindow.document.getElementById('obf-print-fit-wrapper');
                    const content = printWindow.document.getElementById('application-obf-print-area');
                    if (wrapper && content) {
                        wrapper.style.transform = 'none';
                        wrapper.style.width = '100%';
                    }

                    printWindow.focus();
                    printWindow.print();
                    printWindow.close();
                    window.location.reload();
                };
            }
        </script>
