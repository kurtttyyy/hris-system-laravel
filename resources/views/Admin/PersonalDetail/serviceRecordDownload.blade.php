<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Service Record</title>
  <style>
    @page {
      size: A4 portrait;
      margin: 0.55in;
    }
    body {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
      color: #0f172a;
      font-size: 9px;
      line-height: 1.25;
    }
    .page {
      width: 100%;
    }
    .header {
      text-align: center;
      margin-bottom: 6px;
      min-height: 0;
    }
    .header img {
      width: 560px;
      max-width: 100%;
      height: auto;
      display: inline-block;
    }
    .divider {
      border-top: 1px solid #64748b;
      margin: 8px 0 10px;
    }
    .title {
      text-align: center;
      margin-top: 2px;
    }
    .title h1 {
      margin: 0;
      font-size: 22px;
      line-height: 1;
      letter-spacing: 1px;
      color: #0b234f;
      font-weight: 800;
    }
    .title p {
      margin: 2px 0 0;
      font-size: 9px;
      font-style: italic;
    }
    .name-grid {
      margin-top: 10px;
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }
    .name-grid td {
      padding: 0 8px 0 0;
      vertical-align: bottom;
    }
    .line {
      display: block;
      border-bottom: 1px solid #334155;
      min-height: 0;
      padding: 0 2px 1px;
      line-height: 1.1;
      white-space: nowrap;
    }
    .label {
      font-size: 12px;
      font-weight: 700;
      width: 80px;
      white-space: nowrap;
      padding-right: 8px;
    }
    .line-value {
      text-align: center;
      font-size: 9px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.2px;
    }
    .line-note {
      text-align: center;
      font-size: 8px;
      font-style: italic;
      color: #334155;
      margin-top: 2px;
    }
    .meta-grid {
      margin-top: 10px;
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }
    .meta-grid td {
      padding: 2px 4px 2px 0;
      vertical-align: bottom;
    }
    .meta-cell {
      width: 33.33%;
      padding-right: 10px;
    }
    .meta-pair {
      width: 100%;
      border-collapse: collapse;
      table-layout: auto;
    }
    .meta-pair td {
      vertical-align: bottom;
    }
    .meta-label {
      font-size: 10px;
      font-weight: 700;
      white-space: nowrap;
      width: 90px;
      padding-right: 8px;
    }
    .cert {
      text-align: center;
      font-size: 9px;
      font-style: italic;
      margin: 12px 10px 12px;
      color: #1e293b;
    }
    .record-wrap {
      border: 0;
      border-radius: 0;
      overflow: hidden;
      margin-top: 8px;
    }
    .record-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
      font-size: 9px;
    }
    .record-table th,
    .record-table td {
      border: 1px solid #64748b;
      padding: 3px 3px;
      vertical-align: middle;
    }
    .record-table thead th {
      background: #eef2f7;
      font-weight: 700;
      text-align: center;
    }
    .record-table thead tr:nth-child(1) th {
      font-size: 9px;
    }
    .record-table thead tr:nth-child(2) th,
    .record-table thead tr:nth-child(3) th {
      font-size: 8px;
    }
    .record-table tbody td {
      height: 20px;
      font-size: 8px;
    }
    .small {
      font-size: 7px;
      font-weight: 600;
    }
    .sign-row {
      margin-top: 16px;
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }
    .sign-row td {
      vertical-align: top;
    }
    .date-line {
      display: inline-block;
      min-width: 120px;
      border-bottom: 1px solid #334155;
      padding: 1px 3px;
    }
    .certified {
      text-align: center;
      font-size: 9px;
      padding-top: 2px;
    }
    .signature {
      margin-top: 22px;
      text-align: center;
    }
    .signature .sig-line {
      width: 170px;
      border-bottom: 1px solid #334155;
      margin: 0 auto 3px;
      height: 14px;
    }
    .signature .sig-title {
      font-size: 9px;
    }
  </style>
