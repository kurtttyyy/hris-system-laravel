@php
    $employeeName = trim(implode(' ', array_filter([
        trim((string) ($employee->first_name ?? '')),
        trim((string) ($employee->middle_name ?? '')),
        trim((string) ($employee->last_name ?? '')),
    ])));
@endphp

<h2>Your Employee Account Is Now Inactive</h2>

<p>Hello{{ $employeeName !== '' ? ' '.$employeeName : '' }},</p>

<p>Your employee account status has been updated to:</p>

<strong>{{ $employee->account_status }}</strong>

<p>You will no longer be able to sign in to the employee portal while your account is inactive.</p>

<p>If you believe this is incorrect, please contact HR or the system administrator.</p>

<p>Thank you.</p>
