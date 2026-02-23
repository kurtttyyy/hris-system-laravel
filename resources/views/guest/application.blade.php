@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

@include('layouts.header')  {{-- UNIVERSAL HEADER --}}


<div class="header-divider"></div>
@foreach($applicants as $applicant)
<main class="container my-5 animated-card1 delay-5">
    <div class="container my-5 shadow-sm p-4 bg-white rounded">
    <h2 class="fw-bold mb-1">Your Applications</h2>
    <p class="text-muted mb-4">Track the status of your job applications</p>

    {{-- Application Card 1 --}}
    <div class="card shadow-sm mb-4 animated-card delay-5">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">{{ $applicant->position->title }}</h5>
                    <small class="text-muted">Submitted: {{ $applicant->created_at->format('m/d/y') }}</small>
                </div>

                {{-- Status Badge --}}  
                @php
                    $statusColors = [
                        'pending' => 'background-color: rgba(255, 193, 7, 0.3); color: #ffa807; border: 2px solid #ffc107;',
                        'Hired' => 'background-color: rgba(25, 135, 84, 0.2); color: #198754; border: 2px solid #198754;',
                        'Completed' => 'background-color: rgba(25, 135, 84, 0.2); color: #198754; border: 2px solid #198754;',
                        'Rejected' => 'background-color: rgba(220, 53, 69, 0.2); color: #dc3545; border: 2px solid #dc3545;',
                        'Under Review' => 'background-color: rgba(13, 110, 253, 0.2); color: #0d6efd; border: 2px solid #0d6efd;',
                    ];
                    $defaultColor = 'background-color: rgba(13, 110, 253, 0.2); color: #0d6efd; border: 2px solid #0d6efd;';
                    $badgeStyle = $statusColors[$applicant->application_status] ?? $defaultColor;
                @endphp

            <span class="badge rounded-pill px-3 py-2" style="{{ $badgeStyle }}">
                {{ $applicant->application_status }}
            </span>

            

            </div>

            {{-- Progress --}}
            <div class="stepper" data-status="{{ $applicant->application_status }}">
                <div class="step">
                    <div class="circle">1</div>
                    <div class="line"></div>
                </div>
                <div class="step">
                    <div class="circle">2</div>
                    <div class="line"></div>
                </div>
                <div class="step">
                    <div class="circle">3</div>
                    <div class="line"></div>
                </div>
                <div class="step">
                    <div class="circle">4</div>
                    <div class="line"></div>
                </div>
                <div class="step">
                    <div class="circle">5</div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <span class="text-success next-step-text"></span>
            </div>

            {{-- Rejection Message --}}
                @if($applicant->application_status === 'Rejected')
                    <div class="alert alert-danger mt-3">
                        <p>Thank you very much for your interest in the <strong>{{ $applicant->position->title }}</strong> position and for the time you invested in the application process.</p>

                        <p>After careful consideration, we regret to inform you that we will not be moving forward with your application at this time. While your qualifications are impressive, we have chosen to proceed with candidates whose experience more closely matches the requirements of this role.</p>

                        <p>We truly appreciate your interest in joining our team and encourage you to apply for future openings that align with your skills and experience.</p>
                    </div>
                @endif

        </div>
    </div>

    </div>

        <div class="container my-5 shadow-sm p-4 rounded application-tips">
            <div class="d-flex align-items-start">
                <!-- Icon -->
                <div class="me-3">
                    <i class="bi bi-lightbulb-fill tips-icon"></i>
                </div>

                <!-- Content -->
                <div>
                    <h6 class="fw-bold mb-2">Application Tips</h6>
                    <ul class="list-unstyled mb-0">
                        <li>• Check your email regularly for updates from our HR team</li>
                        <li>• You will be notified at each stage of the application process</li>
                        <li>• Interview invitations will be sent via email at least 3 days notice</li>
                    </ul>
                </div>
            </div>
        </div>

</main>
@endforeach
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {

    const steps = [
        'pending',
        'Under Review',
        'Initial Interview',
        'Final Interview',
        'Passing Document',
        'Completed',
        'Hired',
    ];

    document.querySelectorAll('.stepper').forEach(stepper => {
        const status = stepper.dataset.status;
        const stepElements = stepper.querySelectorAll('.step');
        const nextText = stepper
            .closest('.card-body')
            .querySelector('.next-step-text');

        const currentStep = steps.indexOf(status);

        stepElements.forEach((step, index) => {
            const circle = step.querySelector('.circle');
            const line = step.querySelector('.line');

            // Reset styles
            step.classList.remove('completed', 'rejected');
            circle.style.backgroundColor = '';
            circle.style.color = '';
            if(line) line.style.backgroundColor = '';

            if (status === 'Rejected') {
                // All circles red
                step.classList.add('rejected');
                circle.innerText = index + 1;
                circle.style.backgroundColor = '#dc3545'; // red
                circle.style.color = '#fff';
                
                // All lines red
                if(line) line.style.backgroundColor = '#dc3545';
            } 
            else if (status === 'Hired') {
                step.classList.add('completed');
                circle.innerText = '✓';
                circle.style.backgroundColor = '#198754'; // green
                circle.style.color = '#fff';
                if(line) line.style.backgroundColor = '#198754';
            } 
            else {
                // Other statuses: normal stepper logic
                if (index < currentStep) {
                    step.classList.add('completed');
                    circle.innerText = '✓';
                    circle.style.backgroundColor = '#198754';
                    circle.style.color = '#fff';
                    if(line) line.style.backgroundColor = '#198754';
                } else if (index === currentStep) {
                    step.classList.add('completed');
                    circle.innerText = index + 1;
                } else {
                    circle.innerText = index + 1;
                }
            }
        });

        // Text below stepper
        if (status === 'Hired') {
            nextText.innerText = 'Hired';
        } else if (status === 'Rejected') {
            nextText.innerText = 'Application Rejected';
        } else {
            nextText.innerText =
                currentStep < steps.length - 1
                    ? `Next: ${steps[currentStep + 1]}`
                    : 'Process Completed';
        }
    });

});

</script>
