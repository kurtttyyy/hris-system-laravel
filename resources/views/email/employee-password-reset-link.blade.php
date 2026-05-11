@php
    $employeeName = trim(implode(' ', array_filter([
        trim((string) ($employee->first_name ?? '')),
        trim((string) ($employee->middle_name ?? '')),
        trim((string) ($employee->last_name ?? '')),
    ])));
@endphp

<h2>Password Change Request</h2>

<p>Hello{{ $employeeName !== '' ? ' '.$employeeName : '' }},</p>

<p>We received a request to change the password for your HRIS employee account.</p>

<p>
    <a href="{{ $resetUrl }}">Change Password</a>
</p>

<p>This link will expire on {{ $expiresAt->format('F j, Y g:i A') }}.</p>

<p>If you did not request this password change, you can ignore this email.</p>

<p>Thank you.</p>