</head>
<body>
  @php
    $lastName = trim((string) ($employeeUser->last_name ?? ''));
    $firstName = trim((string) ($employeeUser->first_name ?? ''));
    $middleName = trim((string) ($employeeUser->middle_name ?? ''));

    $birthdayValue = $employeeUser->employee?->birthday
      ? \Illuminate\Support\Carbon::parse($employeeUser->employee->birthday)->format('m/d/Y')
      : '-';
    $employeeId = trim((string) ($employeeUser->employee?->employee_id ?? '-'));
    $accountNumber = trim((string) ($employeeUser->employee?->account_number ?? '-'));
    $displayCurrentDate = now()->format('m/d/Y');

    $formSSS = trim((string) ($employeeUser->government?->SSS ?? ''));
    $formTIN = trim((string) ($employeeUser->government?->TIN ?? ''));
    $formPhilHealth = trim((string) ($employeeUser->government?->PhilHealth ?? ''));
    $formMID = trim((string) ($employeeUser->government?->MID ?? ''));

    $normalizeServiceStatus = static function ($value): string {
      $text = trim((string) ($value ?? ''));
      if ($text === '') {
        return '';
      }
      $normalized = strtolower(preg_replace('/[^a-z0-9]+/i', ' ', $text));
      $normalized = trim(preg_replace('/\s+/', ' ', $normalized));
      if (str_contains($normalized, 'full')) {
        return 'Full-Time';
      }
      if (str_contains($normalized, 'part')) {
        return 'Part-Time';
      }
      if (str_contains($normalized, 'probationary') || str_contains($normalized, 'permanent') || str_contains($normalized, 'regular')) {
        return 'Full-Time';
      }
      return $text;
    };

    $formDateHired = $employeeUser->applicant?->date_hired
      ?? $employeeUser->employee?->employement_date
      ?? null;
    $formDateHired = $formDateHired ? \Illuminate\Support\Carbon::parse($formDateHired)->format('Y-m-d') : '';

    $employmentDisplay = $normalizeServiceStatus(
      $employeeUser->employee?->classification
      ?? $employeeUser->applicant?->position?->employment
      ?? '-'
    );
    $salaryDisplay = trim((string) ($employeeUser->salary?->salary ?? '-'));
    $formPosition = trim((string) ($employeeUser->employee?->position ?? $employeeUser->position ?? $employeeUser->applicant?->work_position ?? ''));
    $formDepartment = trim((string) ($employeeUser->employee?->department ?? $employeeUser->department ?? $employeeUser->applicant?->position?->department ?? ''));

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

    $storedRows = $employeeUser->employee?->service_record_rows ?? [];
    $previewRows = collect(is_array($storedRows) ? $storedRows : [])
      ->map(function ($row) use ($normalizeServiceStatus) {
        $row = is_array($row) ? $row : [];
        return [
          'from_date' => trim((string) ($row['from_date'] ?? '')),
          'to_date' => trim((string) ($row['to_date'] ?? '')),
          'designation' => trim((string) ($row['designation'] ?? '')),
          'status' => $normalizeServiceStatus($row['status'] ?? ''),
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

    if ($previewRows->isEmpty()) {
      $previewRows = collect([$defaultServiceRow]);
    }

    $previewRows = $previewRows->take(9)->values();
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

    $formatServiceDate = static function ($value): string {
      $text = trim((string) ($value ?? ''));
      if ($text === '') {
        return '';
      }
      try {
        return \Illuminate\Support\Carbon::parse($text)->format('m/d/Y');
      } catch (\Throwable $e) {
        return $text;
      }
    };

    $logoBannerSrc = asset('images/logo.png');
    try {
      $logoBannerPath = public_path('images/logo.png');
      if (is_file($logoBannerPath)) {
        $logoBannerSrc = 'data:image/png;base64,'.base64_encode((string) file_get_contents($logoBannerPath));
      }
    } catch (\Throwable $e) {
      $logoBannerSrc = asset('images/logo.png');
    }

  @endphp

  <div class="page">
    <div class="header">
      <img src="{{ $logoBannerSrc }}" alt="Northeastern College">
    </div>

    <div class="divider"></div>

    <div class="title">
      <h1>SERVICE RECORD</h1>
      <p>(To be accomplished by the Employer)</p>
    </div>

    <table class="name-grid">
      <tr>
        <td class="label">NAME:</td>
        <td>
          <div class="line line-value" style="text-align:center;">{{ $lastName !== '' ? strtoupper($lastName) : '-' }}</div>
        </td>
        <td>
          <div class="line line-value" style="text-align:center;">{{ $firstName !== '' ? strtoupper($firstName) : '-' }}</div>
        </td>
        <td>
          <div class="line line-value" style="text-align:center;">{{ $middleName !== '' ? strtoupper($middleName) : '-' }}</div>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <div class="line-note">(Last Name)</div>
        </td>
        <td>
          <div class="line-note">(First Name)</div>
        </td>
        <td>
          <div class="line-note">(Middle Name)</div>
        </td>
      </tr>
    </table>

    <table class="meta-grid" cellspacing="0" cellpadding="0" style="width:100%; border-collapse:collapse; table-layout:fixed; mso-table-lspace:0pt; mso-table-rspace:0pt;">
      <tr>
        <td class="meta-cell">
          <table class="meta-pair" cellspacing="0" cellpadding="0">
            <tr>
              <td class="meta-label">Birth Date:</td>
              <td><div class="line">{{ $birthdayValue }}</div></td>
            </tr>
          </table>
        </td>
        <td class="meta-cell">
          <table class="meta-pair" cellspacing="0" cellpadding="0">
            <tr>
              <td class="meta-label">Phil Health #:</td>
              <td><div class="line">{{ $formPhilHealth !== '' ? $formPhilHealth : '-' }}</div></td>
            </tr>
          </table>
        </td>
        <td class="meta-cell">
          <table class="meta-pair" cellspacing="0" cellpadding="0">
            <tr>
              <td class="meta-label">Account #:</td>
              <td><div class="line">{{ $accountNumber !== '' ? $accountNumber : '-' }}</div></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="meta-cell">
          <table class="meta-pair" cellspacing="0" cellpadding="0">
            <tr>
              <td class="meta-label">ID NUMBER:</td>
              <td><div class="line">{{ $employeeId !== '' ? $employeeId : '-' }}</div></td>
            </tr>
          </table>
        </td>
        <td class="meta-cell">
          <table class="meta-pair" cellspacing="0" cellpadding="0">
            <tr>
              <td class="meta-label">TIN #:</td>
              <td><div class="line">{{ $formTIN !== '' ? $formTIN : '-' }}</div></td>
            </tr>
          </table>
        </td>
        <td class="meta-cell"></td>
      </tr>
      <tr>
        <td class="meta-cell">
          <table class="meta-pair" cellspacing="0" cellpadding="0">
            <tr>
              <td class="meta-label">SSS #:</td>
              <td><div class="line">{{ $formSSS !== '' ? $formSSS : '-' }}</div></td>
            </tr>
          </table>
        </td>
        <td class="meta-cell">
          <table class="meta-pair" cellspacing="0" cellpadding="0">
            <tr>
              <td class="meta-label">PAG-IBIG #:</td>
              <td><div class="line">{{ $formMID !== '' ? $formMID : '-' }}</div></td>
            </tr>
          </table>
        </td>
        <td class="meta-cell"></td>
      </tr>
    </table>

    <p class="cert">
      This is to certify that the employee named herein above actually rendered services in this institution as shown by the service record below, each line of which is supported by appointment and other papers actually issued by this Office and approved by the authorities concerned.
    </p>

    <div class="record-wrap">
      <table class="record-table">
        <colgroup>
          <col style="width: 11.5%;">
          <col style="width: 11.5%;">
          <col style="width: 12%;">
          <col style="width: 9.5%;">
          <col style="width: 7.5%;">
          <col style="width: 20.5%;">
          <col style="width: 7%;">
          <col style="width: 8%;">
          <col style="width: 12.5%;">
        </colgroup>
        <thead>
          <tr>
            <th colspan="2">SERVICE</th>
            <th colspan="3">RECORD OF APPOINTMENT</th>
            <th>OFFICE</th>
            <th colspan="2">SEPARATION</th>
            <th>Remarks</th>
          </tr>
          <tr>
            <th colspan="2" class="small">(Inclusive Dates)</th>
            <th>Designation</th>
            <th>Status</th>
            <th>Salary</th>
            <th class="small">Station / Place of Assignment</th>
            <th colspan="2" class="small">(3)</th>
            <th class="small">(4)</th>
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

    <table class="sign-row">
      <tr>
        <td style="width: 43%;">
          <div style="font-size: 13px;">
            Date:
            <span class="date-line">{{ $displayCurrentDate }}</span>
          </div>
        </td>
        <td style="width: 24%;" class="certified">CERTIFIED CORRECT:</td>
        <td style="width: 33%;">
          <div class="signature">
            <div class="sig-line"></div>
            <div class="sig-title">Human Resources Director</div>
          </div>
        </td>
      </tr>
    </table>
  </div>
</body>
</html>
