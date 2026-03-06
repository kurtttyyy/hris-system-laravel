<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Service Record Edit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #e8e6dd;
    }
    .paper {
      background: #fff;
      border: 1px solid #c9c7bf;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }
    .line-input {
      width: 100%;
      border: 0;
      border-bottom: 1px solid #1f2937;
      padding: 2px 2px 3px;
      background: transparent;
      font-size: 0.95rem;
      line-height: 1.2rem;
      color: #111827;
      border-radius: 0;
    }
    .line-input:focus {
      outline: none;
      border-bottom-width: 2px;
    }
    .record-table th,
    .record-table td {
      border: 1px solid #64748b;
      padding: 8px 10px;
      vertical-align: middle;
    }
    .record-table th {
      font-weight: 700;
      text-align: center;
      color: #0f172a;
      background: #f8fafc;
    }
    .record-table thead tr:first-child th {
      background: #e2e8f0;
      font-size: 1.05rem;
    }
    .record-table thead tr:nth-child(2) th {
      background: #f1f5f9;
    }
    .record-table tbody td {
      min-height: 44px;
      height: 44px;
      color: #0f172a;
    }
    .record-table tbody tr:nth-child(even) td {
      background: #fcfcfd;
    }
    .record-table tbody tr:hover td {
      background: #f8fafc;
    }
    .record-table tbody td:empty::after {
      content: "\00a0";
    }
    .record-table-shell {
      border: 1px solid #94a3b8;
      border-radius: 10px;
      overflow: hidden;
      background: #ffffff;
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
    }
    .record-table--edit .line-input {
      border: 0;
      border-bottom: 1px solid #94a3b8;
      border-radius: 0;
      padding: 2px 2px 3px;
      min-height: 28px;
      background: transparent;
      font-size: 0.96rem;
    }
    .record-table--edit .line-input:focus {
      border-bottom-color: #0f172a;
      outline: none;
      box-shadow: none;
    }
    .record-table--edit td {
      padding: 5px 8px;
    }
    .record-table--edit .line-input::placeholder {
      color: #9ca3af;
      opacity: 1;
    }
    .tiny {
      font-size: 11px;
      color: #4b5563;
    }
    .edit-label {
      display: block;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      color: #374151;
      margin-bottom: 4px;
    }
    .edit-input {
      width: 100%;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      padding: 10px 12px;
      background: #fff;
      color: #0f172a;
    }
    .edit-input:focus {
      outline: 2px solid #0ea5e9;
      outline-offset: 1px;
      border-color: #0ea5e9;
    }
    @media print {
      .no-print { display: none !important; }
      body { background: #fff; }
      .paper { border: 0; box-shadow: none; }
    }
  </style>
</head>
<body>
  @php
    $lastName = trim((string) ($employeeUser->last_name ?? ''));
    $firstName = trim((string) ($employeeUser->first_name ?? ''));
    $middleName = trim((string) ($employeeUser->middle_name ?? ''));

    $dateHiredSource = old('date_hired');
    if ($dateHiredSource === null) {
      $dateHiredSource = $employeeUser->applicant?->date_hired ?? $employeeUser->employee?->employement_date ?? null;
    }
    $formDateHired = $dateHiredSource ? \Illuminate\Support\Carbon::parse($dateHiredSource)->format('Y-m-d') : '';
    $displayDateHired = $formDateHired !== '' ? \Illuminate\Support\Carbon::parse($formDateHired)->format('m/d/Y') : '-';
    $displayCurrentDate = now()->format('m/d/Y');
    $birthdayValue = $employeeUser->employee?->birthday
      ? \Illuminate\Support\Carbon::parse($employeeUser->employee->birthday)->format('m/d/Y')
      : '-';
    $employeeId = trim((string) ($employeeUser->employee?->employee_id ?? '-'));
    $accountNumber = trim((string) ($employeeUser->employee?->account_number ?? '-'));

    $formPosition = old('position', $employeeUser->employee?->position ?? $employeeUser->position ?? $employeeUser->applicant?->work_position ?? '');
    $formDepartment = old('department', $employeeUser->employee?->department ?? $employeeUser->department ?? $employeeUser->applicant?->position?->department ?? '');
    $formSSS = old('SSS', $employeeUser->government?->SSS ?? '');
    $formTIN = old('TIN', $employeeUser->government?->TIN ?? '');
    $formPhilHealth = old('PhilHealth', $employeeUser->government?->PhilHealth ?? '');
    $formMID = old('MID', $employeeUser->government?->MID ?? '');
    $formRTN = old('RTN', $employeeUser->government?->RTN ?? '');

    $employmentDisplay = old('employment', $employeeUser->employee?->classification ?? $employeeUser->applicant?->position?->employment ?? '-');
    $salaryDisplay = trim((string) ($employeeUser->salary?->salary ?? '-'));

    $defaultServiceRow = [
      'from_date' => $formDateHired,
      'to_date' => now()->format('Y-m-d'),
      'designation' => $formPosition,
      'status' => $employmentDisplay !== '' ? $employmentDisplay : '-',
      'salary' => $salaryDisplay !== '' ? $salaryDisplay : '-',
      'office' => $formDepartment,
      'separation_date' => '',
      'separation_cause' => '',
      'remarks' => '',
    ];

    $rawStoredRows = old('service_rows', $employeeUser->employee?->service_record_rows ?? []);
    $serviceRows = collect(is_array($rawStoredRows) ? $rawStoredRows : [])
      ->map(function ($row) use ($defaultServiceRow) {
        $row = is_array($row) ? $row : [];
        return [
          'from_date' => trim((string) ($row['from_date'] ?? '')),
          'to_date' => trim((string) ($row['to_date'] ?? '')),
          'designation' => trim((string) ($row['designation'] ?? '')),
          'status' => trim((string) ($row['status'] ?? '')),
          'salary' => trim((string) ($row['salary'] ?? '')),
          'office' => trim((string) ($row['office'] ?? '')),
          'separation_date' => trim((string) ($row['separation_date'] ?? '')),
          'separation_cause' => trim((string) ($row['separation_cause'] ?? '')),
          'remarks' => trim((string) ($row['remarks'] ?? '')),
        ];
      })
      ->filter(function ($row) {
        foreach ($row as $value) {
          if ($value !== '') {
            return true;
          }
        }
        return false;
      })
      ->values();

    if ($serviceRows->isEmpty()) {
      $serviceRows = collect([$defaultServiceRow]);
    }

    $previewRows = $serviceRows->take(9)->values();
    while ($previewRows->count() < 9) {
      $previewRows->push([
        'from_date' => '',
        'to_date' => '',
        'designation' => '',
        'status' => '',
        'salary' => '',
        'office' => '',
        'separation_date' => '',
        'separation_cause' => '',
        'remarks' => '',
      ]);
    }

    $editRows = $serviceRows->take(9)->values();
    while ($editRows->count() < 9) {
      $editRows->push([
        'from_date' => '',
        'to_date' => '',
        'designation' => '',
        'status' => '',
        'salary' => '',
        'office' => '',
        'separation_date' => '',
        'separation_cause' => '',
        'remarks' => '',
      ]);
    }

    $formatServiceDate = static function ($value): string {
      $text = trim((string) ($value ?? ''));
      if ($text === '') {
        return '-';
      }

      try {
        return \Illuminate\Support\Carbon::parse($text)->format('m/d/Y');
      } catch (\Throwable $e) {
        return $text;
      }
    };
  @endphp

  <div class="max-w-6xl mx-auto px-4 py-6 md:py-8" x-data="{ mode: 'preview' }">
    <div class="mb-4 flex items-center justify-between gap-3 no-print">
      <a
        href="{{ route('admin.adminEmployee') }}"
        onclick="if (window.history.length > 1) { event.preventDefault(); window.history.back(); }"
        class="inline-flex items-center rounded border border-slate-400 bg-white px-3 py-1.5 text-sm text-slate-700 hover:bg-slate-50"
      >
        Back
      </a>
      <div class="inline-flex rounded-lg border border-slate-300 bg-white p-1">
        <button type="button" @click="mode='preview'" :class="mode === 'preview' ? 'bg-slate-900 text-white' : 'text-slate-700'" class="rounded px-3 py-1.5 text-sm">
          Preview
        </button>
        <button type="button" @click="mode='edit'" :class="mode === 'edit' ? 'bg-slate-900 text-white' : 'text-slate-700'" class="rounded px-3 py-1.5 text-sm">
          Edit
        </button>
      </div>
    </div>

    @if (session('success'))
      <div class="mb-4 rounded border border-emerald-300 bg-emerald-50 px-3 py-2 text-sm text-emerald-800 no-print">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-4 rounded border border-rose-300 bg-rose-50 px-3 py-2 text-sm text-rose-800 no-print">
        {{ $errors->first() }}
      </div>
    @endif

    <div x-show="mode==='preview'" class="paper rounded-xl p-5 md:p-7" style="display:none;">
      <div class="no-print mb-4 flex justify-end">
        <button type="button" onclick="window.print()" class="rounded border border-slate-300 bg-slate-50 px-3 py-1.5 text-sm hover:bg-slate-100">
          Print
        </button>
      </div>

      <div class="text-center">
        <h1 class="text-3xl font-extrabold tracking-wide text-emerald-900">NORTHEASTERN COLLEGE</h1>
        <p class="text-base font-semibold text-slate-700">Santiago City, Philippines</p>
        <p class="text-base font-semibold text-slate-700">Telephone No.: (078) 305-3226</p>
      </div>

      <hr class="my-4 border-slate-500">

      <div class="text-center">
        <h2 class="text-4xl font-bold tracking-wide text-slate-900">SERVICE RECORD</h2>
        <p class="italic text-slate-700">(To be accomplished by the Employer)</p>
      </div>

      <div class="mt-6 grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
        <div class="md:col-span-1 text-lg font-semibold">NAME:</div>
        <div class="md:col-span-3">
          <input type="text" class="line-input text-center font-semibold" value="{{ $lastName }}" readonly>
          <p class="text-center italic text-slate-600">(Last Name)</p>
        </div>
        <div class="md:col-span-4">
          <input type="text" class="line-input text-center font-semibold" value="{{ $firstName }}" readonly>
          <p class="text-center italic text-slate-600">(First Name)</p>
        </div>
        <div class="md:col-span-4">
          <input type="text" class="line-input text-center font-semibold" value="{{ $middleName }}" readonly>
          <p class="text-center italic text-slate-600">(Middle Name)</p>
        </div>
      </div>

      <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-3">
        <div class="grid grid-cols-3 items-center gap-2">
          <label class="col-span-1 font-semibold text-slate-700">Birth Date:</label>
          <input type="text" class="col-span-2 line-input" value="{{ $birthdayValue }}" readonly>
        </div>
        <div class="grid grid-cols-3 items-center gap-2">
          <label class="col-span-1 font-semibold text-slate-700">Phil Health #:</label>
          <input type="text" class="col-span-2 line-input" value="{{ $formPhilHealth !== '' ? $formPhilHealth : '-' }}" readonly>
        </div>
        <div class="grid grid-cols-3 items-center gap-2">
          <label class="col-span-1 font-semibold text-slate-700">Account #:</label>
          <input type="text" class="col-span-2 line-input" value="{{ $accountNumber }}" readonly>
        </div>
        <div class="grid grid-cols-3 items-center gap-2">
          <label class="col-span-1 font-semibold text-slate-700">ID NUMBER:</label>
          <input type="text" class="col-span-2 line-input" value="{{ $employeeId }}" readonly>
        </div>
        <div class="grid grid-cols-3 items-center gap-2">
          <label class="col-span-1 font-semibold text-slate-700">TIN #:</label>
          <input type="text" class="col-span-2 line-input" value="{{ $formTIN !== '' ? $formTIN : '-' }}" readonly>
        </div>
        <div class="hidden md:block"></div>
        <div class="grid grid-cols-3 items-center gap-2">
          <label class="col-span-1 font-semibold text-slate-700">SSS #:</label>
          <input type="text" class="col-span-2 line-input" value="{{ $formSSS !== '' ? $formSSS : '-' }}" readonly>
        </div>
        <div class="grid grid-cols-3 items-center gap-2">
          <label class="col-span-1 font-semibold text-slate-700">PAG-IBIG #:</label>
          <input type="text" class="col-span-2 line-input" value="{{ $formMID !== '' ? $formMID : '-' }}" readonly>
        </div>
        <div class="grid grid-cols-3 items-center gap-2">
          <label class="col-span-1 font-semibold text-slate-700">RTN #:</label>
          <input type="text" class="col-span-2 line-input" value="{{ $formRTN !== '' ? $formRTN : '-' }}" readonly>
        </div>
      </div>

      <p class="mt-6 text-center italic text-slate-700 leading-relaxed">
        This is to certify that the employee named herein above actually rendered services in this
        institution as shown by the service record below, each line of which is supported by appointment
        and other papers actually issued by this Office and approved by the authorities concerned.
      </p>

      <div class="record-table-shell mt-6 overflow-x-auto">
        <table class="record-table record-table--preview min-w-[980px] w-full text-sm">
          <thead>
            <tr>
              <th colspan="2">SERVICE</th>
              <th colspan="3">RECORD OF APPOINTMENT</th>
              <th>OFFICE</th>
              <th colspan="2">SEPARATION</th>
              <th>Remarks</th>
            </tr>
            <tr>
              <th colspan="2" class="tiny">(Inclusive Dates)</th>
              <th>Designation</th>
              <th>Status</th>
              <th>Salary</th>
              <th class="tiny">Station / Place of Assignment</th>
              <th colspan="2" class="tiny">(3)</th>
              <th class="tiny">(4)</th>
            </tr>
            <tr>
              <th>From</th>
              <th>To</th>
              <th></th>
              <th>(1)</th>
              <th>(2)</th>
              <th></th>
              <th>Date</th>
              <th>Cause</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($previewRows as $row)
              <tr>
                <td>{{ trim((string) ($row['from_date'] ?? '')) !== '' ? $formatServiceDate($row['from_date']) : '' }}</td>
                <td>{{ trim((string) ($row['to_date'] ?? '')) !== '' ? $formatServiceDate($row['to_date']) : '' }}</td>
                <td>{{ trim((string) ($row['designation'] ?? '')) !== '' ? $row['designation'] : '' }}</td>
                <td>{{ trim((string) ($row['status'] ?? '')) !== '' ? $row['status'] : '' }}</td>
                <td>{{ trim((string) ($row['salary'] ?? '')) !== '' ? $row['salary'] : '' }}</td>
                <td>{{ trim((string) ($row['office'] ?? '')) !== '' ? $row['office'] : '' }}</td>
                <td>{{ trim((string) ($row['separation_date'] ?? '')) !== '' ? $formatServiceDate($row['separation_date']) : '' }}</td>
                <td>{{ trim((string) ($row['separation_cause'] ?? '')) !== '' ? $row['separation_cause'] : '' }}</td>
                <td>{{ trim((string) ($row['remarks'] ?? '')) !== '' ? $row['remarks'] : '' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
        <div class="flex items-end gap-2">
          <label class="font-semibold text-slate-700 pb-1">Date:</label>
          <input type="text" class="line-input max-w-[180px]" value="{{ $displayCurrentDate }}" readonly>
        </div>
        <div class="text-center font-semibold text-slate-700">CERTIFIED CORRECT:</div>
      </div>
        <div class=" mr-36 justify-self-end text-right pt-8 mt-6">
          <div class=" w-[250px] border-b border-slate-700"></div>
          <div class="pt-1 text-[18px] text-slate-700 mr-4">Human Resources Director</div>
        </div>
    </div>

    <div x-show="mode==='edit'" class="paper rounded-xl p-5 md:p-7" style="display:none;">
      <h2 class="text-xl font-bold text-slate-900 mb-5">Edit Service Record</h2>

      <form method="POST" action="{{ route('admin.PersonalDetail.serviceRecordEdit.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="user_id" value="{{ $employeeUser->id }}">

        <div>
          <h3 class="text-sm font-bold uppercase tracking-wide text-slate-700 mb-3">Government IDs</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
              <label class="edit-label">SSS</label>
              <input type="text" name="SSS" value="{{ $formSSS }}" class="edit-input" placeholder="34-1234567-8" maxlength="11" inputmode="numeric"
                     oninput="this.value = this.value.replace(/\D/g, '').slice(0,10).replace(/(\d{2})(\d{0,7})(\d{0,1})/, function(_, a, b, c){ return a + (b ? '-' + b : '') + (c ? '-' + c : ''); });">
            </div>
            <div>
              <label class="edit-label">TIN</label>
              <input type="text" name="TIN" value="{{ $formTIN }}" class="edit-input" placeholder="123-456-789-000" maxlength="15" inputmode="numeric"
                     oninput="this.value = this.value.replace(/\D/g, '').slice(0,12).replace(/(\d{3})(\d{0,3})(\d{0,3})(\d{0,3})/, function(_, a, b, c, d){ return a + (b ? '-' + b : '') + (c ? '-' + c : '') + (d ? '-' + d : ''); });">
            </div>
            <div>
              <label class="edit-label">PhilHealth</label>
              <input type="text" name="PhilHealth" value="{{ $formPhilHealth }}" class="edit-input" placeholder="12-123456789-0" maxlength="14" inputmode="numeric"
                     oninput="this.value = this.value.replace(/\D/g, '').slice(0,12).replace(/(\d{2})(\d{0,9})(\d{0,1})/, function(_, a, b, c){ return a + (b ? '-' + b : '') + (c ? '-' + c : ''); });">
            </div>
            <div>
              <label class="edit-label">PAG-IBIG MID</label>
              <input type="text" name="MID" value="{{ $formMID }}" class="edit-input" placeholder="1234-5678-9012" maxlength="14" inputmode="numeric"
                     oninput="this.value = this.value.replace(/\D/g, '').slice(0,12).replace(/(\d{4})(\d{0,4})(\d{0,4})/, function(_, a, b, c){ return a + (b ? '-' + b : '') + (c ? '-' + c : ''); });">
            </div>
            <div>
              <label class="edit-label">PAG-IBIG RTN</label>
              <input type="text" name="RTN" value="{{ $formRTN }}" class="edit-input" placeholder="1234-5678-9012" maxlength="14" inputmode="numeric"
                     oninput="this.value = this.value.replace(/\D/g, '').slice(0,12).replace(/(\d{4})(\d{0,4})(\d{0,4})/, function(_, a, b, c){ return a + (b ? '-' + b : '') + (c ? '-' + c : ''); });">
            </div>
          </div>
        </div>

        <div>
          <h3 class="text-sm font-bold uppercase tracking-wide text-slate-700 mb-3">Service Record of Appointment</h3>
          <div class="record-table-shell overflow-x-auto">
            <table class="record-table record-table--edit min-w-[980px] w-full text-sm">
              <thead>
                <tr>
                  <th colspan="2">SERVICE</th>
                  <th colspan="3">RECORD OF APPOINTMENT</th>
                  <th>OFFICE</th>
                  <th colspan="2">SEPARATION</th>
                  <th>Remarks</th>
                </tr>
                <tr>
                  <th colspan="2" class="tiny">(Inclusive Dates)</th>
                  <th>Designation</th>
                  <th>Status</th>
                  <th>Salary</th>
                  <th class="tiny">Station / Place of Assignment</th>
                  <th colspan="2" class="tiny">(3)</th>
                  <th class="tiny">(4)</th>
                </tr>
                <tr>
                  <th>From</th>
                  <th>To</th>
                  <th></th>
                  <th>(1)</th>
                  <th>(2)</th>
                  <th></th>
                  <th>Date</th>
                  <th>Cause</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($editRows as $idx => $row)
                  <tr>
                    <td><input type="date" name="service_rows[{{ $idx }}][from_date]" value="{{ $row['from_date'] ?? '' }}" class="line-input"></td>
                    <td><input type="date" name="service_rows[{{ $idx }}][to_date]" value="{{ $row['to_date'] ?? '' }}" class="line-input"></td>
                    <td><input type="text" name="service_rows[{{ $idx }}][designation]" value="{{ $row['designation'] ?? '' }}" class="line-input" placeholder="Designation"></td>
                    <td><input type="text" name="service_rows[{{ $idx }}][status]" value="{{ $row['status'] ?? '' }}" class="line-input" placeholder="Status"></td>
                    <td><input type="text" name="service_rows[{{ $idx }}][salary]" value="{{ $row['salary'] ?? '' }}" class="line-input" placeholder="Salary"></td>
                    <td><input type="text" name="service_rows[{{ $idx }}][office]" value="{{ $row['office'] ?? '' }}" class="line-input" placeholder="Station / Place of Assignment"></td>
                    <td><input type="date" name="service_rows[{{ $idx }}][separation_date]" value="{{ $row['separation_date'] ?? '' }}" class="line-input"></td>
                    <td><input type="text" name="service_rows[{{ $idx }}][separation_cause]" value="{{ $row['separation_cause'] ?? '' }}" class="line-input" placeholder="Cause"></td>
                    <td><input type="text" name="service_rows[{{ $idx }}][remarks]" value="{{ $row['remarks'] ?? '' }}" class="line-input" placeholder="Remarks"></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="flex justify-end gap-2">
          <button type="button" @click="mode='preview'" class="rounded border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
            Back to Preview
          </button>
          <button type="submit" class="rounded border border-slate-700 bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Save Record
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
