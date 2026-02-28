<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PeopleHub – HR Dashboard</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Alpine.js -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    body {
      font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif; transition: margin-left 0.3s ease;
    }
    main { transition: margin-left 0.3s ease; }
    aside ~ main { margin-left: 16rem; }
  </style>
</head>

<body class="bg-slate-100">

<div class="flex min-h-screen">

  <!-- Sidebar -->
  @include('components.adminSideBar')

  <!-- Main Content -->
<main class="flex-1 ml-16 transition-all duration-300"
      x-data="{
        openProfile:false,
        openEditProfile:false,
        modalTarget: '',
        tab:'overview',
        department:'All',
        statusFilter:'All',
        search:'',
        employeeIndex: [],
        normalize(value) {
          return (value ?? '').toString().trim().toLowerCase();
        },
        matchesDepartment(empDepartment) {
          if (this.department === 'All') return true;
          return this.normalize(empDepartment) === this.normalize(this.department);
        },
        matchesSearch(empName) {
          const query = this.normalize(this.search);
          if (!query) return true;
          return this.normalize(empName).includes(query);
        },
        matchesStatus(empStatus) {
          if (this.statusFilter === 'All') return true;
          return this.normalize(empStatus) === this.normalize(this.statusFilter);
        },
        hasVisibleEmployees() {
          return this.employeeIndex.some(emp =>
            this.matchesDepartment(emp.department) &&
            this.matchesSearch(emp.name) &&
            this.matchesStatus(emp.status)
          );
        },
        effectiveAccountStatus() {
          const accountStatus = (this.selectedEmployee?.account_status ?? '').toString().trim();
          if (accountStatus !== '') {
            if (accountStatus.toLowerCase() === 'inactive') {
              return 'Inactive';
            }
            if (accountStatus.toLowerCase() === 'on leave') {
              return 'On Leave';
            }
            if (accountStatus.toLowerCase() === 'active') {
              return 'Active';
            }
          }

          const hasApprovedOrCompletedResignation = (Array.isArray(this.selectedEmployee?.resignations)
            ? this.selectedEmployee.resignations
            : []
          ).some((row) => {
            const status = (row?.status ?? '').toString().trim().toLowerCase();
            return status === 'approved' || status === 'completed';
          });

          if (hasApprovedOrCompletedResignation) {
            return 'Inactive';
          }

          const hasApprovedLeave = (Array.isArray(this.selectedEmployee?.leave_applications)
            ? this.selectedEmployee.leave_applications
            : []
          ).some((row) => {
            const status = (row?.status ?? '').toString().trim().toLowerCase();
            return status === 'approved';
          });

          return hasApprovedLeave ? 'On Leave' : 'Active';
        },
        effectiveAccountStatusClass() {
          const status = this.effectiveAccountStatus().toLowerCase();
          if (status === 'active') return 'bg-green-100 text-green-700';
          if (status === 'on leave') return 'bg-orange-100/70 text-orange-700';
          return 'bg-red-100/70 text-red-700';
        },
        selectedEmployee: {
          applicant: { documents: [], required_documents: [], required_documents_text: '', missing_documents: [], document_notice: '', position: {} },
          employee: {},
          education: {},
          government: {},
          license: {},
          salary: {},
          leave_applications: [],
          ui_theme: {},
        },
        async setEmployee(emp) {
          const applicantPosition = emp?.applicant?.position ?? {};
          const employeeData = { ...(emp?.employee ?? {}) };

          if (!employeeData.position && applicantPosition.title) {
            employeeData.position = applicantPosition.title;
          }

          if (!employeeData.department && applicantPosition.department) {
            employeeData.department = applicantPosition.department;
          }

          if (!employeeData.classification) {
            employeeData.classification = employeeData.job_type
              || applicantPosition.job_type
              || null;
          }

          this.selectedEmployee = {
            ...emp,
            applicant: { documents: [], required_documents: [], required_documents_text: '', missing_documents: [], document_notice: '', position: {}, ...(emp?.applicant ?? {}) },
            employee: employeeData,
            education: emp?.education ?? {},
            government: emp?.government ?? {},
            license: emp?.license ?? {},
            salary: emp?.salary ?? {},
            leave_applications: Array.isArray(emp?.leave_applications) ? emp.leave_applications : [],
            ui_theme: emp?.ui_theme ?? {},
          };

          await this.loadDocuments(emp?.id);
        },
        async loadDocuments(userId) {
          if (!userId || !this.selectedEmployee?.applicant?.id) return;

          try {
            const response = await fetch(`/system/employee/${userId}/documents`, {
              headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!response.ok) return;

            const payload = await response.json();
            this.selectedEmployee.applicant.documents = payload.documents ?? [];
            this.selectedEmployee.applicant.required_documents = payload.required_documents ?? [];
            this.selectedEmployee.applicant.required_documents_text = payload.required_documents_text ?? '';
            this.selectedEmployee.applicant.missing_documents = payload.missing_documents ?? [];
            this.selectedEmployee.applicant.document_notice = payload.document_notice ?? '';
          } catch (error) {
            console.error('Unable to load employee documents.', error);
          }
        },
        removeMissingDocumentNeed(documentName) {
          const applicant = this.selectedEmployee?.applicant;
          if (!applicant) return;

          const normalize = (value) => this.normalize(value);
          const textItems = (applicant.required_documents_text ?? '')
            .split(/\r?\n|,/)
            .map(item => item.trim())
            .filter(item => item !== '');
          const baseItems = textItems.length ? textItems : (applicant.required_documents ?? []);
          const filteredItems = baseItems.filter(item => normalize(item) !== normalize(documentName));

          applicant.required_documents = filteredItems;
          applicant.required_documents_text = filteredItems.join('\n');
          applicant.missing_documents = (applicant.missing_documents ?? [])
            .filter(item => normalize(item) !== normalize(documentName));
        },
        leaveRows() {
          return Array.isArray(this.selectedEmployee?.leave_applications)
            ? this.selectedEmployee.leave_applications
            : [];
        },
        leaveStatusNormalized(value) {
          return (value ?? '').toString().trim().toLowerCase();
        },
        isApprovedLeaveStatus(value) {
          const status = this.leaveStatusNormalized(value);
          return status === 'approved' || status === 'completed';
        },
        numberOrZero(value) {
          const numeric = Number(value);
          return Number.isFinite(numeric) ? numeric : 0;
        },
        latestLeaveBalanceRow() {
          const rows = this.leaveRows();
          if (!rows.length) return null;

          const approvedRows = rows.filter(row => this.isApprovedLeaveStatus(row?.status));
          const sourceRows = approvedRows.length ? approvedRows : rows;

          const withSort = sourceRows
            .map((row) => ({
              row,
              sortDate: this.parseDateValue(row?.filing_date || row?.created_at),
            }))
            .sort((a, b) => {
              const ta = a.sortDate?.getTime() ?? 0;
              const tb = b.sortDate?.getTime() ?? 0;
              return tb - ta;
            });

          return withSort[0]?.row ?? null;
        },
        leaveVacationLimit() {
          const row = this.latestLeaveBalanceRow();
          if (!row) return 0;
          return Math.max(
            this.numberOrZero(row?.beginning_vacation) + this.numberOrZero(row?.earned_vacation),
            0
          );
        },
        leaveVacationAvailable() {
          const row = this.latestLeaveBalanceRow();
          if (!row) return 0;
          return Math.max(this.numberOrZero(row?.ending_vacation), 0);
        },
        leaveSickLimit() {
          const row = this.latestLeaveBalanceRow();
          if (!row) return 0;
          return Math.max(
            this.numberOrZero(row?.beginning_sick) + this.numberOrZero(row?.earned_sick),
            0
          );
        },
        leaveSickAvailable() {
          const row = this.latestLeaveBalanceRow();
          if (!row) return 0;
          return Math.max(this.numberOrZero(row?.ending_sick), 0);
        },
        leavePersonalLimit() {
          return 3;
        },
        leavePersonalUsed() {
          return this.leaveRows()
            .filter(row => this.isApprovedLeaveStatus(row?.status))
            .filter((row) => {
              const type = (row?.leave_type ?? '').toString().trim().toLowerCase();
              return type.includes('personal');
            })
            .reduce((sum, row) => sum + this.numberOrZero(row?.number_of_working_days), 0);
        },
        leavePersonalAvailable() {
          return Math.max(this.leavePersonalLimit() - this.leavePersonalUsed(), 0);
        },
        leaveBalancePercent(available, limit) {
          const safeLimit = this.numberOrZero(limit);
          if (safeLimit <= 0) return 0;
          const percent = (this.numberOrZero(available) / safeLimit) * 100;
          return Math.max(0, Math.min(100, percent));
        },
        formatLeaveNumber(value) {
          const normalized = Math.max(this.numberOrZero(value), 0);
          if (Number.isInteger(normalized)) {
            return normalized.toString();
          }
          return normalized.toFixed(1).replace(/\.0$/, '');
        },
        formatLeaveBalance(available, limit) {
          return `${this.formatLeaveNumber(available)}/${this.formatLeaveNumber(limit)}`;
        },
        parseDateValue(raw) {
          if (!raw) return null;
          const datePart = raw.toString().split('T')[0];
          const [year, month, day] = datePart.split('-').map(Number);
          if (!year || !month || !day) return null;
          const date = new Date(year, month - 1, day);
          return Number.isNaN(date.getTime()) ? null : date;
        },
        formatTimelineDate(raw) {
          const date = this.parseDateValue(raw);
          if (!date) return '-';
          return date.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
        },
        formatServiceLength(startRaw, endRaw) {
          const start = this.parseDateValue(startRaw);
          const end = this.parseDateValue(endRaw);
          if (!start || !end) return '';
          if (end.getTime() < start.getTime()) return '';

          let years = end.getFullYear() - start.getFullYear();
          let months = end.getMonth() - start.getMonth();
          let days = end.getDate() - start.getDate();

          if (days < 0) {
            const previousMonthDays = new Date(end.getFullYear(), end.getMonth(), 0).getDate();
            days += previousMonthDays;
            months -= 1;
          }
          if (months < 0) {
            months += 12;
            years -= 1;
          }

          if (years < 0) return '';
          return `${years}.${months} years`;
        },
        normalizeClassificationValue(value) {
          return (value ?? '')
            .toString()
            .trim()
            .toLowerCase()
            .replace(/[-_]/g, ' ')
            .replace(/\s+/g, ' ');
        },
        isProbationaryToPermanent(oldValue, newValue) {
          const oldNorm = this.normalizeClassificationValue(oldValue);
          const newNorm = this.normalizeClassificationValue(newValue);
          return oldNorm === 'probationary' && newNorm === 'permanent';
        },
        countPromotionEvents() {
          const historyRows = Array.isArray(this.selectedEmployee?.position_histories)
            ? this.selectedEmployee.position_histories
            : [];

          return historyRows.filter((row) => {
            const oldPos = (row?.old_position ?? '').toString().trim().toLowerCase();
            const newPos = (row?.new_position ?? '').toString().trim().toLowerCase();
            const positionChanged = oldPos !== '' && newPos !== '' && oldPos !== newPos;
            const classPromotion = this.isProbationaryToPermanent(
              row?.old_classification,
              row?.new_classification
            );
            return positionChanged || classPromotion;
          }).length;
        },
        buildServiceTimeline() {
          const items = [];
          const hiredRaw = this.selectedEmployee?.applicant?.date_hired || this.selectedEmployee?.employee?.employement_date;
          const positionTitle = this.selectedEmployee?.applicant?.position?.title || this.selectedEmployee?.employee?.position || 'Employee';

          if (hiredRaw) {
            items.push({
              type: 'hire',
              badge: 'Date Hired',
              badgeClass: 'bg-green-100 text-green-700',
              dotClass: 'bg-indigo-500',
              title: 'Initial Employment Start',
              dateLabel: this.formatTimelineDate(hiredRaw),
              description: `Employee was hired as ${positionTitle}.`,
              sortKey: hiredRaw,
            });
          }

          const resignationRows = Array.isArray(this.selectedEmployee?.resignations)
            ? this.selectedEmployee.resignations
            : [];

          resignationRows.forEach((row) => {
            const status = (row?.status ?? '').toString().trim().toLowerCase();
            const submittedAt = row?.submitted_at || row?.created_at;
            const effectiveAt = row?.effective_date;
            const processedAt = row?.processed_at;

            if (submittedAt) {
              items.push({
                type: 'resignation-submitted',
                badge: 'Resignation Filed',
                badgeClass: 'bg-amber-100 text-amber-700',
                dotClass: 'bg-amber-500',
                title: 'Resignation Submission',
                dateLabel: this.formatTimelineDate(submittedAt),
                description: 'Employee submitted a resignation request.',
                sortKey: submittedAt,
              });
            }

            if (processedAt && (status === 'approved' || status === 'completed')) {
              const serviceLength = this.formatServiceLength(hiredRaw, effectiveAt || processedAt);
              const baseDescription = row?.admin_note || 'HR approved the resignation request.';
              items.push({
                type: 'resignation-approved',
                badge: 'Resignation Approved',
                badgeClass: 'bg-blue-100 text-blue-700',
                dotClass: 'bg-blue-500',
                title: 'Resignation Request Approved',
                dateLabel: this.formatTimelineDate(processedAt),
                description: serviceLength !== ''
                  ? `${baseDescription} Total length of service: ${serviceLength}.`
                  : baseDescription,
                sortKey: processedAt,
              });
            }

            if (processedAt && (status === 'rejected' || status === 'cancelled')) {
              items.push({
                type: status === 'rejected' ? 'resignation-rejected' : 'resignation-cancelled',
                badge: status === 'rejected' ? 'Resignation Rejected' : 'Resignation Cancelled',
                badgeClass: status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-slate-200 text-slate-700',
                dotClass: status === 'rejected' ? 'bg-rose-500' : 'bg-slate-500',
                title: status === 'rejected' ? 'Resignation Request Rejected' : 'Resignation Request Cancelled',
                dateLabel: this.formatTimelineDate(processedAt),
                description: row?.admin_note || 'Status was updated by HR.',
                sortKey: processedAt,
              });
            }
          });

          const positionHistoryRows = Array.isArray(this.selectedEmployee?.position_histories)
            ? this.selectedEmployee.position_histories
            : [];

          positionHistoryRows.forEach((row) => {
            const newPosition = (row?.new_position ?? '').toString().trim();
            const changedAt = row?.changed_at || row?.created_at;
            if (!changedAt) return;

            const oldPosition = (row?.old_position ?? '').toString().trim();
            const oldClassification = (row?.old_classification ?? '').toString().trim();
            const newClassification = (row?.new_classification ?? '').toString().trim();
            const hasPositionChange = oldPosition !== '' && newPosition !== '' && oldPosition.toLowerCase() !== newPosition.toLowerCase();
            const hasProbationaryPromotion = this.isProbationaryToPermanent(oldClassification, newClassification);

            if (hasPositionChange) {
              items.push({
                type: 'promotion-position',
                badge: 'Promotion',
                badgeClass: 'bg-emerald-100 text-emerald-700',
                dotClass: 'bg-emerald-500',
                title: 'Position Update',
                dateLabel: this.formatTimelineDate(changedAt),
                description: `Position changed from ${oldPosition} to ${newPosition}.`,
                sortKey: changedAt,
              });
            }

            if (hasProbationaryPromotion) {
              items.push({
                type: 'promotion-classification',
                badge: 'Promotion',
                badgeClass: 'bg-emerald-100 text-emerald-700',
                dotClass: 'bg-emerald-500',
                title: 'Employment Status Update',
                dateLabel: this.formatTimelineDate(changedAt),
                description: 'Classification changed from Probationary to Permanent.',
                sortKey: changedAt,
              });
            }
          });

          return items.sort((a, b) => {
            const ta = this.parseDateValue(a.sortKey)?.getTime() ?? 0;
            const tb = this.parseDateValue(b.sortKey)?.getTime() ?? 0;
            return tb - ta;
          });
        },
      }"
      x-init="employeeIndex = @js(
        $employee->map(fn($emp) => [
          'name' => trim(($emp->first_name ?? '').' '.($emp->middle_name ?? '').' '.($emp->last_name ?? '')),
          'department' => trim((string) (data_get($emp, 'applicant.position.department') ?: data_get($emp, 'employee.department') ?: '')),
          'status' => $emp->account_status ?? '',
        ])->values()
      )"
