@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .site-footer {
        background:
            radial-gradient(circle at top left, rgba(21, 115, 71, 0.12), transparent 24%),
            linear-gradient(180deg, #0f1113 0%, #0b0c0d 100%);
        color: rgba(255, 255, 255, 0.82);
        margin-top: 4rem;
    }

    .site-footer a {
        color: rgba(255, 255, 255, 0.82);
        text-decoration: none;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .site-footer a:hover {
        color: #ffffff;
        transform: translateX(2px);
    }

    .footer-shell {
        max-width: 1240px;
        margin: 0 auto;
        padding: 4rem 1.5rem 2rem;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1.3fr 1fr 1fr;
        gap: 2rem;
    }

    .footer-brand {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .footer-brand-mark {
        width: 3.75rem;
        height: 3.75rem;
        border-radius: 50%;
        object-fit: cover;
        background: #fff;
        padding: 0.35rem;
        box-shadow: 0 14px 26px rgba(0, 0, 0, 0.28);
    }

    .footer-brand h3,
    .footer-title {
        margin: 0 0 1.25rem;
        color: #fff;
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .footer-title {
        font-size: 1.15rem;
    }

    .footer-info-list,
    .footer-link-list {
        list-style: none;
        padding: 0;
        margin: 1.4rem 0 0;
    }

    .footer-info-list li,
    .footer-link-list li {
        margin-bottom: 0.95rem;
    }

    .footer-contact {
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
    }

    .footer-icon {
        width: 1.2rem;
        height: 1.2rem;
        flex: 0 0 1.2rem;
        color: rgba(255, 255, 255, 0.85);
        margin-top: 0.15rem;
    }

    .footer-feature-text {
        margin: 0;
        max-width: 18rem;
        color: rgba(255, 255, 255, 0.78);
        line-height: 1.9;
    }

    .newsletter-copy {
        margin: 0 0 1rem;
        color: rgba(255, 255, 255, 0.72);
        line-height: 1.7;
    }

    .newsletter-form {
        display: grid;
        gap: 0.8rem;
    }

    .newsletter-input-wrap {
        position: relative;
    }

    .newsletter-input {
        width: 100%;
        height: 3rem;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 0.95rem;
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        padding: 0.8rem 3rem 0.8rem 1rem;
        outline: none;
    }

    .newsletter-input::placeholder {
        color: rgba(255, 255, 255, 0.45);
    }

    .newsletter-input:focus {
        border-color: rgba(52, 211, 153, 0.55);
        box-shadow: 0 0 0 0.18rem rgba(52, 211, 153, 0.15);
    }

    .newsletter-input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.1rem;
        height: 1.1rem;
        color: rgba(255, 255, 255, 0.55);
    }

    .newsletter-btn {
        border: none;
        border-radius: 0.95rem;
        min-height: 3rem;
        background: linear-gradient(135deg, #1b1d1f 0%, #232628 100%);
        color: #fff;
        font-weight: 700;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.05);
    }

    .newsletter-btn:hover {
        background: linear-gradient(135deg, #157347 0%, #1ea55d 100%);
    }

    .newsletter-note {
        margin: 0.35rem 0 0;
        color: rgba(191, 219, 254, 0.8);
        font-size: 0.9rem;
    }

    .footer-bottom {
        margin-top: 3rem;
        padding-top: 1.4rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .footer-bottom p {
        margin: 0;
        color: rgba(255, 255, 255, 0.68);
    }

    .footer-bottom-links {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    @media (max-width: 991.98px) {
        .footer-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .footer-shell {
            padding: 3rem 1rem 1.5rem;
        }

        .footer-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .footer-bottom {
            flex-direction: column;
            align-items: flex-start;
        }

        .footer-bottom-links {
            gap: 1rem;
        }
    }
</style>

@include('layouts.header')  {{-- UNIVERSAL HEADER --}}


<div class="header-divider"></div>
<main class="container my-5 animated-card1 delay-5">
    <div class="container my-5 shadow-sm p-4 bg-white rounded">
    <h2 class="fw-bold mb-1">Your Applications</h2>
    <p class="text-muted mb-4">Track the status of your job applications</p>

    @if(($applicants ?? collect())->isEmpty())
        <div class="rounded-4 border bg-light-subtle p-4 text-center">
            <h5 class="fw-bold mb-2">No application found</h5>
            @if(!empty($searchedEmail))
                <p class="text-muted mb-0">We could not find an application submitted with {{ $searchedEmail }}.</p>
            @else
                <p class="text-muted mb-0">Enter your email from the Application Status button to view your submitted applications.</p>
            @endif
        </div>
    @endif

    @foreach($applicants as $applicant)
        <div class="card shadow-sm mb-4 animated-card delay-5">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <h5 class="mb-1">{{ optional($applicant->position)->title ?: 'Application' }}</h5>
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
                        <p>Thank you very much for your interest in the <strong>{{ optional($applicant->position)->title ?: 'selected' }}</strong> position and for the time you invested in the application process.</p>

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

<footer class="site-footer">
    <div class="footer-shell">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">
                    <img src="{{ asset('images/nclogo.png') }}" alt="Northeastern College logo" class="footer-brand-mark">
                    <div>
                        <h3>Northeastern<br>College</h3>
                    </div>
                </div>

                <ul class="footer-info-list">
                    <li>
                        <a href="https://www.google.com/maps/search/?api=1&query=Villasis%2C+Santiago+City%2C+Isabela+3311" target="_blank" rel="noopener noreferrer" class="footer-contact">
                            <svg class="footer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.35 7-11a7 7 0 1 0-14 0c0 6.65 7 11 7 11Z"/>
                                <circle cx="12" cy="10" r="2.5"/>
                            </svg>
                            <span>Villasis, Santiago City<br>Isabela, 3311</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/NCnianAko" target="_blank" rel="noopener noreferrer" class="footer-contact">
                            <svg class="footer-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M13.5 21v-7h2.3l.4-3h-2.7V9.2c0-.9.3-1.5 1.6-1.5H16V5.1c-.3 0-1.2-.1-2.2-.1-2.2 0-3.8 1.3-3.8 3.8V11H7.5v3H10v7h3.5Z"/>
                            </svg>
                            <span>facebook.com/NCnianAko</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://icloudph.com/nc/sias/" target="_blank" rel="noopener noreferrer" class="footer-contact">
                            <svg class="footer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/>
                            </svg>
                            <span>SIAS Online</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="footer-title">Quick Links</h4>
                <ul class="footer-link-list">
                    <li><a href="{{ route('guest.index') }}">Home</a></li>
                    <li><a href="{{ route('guest.jobOpenLanding') }}">Job Vacancies</a></li>
                    <li><a href="{{ route('login_display') }}">Applicant Login</a></li>
                    <li><a href="{{ route('register') }}">Create Account</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-title">About</h4>
                <p class="footer-feature-text">Building careers, growing leaders, and creating meaningful opportunities for the next generation.</p>
            </div>

            
        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 Northeastern College. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="{{ route('guest.policy') }}">Privacy Policy</a>
                <a href="{{ route('guest.terms') }}">Terms of Service</a>
                <a href="{{ route('guest.cookie') }}">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>
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



