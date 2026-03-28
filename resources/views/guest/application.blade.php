@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

@include('layouts.header')  {{-- UNIVERSAL HEADER --}}


<div class="header-divider"></div>
<main class="container my-5 animated-card1 delay-5">
    <div class="container my-5 shadow-sm p-4 bg-white rounded">
    <h2 class="fw-bold mb-1">Your Applications</h2>
    <p class="text-muted mb-4">Track the status of your job applications</p>

    @foreach($applicants as $applicant)
        <div class="card shadow-sm mb-4 animated-card delay-5">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <h5 class="mb-1">{{ $applicant->position->title }}</h5>
                            @if((bool) ($applicant->is_email_history_match ?? false))
                                <span class="badge rounded-pill px-3 py-2" style="background-color: rgba(108, 117, 125, 0.12); color: #495057; border: 1px solid rgba(108, 117, 125, 0.35);">
                                    Previous Application
                                </span>
                            @endif
                        </div>
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
                            'Demo Teaching' => 'background-color: rgba(13, 110, 253, 0.2); color: #0d6efd; border: 2px solid #0d6efd;',
                        ];
                        $defaultColor = 'background-color: rgba(13, 110, 253, 0.2); color: #0d6efd; border: 2px solid #0d6efd;';
                        $badgeStyle = $statusColors[$applicant->application_status] ?? $defaultColor;
                    @endphp

                    <span class="badge rounded-pill px-3 py-2" style="{{ $badgeStyle }}">
                        {{ $applicant->application_status }}
                    </span>
                </div>

                {{-- Progress --}}
                <div
                    class="stepper"
                    data-status="{{ $applicant->application_status }}"
                    data-job-type="{{ strtolower((string) optional($applicant->position)->job_type) }}"
                ></div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-success next-step-text"></span>
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-success application-view-toggle"
                        data-target="application-details-{{ $applicant->id }}"
                    >
                        View Submitted Application
                    </button>
                </div>

                {{-- Rejection Message --}}
                @if($applicant->application_status === 'Rejected')
                    <div class="alert alert-danger mt-3">
                        <p>Thank you very much for your interest in the <strong>{{ $applicant->position->title }}</strong> position and for the time you invested in the application process.</p>

                        <p>After careful consideration, we regret to inform you that we will not be moving forward with your application at this time. While your qualifications are impressive, we have chosen to proceed with candidates whose experience more closely matches the requirements of this role.</p>

                        <p>We truly appreciate your interest in joining our team and encourage you to apply for future openings that align with your skills and experience.</p>
                    </div>
                @endif

                <div id="application-details-{{ $applicant->id }}" class="application-details-panel mt-4" style="display: none;">
                    <div class="rounded-4 border bg-light-subtle p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 text-success">Personal Information</h6>
                                <div class="small text-muted mb-2">Full Name</div>
                                <div class="mb-3">{{ $applicant->first_name }} {{ $applicant->last_name }}</div>
                                <div class="small text-muted mb-2">Email</div>
                                <div class="mb-3">{{ $applicant->email }}</div>
                                <div class="small text-muted mb-2">Phone</div>
                                <div class="mb-3">{{ $applicant->phone ?: 'N/A' }}</div>
                                <div class="small text-muted mb-2">Address</div>
                                <div>{{ $applicant->address ?: 'N/A' }}</div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 text-success">Application Information</h6>
                                <div class="small text-muted mb-2">Position</div>
                                <div class="mb-3">{{ optional($applicant->position)->title ?: 'N/A' }}</div>
                                <div class="small text-muted mb-2">Department</div>
                                <div class="mb-3">{{ optional($applicant->position)->department ?: 'N/A' }}</div>
                                <div class="small text-muted mb-2">Experience Years</div>
                                <div class="mb-3">{{ $applicant->experience_years ?: 'N/A' }}</div>
                                <div class="small text-muted mb-2">Skills / Expertise</div>
                                <div>{{ $applicant->skills_n_expertise ?: 'N/A' }}</div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 text-success">Education</h6>
                                @php
                                    $degreeRows = collect($applicant->degrees ?? []);
                                @endphp
                                @if($degreeRows->isNotEmpty())
                                    <div class="d-flex flex-column gap-3">
                                        @foreach($degreeRows as $degree)
                                            <div class="rounded-3 border bg-white p-3">
                                                <div class="fw-semibold text-capitalize">{{ $degree->degree_level }}</div>
                                                <div>{{ $degree->degree_name ?: 'N/A' }}</div>
                                                <div class="small text-muted">{{ $degree->school_name ?: 'School N/A' }}{{ $degree->year_finished ? ' • '.$degree->year_finished : '' }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="rounded-3 border bg-white p-3">
                                        <div><strong>Bachelor:</strong> {{ $applicant->bachelor_degree ?: 'N/A' }}</div>
                                        <div><strong>Master:</strong> {{ $applicant->master_degree ?: 'N/A' }}</div>
                                        <div><strong>Doctorate:</strong> {{ $applicant->doctoral_degree ?: 'N/A' }}</div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 text-success">Experience</h6>
                                <div class="small text-muted mb-2">Previous Position</div>
                                <div class="mb-3">{{ $applicant->work_position ?: 'N/A' }}</div>
                                <div class="small text-muted mb-2">Employer</div>
                                <div class="mb-3">{{ $applicant->work_employer ?: 'N/A' }}</div>
                                <div class="small text-muted mb-2">Work Location</div>
                                <div class="mb-3">{{ $applicant->work_location ?: 'N/A' }}</div>
                                <div class="small text-muted mb-2">Work Duration</div>
                                <div>{{ $applicant->work_duration ?: 'N/A' }}</div>
                            </div>

                            <div class="col-12">
                                <h6 class="fw-bold mb-3 text-success">Uploaded Documents</h6>
                                @if(collect($applicant->documents ?? [])->isNotEmpty())
                                    <div class="row g-3">
                                        @foreach($applicant->documents as $document)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="rounded-3 border bg-white p-3 h-100">
                                                    <div class="fw-semibold">{{ $document->type ?: 'Document' }}</div>
                                                    <div class="small text-muted text-break">{{ $document->filename }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="rounded-3 border bg-white p-3">No uploaded documents found.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {

    const getSteps = (jobType) => {
        const normalized = (jobType || '').toLowerCase().trim();
        const isTeaching = normalized.includes('teaching') && !normalized.includes('non');

        if (isTeaching) {
            return [
                'pending',
                'Under Review',
                'Initial Interview',
                'Final Interview',
                'Demo Teaching',
                'Passing Document',
            ];
        }

        return [
            'pending',
            'Under Review',
            'Initial Interview',
            'Final Interview',
            'Passing Document',
        ];
    };

    document.querySelectorAll('.stepper').forEach(stepper => {
        const status = stepper.dataset.status;
        const steps = getSteps(stepper.dataset.jobType);
        stepper.innerHTML = steps.map((_, index) => `
            <div class="step">
                <div class="circle">${index + 1}</div>
                ${index < steps.length - 1 ? '<div class="line"></div>' : ''}
            </div>
        `).join('');

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
            else if (status === 'Hired' || status === 'Completed') {
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
        } else if (status === 'Completed') {
            nextText.innerText = 'Process Completed';
        } else if (status === 'Rejected') {
            nextText.innerText = 'Application Rejected';
        } else if (currentStep === steps.length - 1) {
            nextText.innerText = 'Next: Completed';
        } else {
            nextText.innerText =
                currentStep >= 0 && currentStep < steps.length - 1
                    ? `Next: ${steps[currentStep + 1]}`
                    : 'In Progress';
        }
    });

    document.querySelectorAll('.application-view-toggle').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const panel = document.getElementById(targetId);
            if (!panel) return;

            const isOpen = panel.style.display !== 'none';
            panel.style.display = isOpen ? 'none' : 'block';
            this.innerText = isOpen ? 'View Submitted Application' : 'Hide Submitted Application';
        });
    });

});

</script>