>

    <!-- Header -->
    @php
      $resolveDepartment = function ($emp) {
        return trim((string) (data_get($emp, 'applicant.position.department') ?: data_get($emp, 'employee.department') ?: ''));
      };

      $departmentOptions = $employee
        ->map(fn($emp) => $resolveDepartment($emp))
        ->filter(fn($dept) => $dept !== '')
        ->unique(fn($dept) => strtolower($dept))
        ->sort()
        ->values();
    @endphp
    @include('components.adminHeader.employeeHeader', ['departmentOptions' => $departmentOptions])

    <!-- ================= DASHBOARD CONTENT ================= -->
<div class="p-4 md:p-8 space-y-6 pt-20">

    <!-- TOP BAR -->
<div class="flex items-center justify-between" style="margin-top: -20px;">

    <!-- Status Legend -->
    <div class="flex items-center gap-6 text-sm text-gray-600">

        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-green-500"></span>
            Active
        </div>

        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
            On Leave
        </div>

        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-500"></span>
            Inactive
        </div>

    </div>
    <!-- FILTER BAR -->



</div>




    <!-- Employee Cards Grid -->
    <div class="flex flex-wrap gap-6">

        @foreach ($employee as $emp)
        @php
          $themeSeed = (string) ($emp->id ?? data_get($emp, 'employee.employee_id') ?? $loop->index);
          $hue = ((int) sprintf('%u', crc32($themeSeed))) % 360;
          $headerStart = "hsl({$hue}, 78%, 58%)";
          $headerEndHue = ($hue + 36) % 360;
          $headerEnd = "hsl({$headerEndHue}, 78%, 46%)";
          $avatarHue = ($hue + 18) % 360;
          $avatarColor = "hsl({$avatarHue}, 72%, 40%)";
        @endphp
        <!-- Employee Card -->
        <div
            class="bg-white rounded-xl shadow-md overflow-hidden w-72"
            x-show="matchesDepartment(@js($resolveDepartment($emp))) &&
                    matchesSearch(@js(trim(($emp->first_name ?? '').' '.($emp->middle_name ?? '').' '.($emp->last_name ?? ''))) ) &&
                    matchesStatus(@js($emp->account_status ?? ''))"
        >
            <div class="h-24 flex justify-center items-center" style="background-image: linear-gradient(to right, {{ $headerStart }}, {{ $headerEnd }});">
                <div class="w-16 h-16 rounded-full text-white flex items-center justify-center text-lg font-bold border-4 border-white mt-24" style="background-color: {{ $avatarColor }};">
                    {{$emp->initials}}
                </div>
            </div>

            <div class="p-4 mt-7">
                <h3 class="font-bold text-gray-800 text-lg text-center">{{$emp->first_name ?? ''}} {{$emp->last_name ?? ''}}</h3>
                <p class="text-gray-500 text-sm text-center">{{$emp->applicant->position->title ?? $emp->employee->position ?? ''}}</p>

                <div class="mt-4 space-y-1 text-gray-500 text-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa-regular fa-id-badge"></i>
                        {{ $emp->employee->employee_id ?? '' }}
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-sitemap"></i>
                        {{ $resolveDepartment($emp) }}
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-calendar"></i>
                        Hired {{$emp->applicant?->formatted_date_hired }}
                    </div>
                </div>

                <hr class="my-3">

                <div class="flex justify-between items-center">
                    <div class="flex items-center -space-x-">
                        @php
                          $accountStatus = trim((string) ($emp->account_status ?? 'Active'));
                          $normalizedStatus = strtolower($accountStatus);
                          $statusBadgeClass = match ($normalizedStatus) {
                            'active' => 'text-green-700 bg-green-100',
                            'on leave' => 'text-orange-700 bg-orange-100/70',
                            default => 'text-red-700 bg-red-100/70',
                          };
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium z-10 {{ $statusBadgeClass }}">
                            {{ $emp->account_status ?? 'Active' }}
                        </span>

                        <!--<span class="px-2 py-1 text-indigo-700 bg-indigo-100 rounded-full text-xs font-medium">
                            Rehire
                        </span>-->
                    </div>
                    <button
                        @click="openProfile = true; setEmployee({ ...@js($emp), ui_theme: { header_start: @js($headerStart), header_end: @js($headerEnd) } });"
                        class="text-blue-500 text-sm font-medium hover:underline">
                        View Profile
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div
      x-show="!hasVisibleEmployees()"
      class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg px-4 py-3"
      style="display:none;"
    >
      No employee found for your filter/search.
    </div>


</div>


    <!-- ================= PROFILE MODAL ================= -->
    <div
      x-show="openProfile"
      x-transition
      @click.self="openProfile=false"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-40"
      style="display:none"
    >
      <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-y-auto overflow-hidden">

        <div
          class="p-6 text-white relative"
          :style="`background-image: linear-gradient(to right, ${selectedEmployee?.ui_theme?.header_start || 'rgb(168 85 247)'}, ${selectedEmployee?.ui_theme?.header_end || 'rgb(99 102 241)'})`"
        >
          <button @click="openProfile=false" class="absolute top-4 right-4 text-2xl">&times;</button>

          <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center font-bold"
            x-text="selectedEmployee?.initials"
            >
            </div>
            <div>
              <h2 class="text-xl font-semibold"
              x-text="selectedEmployee?.applicant?.first_name + ' ' + selectedEmployee?.applicant?.last_name"
              ></h2>
              <p class="text-sm">
                <span x-text="selectedEmployee?.applicant?.position?.title ?? selectedEmployee?.employee?.position ?? '-'"></span><br>
                <span x-text="selectedEmployee?.applicant?.position?.department ?? selectedEmployee?.employee?.department ?? '-'"></span>
              </p>
            </div>
            <span
              class="ml-auto text-xs px-3 py-1 rounded-full"
              :class="effectiveAccountStatusClass()"
              x-text="effectiveAccountStatus()"
            ></span>
          </div>
        </div>

        <!-- Tabs -->
        <div class="flex gap-6 px-6 pt-4 border-b text-sm">
          <button @click="tab='overview'" :class="tab==='overview' ? 'text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-2' : 'text-gray-500'">Overview</button>
          <button @click="tab='personal'" :class="tab==='personal' ? 'text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-2' : 'text-gray-500'">Personal Details</button>
          <button @click="tab='performance'" :class="tab==='performance' ? 'text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-2' : 'text-gray-500'">Performance</button>
          <button @click="tab='documents'" :class="tab==='documents' ? 'text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-2' : 'text-gray-500'">Documents</button>
          <button @click="tab='record'" :class="tab==='record' ? 'text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-2' : 'text-gray-500'">Service Record</button>
          <button @click="tab='biometric'" :class="tab==='biometric' ? 'text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-2' : 'text-gray-500'">Biometric</button>
        </div>

        @include('Admin.PersonalDetail.adminEmployeeOverview')
        @include('Admin.PersonalDetail.adminEmployeePD')
        @include('Admin.PersonalDetail.adminEmployeePerformance')
        @include('Admin.PersonalDetail.adminEmployeeDocuments')
        @include('Admin.PersonalDetail.adminServiceRecord')
        @include('Admin.PersonalDetail.adminbioMetric')



        <!-- Footer -->
        <div class="flex gap-3 p-6 border-t">
          <button class="flex-1 bg-indigo-600 text-white py-2 rounded-lg">Send Message</button>
          <button
            @click="openEditProfile = true; modalTarget = 'general'"
            class="flex-1 bg-slate-100 py-2 rounded-lg hover:bg-slate-200">
            Edit Profile
          </button>
        </div>

      </div>
    </div>



    <!-- ================= PROFILE EDIT ================= -->
    @include('Admin.PersonalDetail.editProfile')

  </main>
</div>

</body>

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

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</html>
