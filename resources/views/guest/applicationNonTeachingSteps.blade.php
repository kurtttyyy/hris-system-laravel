@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .apply-shell {
        max-width: 1240px;
    }

    .apply-card {
        border: 1px solid rgba(22, 101, 52, 0.14);
        border-radius: 1.2rem;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.1);
        overflow: hidden;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdfb 100%);
    }

    .apply-card-body {
        padding: 2rem;
    }

    .apply-hero {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .apply-hero h2 {
        font-size: clamp(1.6rem, 2.2vw, 2.2rem);
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .apply-hero-subtext {
        color: #5b6472 !important;
        margin-bottom: 0;
    }

    .apply-meta {
        border: 1px solid rgba(34, 197, 94, 0.2);
        border-radius: 0.9rem;
        background: linear-gradient(145deg, rgba(22, 163, 74, 0.08), rgba(234, 179, 8, 0.08));
        padding: 0.7rem 0.95rem;
        min-width: 220px;
    }

    .apply-meta-label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #1e7a3f;
        margin-bottom: 0.25rem;
    }

    .apply-meta-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: #123f26;
        margin: 0;
    }

    .step-progress-shell {
        width: 100%;
        height: 8px;
        border-radius: 999px;
        background: #e5e7eb;
        overflow: hidden;
        margin: 1rem 0 1.4rem;
    }

    .step-progress-bar {
        height: 100%;
        width: 20%;
        border-radius: inherit;
        background: linear-gradient(90deg, #16a34a, #22c55e);
        transition: width 0.35s ease;
    }

    .stepper1 {
        margin-bottom: 1rem;
    }

    .circle1 {
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.1);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .step1.active .circle1,
    .step1.completed1 .circle1 {
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(22, 163, 74, 0.2);
    }

    .form-step {
        animation: fadeUpIn 0.35s ease both;
    }

    @keyframes fadeUpIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .floating-input input,
    .floating-input select {
        border-radius: 0.65rem;
        border: 1px solid #d5dbe3;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .floating-input input:focus,
    .floating-input select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.15);
        transform: translateY(-1px);
    }

    #experienceForm .floating-input input:focus,
    #experienceForm .floating-input select:focus,
    #experienceForm .floating-input input:not(:placeholder-shown),
    #experienceForm .floating-input select:not([value=""]) {
        border-color: #16a34a;
        box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.2), 0 10px 22px rgba(22, 163, 74, 0.12);
        background: linear-gradient(180deg, #ffffff 0%, #f4fff8 100%);
    }

    #experienceForm .floating-input label {
        transition: color 0.2s ease, letter-spacing 0.2s ease;
    }

    #experienceForm .floating-input input:focus + label,
    #experienceForm .floating-input input:not(:placeholder-shown) + label,
    #experienceForm .floating-input select:focus + label,
    #experienceForm .floating-input select:not([value=""]) + label {
        color: #0f7a39;
        letter-spacing: 0.01em;
        font-weight: 700;
    }

    .upload-area {
        border-radius: 0.9rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .upload-area:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.1);
    }

    .step-actions {
        position: sticky;
        bottom: 0;
        z-index: 3;
        padding-top: 0.75rem;
        margin-top: 1.25rem !important;
        background: linear-gradient(180deg, rgba(255,255,255,0), rgba(255,255,255,0.96) 36%, #ffffff 100%);
    }

    .step-actions .btn {
        min-width: 120px;
        border-radius: 0.65rem;
        font-weight: 600;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .step-actions .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.12);
    }

    @media (max-width: 992px) {
        .apply-card-body {
            padding: 1.2rem;
        }

        .apply-hero {
            flex-direction: column;
            gap: 0.75rem;
        }

        .apply-meta {
            width: 100%;
        }
    }

    .field-error-highlight {
        border: 1px solid rgba(220, 38, 38, 0.55) !important;
        background: rgba(220, 38, 38, 0.08) !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.16);
        border-radius: 0.5rem;
        animation: errorPulse 0.55s ease-in-out 2, errorShake 0.35s ease-in-out 1;
    }

    @keyframes errorPulse {
        0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.0); }
        50% { box-shadow: 0 0 0 6px rgba(220, 38, 38, 0.18); }
        100% { box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.16); }
    }

    @keyframes errorShake {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-4px); }
        40% { transform: translateX(4px); }
        60% { transform: translateX(-3px); }
        80% { transform: translateX(3px); }
    }

    .year-field-transition {
        overflow: visible;
        max-height: 160px;
        opacity: 1;
        transform: translateY(0);
        transition: max-height 0.55s ease, opacity 0.45s ease, transform 0.45s ease, margin 0.45s ease;
    }

    .year-field-transition.year-hidden {
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transform: translateY(-6px);
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        pointer-events: none;
    }
</style>


@include('layouts.header')  {{-- UNIVERSAL HEADER --}}


<div class="header-divider"></div>

<main class="container my-5 apply-shell">

    @if (session('popup_error'))
        <div class="alert alert-danger" role="alert">
            {{ session('popup_error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <div class="fw-semibold mb-2">Please fix the following:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- step 1 --}}
    <div class="card shadow-sm mb-4 animated-card delay-5 hover-card apply-card">
        <div class="card-body apply-card-body">
            <div class="apply-hero">
                <div>
                    <h2 class="mb-1">Apply for {{ $openPosition->title}}</h2>
                    <h6 class="apply-hero-subtext">Please fill out all fields to complete your application</h6>
                </div>
                <div class="apply-meta">
                    <p class="apply-meta-label">Application Flow</p>
                    <p id="currentStepText" class="apply-meta-value">Step 1 of 5: Personal Info</p>
                </div>
            </div>

            <div class="stepper1">

                <div class="step1 completed1">
                    <div class="circle1">1</div>
                    <div class="label1">Personal Info</div>
                </div>

                <div class="line1 completed1"></div>

                <div class="step1 completed1">
                    <div class="circle1">2</div>
                    <div class="label1">Experience</div>
                </div>

                <div class="line1 completed1"></div>

                <div class="step1">
                    <div class="circle1">3</div>
                    <div class="label1">Documents</div>
                </div>

                <div class="line1"></div>

                <div class="step1">
                    <div class="circle1">4</div>
                    <div class="label1">Review</div>
                </div>

                <div class="line1"></div>

                <div class="step1">
                    <div class="circle1">5</div>
                    <div class="label1">Submit</div>
                </div>

            </div>
<!-- Personal Info Form -->
<form id="formPersonal" action="{{ route('applicant.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" name="position" class="form-control" value="{{ $openPosition->id}}" hidden>
<div id="personalForm" class="mt-4 form-step">
    <h4 class="fw-bold mb-3">Personal Information</h4>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="floating-input">
                <input type="text" id="first_name" name="first_name" class="form-control" placeholder=" " required>
                <label for="first_name">First Name<span class="required-asterisk"> *</span></label>
            </div>
        </div>


        <div class="col-md-6">
            <div class="floating-input">
                <input type="text" id="last_name" name="last_name" class="form-control" placeholder=" " required>
                <label for="last_name">Last Name<span class="required-asterisk"> *</span></label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="floating-input">
                <input type="email" id="email" name="email" class="form-control" placeholder=" " required>
                <label for="email">Email Address<span class="required-asterisk"> *</span></label>
            </div>
        </div>

        <div class="col-md-6">
            <div class="floating-input">
                <input type="text" id="phone" name="phone" class="form-control" placeholder=" " required>
                <label for="phone">Phone Number<span class="required-asterisk"> *</span></label>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="floating-input">
            <input type="text" id="address" name="address" class="form-control" placeholder=" " required>
            <label for="address">Address<span class="required-asterisk"> *</span></label>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-auto step-actions">
        <div></div>
        <button type="button" id="btnToExperience" class="btn btn-primary">Proceed</button>
    </div>
</div>



    <!-- Work Experience & Education Form -->
<div id="experienceForm" class="mt-4 d-none form-step">

    <h4 class="fw-bold mb-3">Educational Background</h4>

    <div id="bachelor-degrees-container">
    <div class="bachelor-degree-entry border rounded p-3 mb-3" data-degree-index="0">
    <div class="mb-3 floating-input">
        <input type="text" class="form-select text-secondary bachelor-degree-select" id="bachelor_degree_0" name="bachelor_degrees[0][degree]" list="bachelorDegreeOptions" placeholder=" " required>
        <label for="bachelor_degree_0">Bachelor Degree<span class="required-asterisk"> *</span></label>
    </div>

    <div class="mb-3 floating-input year-field-transition year-hidden bachelor-school-wrapper">
        <input type="text" class="form-select bachelor-school-input" id="bachelor_school_name_0" name="bachelor_degrees[0][school_name]" placeholder=" ">
        <label for="bachelor_school_name_0">Bachelor School Name</label>
    </div>

    <div class="mb-3 floating-input year-field-transition year-hidden bachelor-year-wrapper">
        <select class="form-select text-secondary bachelor-year-select" id="bachelor_year_finished_0" name="bachelor_degrees[0][year_finished]">
            <option value="" selected style="color: #6c757d;">Select Year Finished</option>
            @for ($year = 2026; $year >= 1900; $year--)
                <option value="{{ $year }}">{{ $year }}</option>
            @endfor
        </select>
        <label for="bachelor_year_finished_0">Bachelor Year Finished</label>
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-outline-danger btn-sm remove-bachelor-degree d-none">Remove</button>
    </div>
    </div>
    </div>

    <div class="mb-3">
        <button type="button" id="addBachelorDegreeBtn" class="btn btn-outline-primary btn-sm">+ Add Another Bachelor Degree</button>
    </div>

    <datalist id="bachelorDegreeOptions">
        <option value="Bachelor of Architecture">
        <option value="Bachelor of Science in Accountancy">
        <option value="Bachelor of Science in Nursing">
        <option value="Bachelor of Medicine and Bachelor of Surgery">
        <option value="Bachelor of Science in Medical Technology">
        <option value="Bachelor of Science in Radiologic Technology">
        <option value="Bachelor of Science in Respiratory Therapy">
        <option value="Bachelor of Science in Occupational Therapy">
        <option value="Bachelor of Science in Pharmacy">
        <option value="Bachelor of Science in Dentistry">
        <option value="Bachelor of Science in Psychology">
        <option value="Bachelor of Science in Public Health">
        <option value="Bachelor of Science in Physical Therapy">
        <option value="Bachelor of Science in Computer Science">
        <option value="Bachelor of Science in Information Technology">
        <option value="Bachelor of Science in Information Systems">
        <option value="Bachelor of Science in Cybersecurity">
        <option value="Bachelor of Science in Software Engineering">
        <option value="Bachelor of Science in Data Science">
        <option value="Bachelor of Science in Computer Engineering">
        <option value="Bachelor of Science in Civil Engineering">
        <option value="Bachelor of Science in Mechanical Engineering">
        <option value="Bachelor of Science in Electrical Engineering">
        <option value="Bachelor of Science in Chemical Engineering">
        <option value="Bachelor of Science in Industrial Engineering">
        <option value="Bachelor of Science in Electronics Engineering">
        <option value="Bachelor of Science in Environmental Engineering">
        <option value="Bachelor of Science in Architectural Engineering">
        <option value="Bachelor of Science in Accounting">
        <option value="Bachelor of Science in Economics">
        <option value="Bachelor of Science in Marketing">
        <option value="Bachelor of Science in Finance">
        <option value="Bachelor of Science in Business Administration">
        <option value="Bachelor of Science in Entrepreneurship">
        <option value="Bachelor of Science in Office Administration">
        <option value="Bachelor of Science in Human Resource Management">
        <option value="Bachelor of Science in Operations Management">
        <option value="Bachelor of Science in Tourism Management">
        <option value="Bachelor of Science in Hospitality Management">
        <option value="Bachelor of Science in Hotel and Restaurant Management">
        <option value="Bachelor of Science in Supply Chain Management">
        <option value="Bachelor of Laws">
        <option value="Bachelor of Arts in Political Science">
        <option value="Bachelor of Public Administration">
        <option value="Bachelor of Science in Criminology">
        <option value="Bachelor of Science in Social Work">
        <option value="Bachelor of Arts in Sociology">
        <option value="Bachelor of Arts in Communication">
        <option value="Bachelor of Arts in Journalism">
        <option value="Bachelor of Arts in English">
        <option value="Bachelor of Arts in Filipino">
        <option value="Bachelor of Arts in Philosophy">
        <option value="Bachelor of Elementary Education">
        <option value="Bachelor of Secondary Education">
        <option value="Bachelor of Special Needs Education">
        <option value="Bachelor of Early Childhood Education">
        <option value="Bachelor of Technical-Vocational Teacher Education">
        <option value="Bachelor of Library and Information Science">
        <option value="Bachelor of Fine Arts">
        <option value="Bachelor of Music">
        <option value="Bachelor of Arts in Literature">
        <option value="Bachelor of Arts in History">
        <option value="Bachelor of Science in Graphic Design">
        <option value="Bachelor of Multimedia Arts">
        <option value="Bachelor of Science in Biology">
        <option value="Bachelor of Science in Biochemistry">
        <option value="Bachelor of Science in Chemistry">
        <option value="Bachelor of Science in Physics">
        <option value="Bachelor of Science in Mathematics">
        <option value="Bachelor of Science in Statistics">
        <option value="Bachelor of Science in Agriculture">
        <option value="Bachelor of Science in Forestry">
        <option value="Bachelor of Science in Fisheries">
    </datalist>

    <div id="master-degrees-container">
    <div class="master-degree-entry border rounded p-3 mb-3" data-degree-index="0">
    <div class="mb-3 floating-input">
        <input type="text" class="form-select text-secondary master-degree-select" id="master_degree_0" name="master_degrees[0][degree]" list="masterDegreeOptions" placeholder=" ">
        <label for="master_degree_0">Master Degree</label>
    </div>

    <div class="mb-3 floating-input year-field-transition year-hidden master-school-wrapper">
        <input type="text" class="form-select master-school-input" id="master_school_name_0" name="master_degrees[0][school_name]" placeholder=" ">
        <label for="master_school_name_0">Master School Name</label>
    </div>

    <div class="mb-3 floating-input year-field-transition year-hidden master-year-wrapper">
        <select class="form-select text-secondary master-year-select" id="master_year_finished_0" name="master_degrees[0][year_finished]">
            <option value="" selected style="color: #6c757d;">Select Year Finished</option>
            @for ($year = 2026; $year >= 1900; $year--)
                <option value="{{ $year }}">{{ $year }}</option>
            @endfor
        </select>
        <label for="master_year_finished_0">Master Year Finished</label>
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-outline-danger btn-sm remove-master-degree d-none">Remove</button>
    </div>
    </div>
    </div>

    <div class="mb-3">
        <button type="button" id="addMasterDegreeBtn" class="btn btn-outline-primary btn-sm">+ Add Another Master Degree</button>
    </div>

    <datalist id="masterDegreeOptions">
        <option value="Master of Arts (MA)">
        <option value="Master of Science (MS/MSc)">
        <option value="Master of Science in Nursing (MSN)">
        <option value="Master of Business Administration (MBA)">
        <option value="Master of Education (MEd)">
        <option value="Master of Fine Arts (MFA)">
        <option value="Master of Laws (LLM)">
        <option value="Master of Social Work (MSW)">
        <option value="Master of Public Health (MPH)">
        <option value="Master of Engineering (MEng)">
        <option value="Master of Research (MRes)">
        <option value="Master of Philosophy (MPhil)">
        <option value="Master of Studies (MSt)">
        <option value="Master of Technology (MTech)">
        <option value="Master of Science in Information Technology (MSIT)">
        <option value="Master of Computer Applications (MCA)">
        <option value="Master of Veterinary Science (MVSc)">
        <option value="Master of Architecture (MArch)">
        <option value="Master of Public Administration (MPA)">
    </datalist>

    <datalist id="doctoralDegreeOptions">
        <option value="Doctor of Philosophy (PhD)">
        <option value="Doctor of Education (EdD)">
        <option value="Doctor of Musical Arts (DMA)">
        <option value="Doctor of Theology (ThD)">
        <option value="Doctor of Science (DSc/ScD)">
        <option value="Doctor of Arts (DA)">
        <option value="Doctor of Business Administration (DBA)">
        <option value="Doctor of Medicine (MD)">
        <option value="Doctor of Osteopathic Medicine (DO)">
        <option value="Doctor of Dental Surgery (DDS)">
        <option value="Doctor of Nursing Practice (DNP/DNSc)">
        <option value="Doctor of Pharmacy (PharmD)">
        <option value="Doctor of Podiatric Medicine (DPM)">
        <option value="Doctor of Physical Therapy (DPT)">
        <option value="Juris Doctor (JD)">
        <option value="Doctor of Juridical Science (JSD/SJD)">
        <option value="Doctor of Canon Law (JCD)">
        <option value="Doctor of Psychology (PsyD)">
        <option value="Doctor of Public Administration (DPA)">
        <option value="Doctor of Design (DDes)">
        <option value="Doctor of Fine Arts (DFA)">
        <option value="Doctor of Behavioral Health (DBH)">
        <option value="Doctor of Criminal Justice (DCJ)">
        <option value="Doctor of Information Technology (DIT)">
        <option value="Doctor of Social Work (DSW)">
        <option value="Doctor of Architecture (DArch)">
        <option value="Doctor of Professional Studies (DPS)">
        <option value="Doctor of Sustainability (DSus)">
    </datalist>

    <div id="doctoral-degrees-container">
    <div class="doctoral-degree-entry border rounded p-3 mb-3" data-degree-index="0">
    <div class="mb-3 floating-input">
        <input type="text" class="form-select text-secondary doctoral-degree-select" id="doctoral_degree_0" name="doctoral_degrees[0][degree]" list="doctoralDegreeOptions" placeholder=" ">
        <label for="doctoral_degree_0">Doctoral Degree</label>
    </div>

    <div class="mb-3 floating-input year-field-transition year-hidden doctoral-school-wrapper">
        <input type="text" class="form-select doctoral-school-input" id="doctoral_school_name_0" name="doctoral_degrees[0][school_name]" placeholder=" ">
        <label for="doctoral_school_name_0">Doctoral School Name</label>
    </div>

    <div class="mb-3 floating-input year-field-transition year-hidden doctoral-year-wrapper">
        <select class="form-select text-secondary doctoral-year-select" id="doctoral_year_finished_0" name="doctoral_degrees[0][year_finished]">
            <option value="" selected style="color: #6c757d;">Select Year Finished</option>
            @for ($year = 2026; $year >= 1900; $year--)
                <option value="{{ $year }}">{{ $year }}</option>
            @endfor
        </select>
        <label for="doctoral_year_finished_0">Doctoral Year Finished</label>
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-outline-danger btn-sm remove-doctoral-degree d-none">Remove</button>
    </div>
    </div>
    </div>

    <div class="mb-3">
        <button type="button" id="addDoctoralDegreeBtn" class="btn btn-outline-primary btn-sm">+ Add Another Doctoral Degree</button>
    </div>

    <div class="mb-3 floating-input">
        <input type="text" class="form-select" id="university_address" name="university_address" placeholder=" " required>
        <label for="university_address">Address<span class="required-asterisk"> *</span></label>
    </div>

    <h4 class="fw-bold mb-3 mt-4">Work Experience</h4>

    <div class="mb-3 form-check">
        <input type="hidden" name="fresh_graduate" value="0">
        <input class="form-check-input" type="checkbox" id="fresh_graduate" name="fresh_graduate" value="1">
        <label class="form-check-label" for="fresh_graduate">
            I am a Fresh Graduate (No work experience yet)
        </label>
    </div>

    <div class="mb-3 floating-input">
        <input type="text" class="form-select" id="work_position" name="work_position" placeholder=" " required>
        <label for="work_position">Position<span class="required-asterisk"> *</span></label>
    </div>

    <div class="mb-3 floating-input">
        <input type="text" class="form-select" id="work_employer" name="work_employer" placeholder=" " required>
        <label for="work_employer">Employer<span class="required-asterisk"> *</span></label>
    </div>

    <div class="mb-3 floating-input">
        <input type="text" class="form-select" id="work_location" name="work_location" placeholder=" " required>
        <label for="work_location">Location<span class="required-asterisk"> *</span></label>
    </div>

    <div class="mb-3 floating-input">
        <input type="text" class="form-select" id="work_duration" name="work_duration" placeholder=" " required>
        <label for="work_duration">Duration<span class="required-asterisk"> *</span></label>
    </div>

    <div class="mb-3 floating-input">
        <select class="form-select" id="experience_years" name="experience_years" required>
            <option value="" disabled selected></option>
            <option value="0–1">0–1</option>
            <option value="2–3">2–3</option>
            <option value="4–5">4–5</option>
            <option value="6+">6+</option>
        </select>
        <label for="experience_years">Years of Relevant Experience<span class="required-asterisk"> *</span></label>
    </div>

    <div class="mb-4 floating-input">
        <input list="skillsList" class="form-control" id="key_skills"
               name="key_skills" placeholder=" " required>
        <label for="key_skills">Key Skill & Expertise<span class="required-asterisk"> *</span></label>

        <datalist id="skillsList">
            <option value="Team Leadership">
            <option value="Project Management">
            <option value="Communication">
            <option value="Software Development">
            <option value="Graphic Design">
            <option value="Data Analysis">
            <option value="Customer Service">
        </datalist>
    </div>

    <div class="d-flex justify-content-between mt-auto step-actions">
        <button type="button" id="btnBackToPersonal" class="btn btn-secondary">Previous</button>
        <button type="button" id="btnToDocuments" class="btn btn-primary">Proceed</button>
    </div>

</div>


    <!--Documents Form-->
 <div id="documentsForm" class="mt-4 d-none form-step">
    <h4 class="fw-bold mb-3">Required Document</h4>

    <!-- Resume/CV -->
    <div class="mb-4">
        <label class="form-label fw-semibold">Resume/CV <span class="required-asterisk"> *</span></label>
        <label class="upload-area">
            <i class="bi bi-file-earmark-arrow-up upload-icon"></i>
            <div class="upload-main-text">Click to upload your resume</div>
            <div class="upload-sub-text">PDF, DOC, DOCX (up to 5MB)</div>
            <input type="file" id="resume" name="documents[0][file]" accept=".pdf,.doc,.docx" required>
            <input type="hidden" name="documents[0][type]" value="Resume/CV">
        </label>
    </div>

    <!-- Cover Letter -->
    <div class="mb-4">
        <label class="form-label fw-semibold">Cover Letter <span class="required-asterisk"> *</span></label>
        <label class="upload-area">
            <i class="bi bi-file-earmark-arrow-up upload-icon"></i>
            <div class="upload-main-text">Click to upload your cover letter</div>
            <div class="upload-sub-text">PDF, DOC, DOCX (up to 5MB)</div>
            <input type="file" id="cover_letter" name="documents[1][file]" accept=".pdf,.doc,.docx" required>
            <input type="hidden" name="documents[1][type]" value="Cover Letter">
        </label>
    </div>

    <!-- Personal Data Sheet -->
    <div class="mb-4">
        <label class="form-label fw-semibold">Personal Data Sheet <span class="required-asterisk"> *</span></label>
        <label class="upload-area">
            <i class="bi bi-file-earmark-arrow-up upload-icon"></i>
            <div class="upload-main-text">Click to upload your Personal Data Sheet</div>
            <div class="upload-sub-text">PDF, DOC, DOCX (up to 5MB)</div>
            <input type="file" id="personal_data_sheet" name="documents[2][file]" accept=".pdf,.doc,.docx" required>
            <input type="hidden" name="documents[2][type]" value="Personal Data Sheet">
        </label>
    </div>

    <!-- Transcript Of Records -->
    <div class="mb-4">
        <label class="form-label fw-semibold">Transcript Of Records <span class="required-asterisk"> *</span></label>
        <label class="upload-area">
            <i class="bi bi-file-earmark-arrow-up upload-icon"></i>
            <div class="upload-main-text">Click to upload your Transcript Of Records</div>
            <div class="upload-sub-text">PDF, DOC, DOCX (up to 5MB)</div>
            <input type="file" id="TOR" name="documents[3][file]" accept=".pdf,.doc,.docx" required>
            <input type="hidden" name="documents[3][type]" value="Transcript Of Records">
        </label>
    </div>

    <!-- Diploma, Master's, Doctorate -->
    <div class="mb-4">
        <label class="form-label fw-semibold">Diploma, Master's (if available), Doctorate (if available)</label>
        <label class="upload-area">
            <i class="bi bi-file-earmark-arrow-up upload-icon"></i>
            <div class="upload-main-text">Click to upload your Diploma, Master's, Doctorate </div>
            <div class="upload-sub-text">PDF, DOC, DOCX (up to 5MB)</div>
            <input type="file" id="diploma" name="documents[4][file]" accept=".pdf,.doc,.docx">
            <input type="hidden" name="documents[4][type]" value="Diploma">
        </label>
    </div>

    <!-- PRC License/Board Rating -->
    <div class="mb-4">
        <label class="form-label fw-semibold">PRC License/Board Rating (if Applicable)</label>
        <label  class="upload-area">
            <i class="bi bi-file-earmark-arrow-up upload-icon"></i>
            <div class="upload-main-text">Click to upload your PRC License/Board Rating</div>
            <div class="upload-sub-text">PDF, DOC, DOCX (up to 5MB)</div>
            <input type="file" id="board_rating" name="documents[5][file]" accept=".pdf,.doc,.docx">
            <input type="hidden" name="documents[5][type]" value="PRC License/Board Rating">
        </label>
    </div>

    <!-- Certificate Of Eligibility / Certificate of Passing -->
    <div class="mb-4">
        <label class="form-label fw-semibold">Certificate Of Eligibility / Certificate of Passing (If Applicable)</label>
        <label  class="upload-area">
            <i class="bi bi-file-earmark-arrow-up upload-icon"></i>
            <div class="upload-main-text">Click to upload your Certificate Of Eligibility / Certificate of Passing</div>
            <div class="upload-sub-text">PDF, DOC, DOCX (up to 5MB)</div>
            <input type="file" id="certification_eligibility" name="documents[6][file]" accept=".pdf,.doc,.docx">
            <input type="hidden" name="documents[6][type]" value="Certificate Of Eligibility / Certificate of Passing">
        </label>
    </div>

    <!-- Certifications -->
    <div class="mb-4">
        <label class="form-label fw-semibold">Certifications & Supporting Document <span class="required-asterisk"> *</span></label>
        <label class="upload-area">
            <i class="bi bi-file-earmark-arrow-up upload-icon"></i>
            <div class="upload-main-text">Click to upload your documents</div>
            <div class="upload-sub-text">PDF, DOC, DOCX (up to 5MB)</div>
            <input type="file" id="certifications" name="documents[7][file]" accept=".pdf,.doc,.docx" required>
            <input type="hidden" name="documents[7][type]" value="Certifications & Supporting Document">
        </label>
    </div>

    <!-- Membership/Affiliation -->
    <div class="mb-4">
        <label class="form-label fw-semibold">Membership/affiliation (If Applicable)</label>
        <label class="upload-area">
            <i class="bi bi-file-earmark-arrow-up upload-icon"></i>
            <div class="upload-main-text">Click to upload your documents</div>
            <div class="upload-sub-text">PDF, DOC, DOCX (up to 5MB)</div>
            <input type="file" id="membership_affiliation" name="documents[8][file]" accept=".pdf,.doc,.docx">
            <input type="hidden" name="documents[8][type]" value="Membership/Affiliation">
        </label>
    </div>

    <div class="d-flex justify-content-between step-actions">
        <button type="button" id="btnBackToExperience" class="btn btn-secondary">Previous</button>
        <button type="button" id="btnToReview" class="btn btn-primary">Proceed</button>
    </div>
</div>




        <!-- Review & Submit Form (to be implemented) -->
    <!-- Review Your Application Form -->
    <div id="reviewForm" class="mt-4 d-none form-step">
        <h3 class="fw-bold mb-3">Review Your Application</h3>

        <div class="review-notice d-flex align-items-start mb-4">
            <div class="review-icon">i</div>
            <div class="ms-3">
                <div class="fw-semibold" style="font-size: 1.1rem;">Before you submit</div>
                <div class="text-dark-green">
                    Please review all information carefully. You can go back to any previous step to make sure changes.
                </div>
            </div>
        </div>

        <!-- Personal Information Summary -->
        <div class="mb-4 p-3 border rounded shadow-sm bg-light">
            <h5 class="text-uppercase text-success">Personal Information</h5>

            <p class="text-uppercase fw-semibold">
                First Name:
                <span id="review-first-name" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Last Name:
                <span id="review-last-name" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Email Address:
                <span id="review-email" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Phone Number:
                <span id="review-phone" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Address:
                <span id="review-address" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>
        </div>


        <!-- Education & Experience Summary -->
        <div class="mb-4 p-3 border rounded shadow-sm bg-light">
            <h5 class="text-uppercase text-success">Education & Experience</h5>

            <p class="text-uppercase fw-semibold">
                Bachelor Degree(s):
                <span id="review-bachelor-degree" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Master Degree:
                <span id="review-master-degree" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Doctoral Degree:
                <span id="review-doctoral-degree" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Address:
                <span id="uni_add" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Position:
                <span id="work_po" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Employer:
                <span id="work_em" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Location:
                <span id="work_lo" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Duration:
                <span id="work_du" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Years of Relevant Experience:
                <span id="review-experience-years" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Key Skills & Expertise:
                <span id="review-key-skills" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>
        </div>


        <!-- Documents Summary -->
        <div class="mb-4 p-3 border rounded shadow-sm bg-light">
            <h5 class="text-uppercase text-success">Documents</h5>

            <p class="text-uppercase fw-semibold">
                Resume/CV:
                <span id="review-resume-file" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Cover Letter:
                <span id="review-cover-file" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Personal Data Sheet:
                <span id="personal" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Transcript Of Records:
                <span id="tor" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Diploma, Master's, Doctorate:
                <span id="dip" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                PRC License / Board Rating:
                <span id="prc" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Certificate Of Eligibility / Certificate of Passing:
                <span id="passing" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Certifications:
                <span id="review-certs-file" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Membership / Affiliation:
                <span id="membership" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>
        </div>




    <!-- Certification Checkbox -->
    <div class="review-notice1 d-flex align-items-start mb-4">
        <div class="form-check mb-3">
            <input
                class="form-check-input"
                type="checkbox"
                id="certifyCheckbox"
                required
            >
            <label class="form-check-label text-secondary" for="certifyCheckbox">
                I certify that all information provided is true and accurate to the best of my knowledge.
                I understand that any false information may result in disqualification.
            </label>
        </div>
    </div>




        <div class="d-flex justify-content-between step-actions">
            <button type="button" id="btnBackToDocumentsFromReview" class="btn btn-secondary">Previous</button>
            <button type="submit" class="btn btn-success">Submit Application</button>
        </div>
    </div>
</form>


        </div>
    </div>
</main>

<!-- JS for Dynamic File Name Display with Truncation -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInputs = document.querySelectorAll('#documentsForm input[type="file"]');

    fileInputs.forEach(input => {
        const uploadArea = input.closest('.upload-area');
        const uploadText = uploadArea.querySelector('.upload-main-text');
        const uploadSubText = uploadArea.querySelector('.upload-sub-text');

        // ONLY handle change event, DO NOT trigger input.click()
        input.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                let fileName = this.files[0].name;

                // truncate long names
                if (fileName.length > 30) {
                    const ext = fileName.split('.').pop();
                    fileName = fileName.substring(0, 25) + '...' + '.' + ext;
                }

                uploadText.textContent = fileName;
                uploadSubText.textContent = 'File selected successfully';
            }
        });
    });
});
</script>



<script>
document.addEventListener('DOMContentLoaded', () => {

    /* =======================
       FORM SECTIONS
    ======================= */
    const personalForm   = document.getElementById('personalForm');
    const experienceForm = document.getElementById('experienceForm');
    const documentsForm  = document.getElementById('documentsForm');
    const reviewForm     = document.getElementById('reviewForm');

    /* =======================
       BUTTONS
    ======================= */
    const btnToExperience              = document.getElementById('btnToExperience');
    const btnBackToPersonal            = document.getElementById('btnBackToPersonal');
    const btnToDocuments               = document.getElementById('btnToDocuments');
    const btnBackToExperience          = document.getElementById('btnBackToExperience');
    const btnToReview                  = document.getElementById('btnToReview');
    const btnBackToDocumentsFromReview = document.getElementById('btnBackToDocumentsFromReview');

    /* =======================
       STEPPER ELEMENTS
    ======================= */
    const steps = document.querySelectorAll('.step1');
    const lines = document.querySelectorAll('.line1');
    const stepProgressBar = document.getElementById('stepProgressBar');
    const currentStepText = document.getElementById('currentStepText');

    const stepMeta = {
        1: 'Step 1 of 5: Personal Info',
        2: 'Step 2 of 5: Experience',
        3: 'Step 3 of 5: Documents',
        4: 'Step 4 of 5: Review',
        5: 'Step 5 of 5: Submit',
    };

    function setStep(stepNumber) {
        steps.forEach((step, index) => {
            step.classList.remove('active', 'completed1');
            if (index + 1 < stepNumber) step.classList.add('completed1');
            else if (index + 1 === stepNumber) step.classList.add('active');
        });

        lines.forEach((line, index) => {
            line.classList.toggle('completed1', index < stepNumber - 1);
        });

        if (stepProgressBar) {
            const maxStep = Math.max(1, steps.length);
            const progress = Math.min(Math.max(stepNumber, 1), maxStep);
            stepProgressBar.style.width = `${(progress / maxStep) * 100}%`;
        }

        if (currentStepText) {
            currentStepText.textContent = stepMeta[stepNumber] || stepMeta[1];
        }
    }

    setStep(1); // Initial step

    /* =======================
       CERTIFICATION CHECKBOX
    ======================= */
    const certifyCheckbox = document.getElementById('certifyCheckbox');
    const submitButton = reviewForm.querySelector('button[type="submit"]');
    const applicationForm = document.getElementById('formPersonal');
    submitButton.disabled = true;

    const positionInput = applicationForm?.querySelector('input[name="position"]');
    const formDraftStorageKey = `non_teaching_application_draft:${window.location.pathname}:${positionInput?.value ?? 'unknown'}`;

    const persistableFields = applicationForm
        ? Array.from(applicationForm.querySelectorAll('input[id][name], select[id][name], textarea[id][name]'))
            .filter((field) => {
                const type = (field.type || '').toLowerCase();
                return type !== 'file' && type !== 'password';
            })
        : [];

    function saveFormDraft() {
        if (!applicationForm) return;

        const payload = {};
        persistableFields.forEach((field) => {
            payload[field.id] = (field.type || '').toLowerCase() === 'checkbox'
                ? field.checked
                : field.value;
        });

        try {
            localStorage.setItem(formDraftStorageKey, JSON.stringify(payload));
        } catch (_) {
            // Ignore storage errors (private mode/quota).
        }
    }

    function restoreFormDraft() {
        if (!applicationForm) return;

        let payload = null;
        try {
            payload = JSON.parse(localStorage.getItem(formDraftStorageKey) || 'null');
        } catch (_) {
            payload = null;
        }
        if (!payload || typeof payload !== 'object') return;

        persistableFields.forEach((field) => {
            if (!(field.id in payload)) return;
            const value = payload[field.id];
            if ((field.type || '').toLowerCase() === 'checkbox') {
                field.checked = Boolean(value);
            } else {
                field.value = value ?? '';
            }
        });
    }

    function clearFormDraft() {
        try {
            localStorage.removeItem(formDraftStorageKey);
        } catch (_) {
            // Ignore storage errors.
        }
    }

    certifyCheckbox.addEventListener('change', () => {
        submitButton.disabled = !certifyCheckbox.checked;
    });

    function hasRequiredAsterisk(field) {
        if (!field) return false;

        const fieldType = (field.type || '').toLowerCase();
        if (fieldType === 'file') {
            const block = field.closest('.mb-4');
            const blockLabel = block?.querySelector(':scope > label.form-label');
            return !!(blockLabel && (blockLabel.querySelector('.required-asterisk') || blockLabel.textContent.includes('*')));
        }

        const fieldId = field.id || '';
        const explicitLabel = fieldId
            ? applicationForm.querySelector(`label[for="${fieldId}"]`)
            : null;

        return !!(explicitLabel && (explicitLabel.querySelector('.required-asterisk') || explicitLabel.textContent.includes('*')));
    }

    function getErrorHighlightTarget(field) {
        if (!field) return null;

        if ((field.type || '').toLowerCase() === 'file') {
            return field.closest('.upload-area') || field;
        }
        if ((field.type || '').toLowerCase() === 'checkbox') {
            return field.closest('.review-notice1') || field.closest('.form-check') || field;
        }

        return field.closest('.floating-input')
            || field.closest('.mb-3')
            || field.closest('.col-md-6')
            || field;
    }

    function clearErrorHighlight(field) {
        const target = getErrorHighlightTarget(field);
        if (target) target.classList.remove('field-error-highlight');
    }

    function showErrorHighlight(field) {
        const target = getErrorHighlightTarget(field);
        if (!target) return;
        target.classList.remove('field-error-highlight');
        // force reflow so animation retriggers
        void target.offsetWidth;
        target.classList.add('field-error-highlight');
    }

    function showStepFormForField(field) {
        if (!field) return;

        const isInPersonal = !!field.closest('#personalForm');
        const isInExperience = !!field.closest('#experienceForm');
        const isInDocuments = !!field.closest('#documentsForm');
        const isInReview = !!field.closest('#reviewForm');

        personalForm.classList.add('d-none');
        experienceForm.classList.add('d-none');
        documentsForm.classList.add('d-none');
        reviewForm.classList.add('d-none');

        if (isInPersonal) {
            personalForm.classList.remove('d-none');
            setStep(1);
            return;
        }
        if (isInExperience) {
            experienceForm.classList.remove('d-none');
            setStep(2);
            return;
        }
        if (isInDocuments) {
            documentsForm.classList.remove('d-none');
            setStep(3);
            return;
        }
        if (isInReview) {
            reviewForm.classList.remove('d-none');
            setStep(4);
            return;
        }

        reviewForm.classList.remove('d-none');
        setStep(4);
    }

    if (applicationForm) {
        applicationForm.setAttribute('novalidate', 'novalidate');

        applicationForm.querySelectorAll('[required]').forEach((field) => {
            if (!hasRequiredAsterisk(field)) return;
            field.addEventListener('input', () => clearErrorHighlight(field));
            field.addEventListener('change', () => clearErrorHighlight(field));
        });

        applicationForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const requiredFields = Array.from(applicationForm.querySelectorAll('[required]'))
                .filter((field) => !field.disabled && hasRequiredAsterisk(field));

            const invalidFields = requiredFields.filter((field) => {
                const type = (field.type || '').toLowerCase();
                if (type === 'file') return !(field.files && field.files.length > 0);
                if (type === 'checkbox') return !field.checked;
                return !field.checkValidity();
            });

            if (!invalidFields.length) {
                clearFormDraft();
                applicationForm.submit();
                return;
            }

            invalidFields.forEach((field) => showErrorHighlight(field));

            const firstInvalid = invalidFields[0];
            showStepFormForField(firstInvalid);
            const firstTarget = getErrorHighlightTarget(firstInvalid) || firstInvalid;
            setTimeout(() => {
                if (firstTarget && typeof firstTarget.scrollIntoView === 'function') {
                    firstTarget.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                if (firstInvalid && typeof firstInvalid.focus === 'function') {
                    firstInvalid.focus({ preventScroll: true });
                }
                showErrorHighlight(firstInvalid);
            }, 40);
        });
    }

    restoreFormDraft();
    submitButton.disabled = !certifyCheckbox.checked;
    persistableFields.forEach((field) => {
        field.addEventListener('input', saveFormDraft);
        field.addEventListener('change', saveFormDraft);
    });

    /* =======================
       FORM TRANSITION FUNCTION
    ======================= */
    function transitionForms(hideForm, showForm, direction = 'forward') {
        const outClass = direction === 'forward' ? 'slide-out-left' : 'slide-out-right';
        const inClass  = direction === 'forward' ? 'slide-in-right' : 'slide-in-left';

        hideForm.classList.add(outClass);

        setTimeout(() => {
            hideForm.classList.add('d-none');
            hideForm.classList.remove(outClass);

            showForm.classList.remove('d-none');
            showForm.classList.add(inClass);

            setTimeout(() => {
                showForm.classList.remove(inClass);
            }, 450);
        }, 300);
    }

    /* =======================
       NAVIGATION LOGIC
    ======================= */

    // Step 1 → Step 2
    btnToExperience.addEventListener('click', () => {
        transitionForms(personalForm, experienceForm, 'forward');
        setStep(2);
    });

    // Step 2 → Step 1
    btnBackToPersonal.addEventListener('click', () => {
        transitionForms(experienceForm, personalForm, 'back');
        setStep(1);
    });

    // Step 2 → Step 3
    btnToDocuments.addEventListener('click', () => {
        transitionForms(experienceForm, documentsForm, 'forward');
        setStep(3);
    });

    // Step 3 → Step 2
    btnBackToExperience.addEventListener('click', () => {
        transitionForms(documentsForm, experienceForm, 'back');
        setStep(2);
    });

    // Step 3 → Step 4 (Review)
    btnToReview.addEventListener('click', () => {
        // Populate review fields
        document.getElementById('review-first-name').textContent = document.getElementById('first_name').value;
        document.getElementById('review-last-name').textContent = document.getElementById('last_name').value;
        document.getElementById('review-email').textContent = document.getElementById('email').value;
        document.getElementById('review-phone').textContent = document.getElementById('phone').value;
        document.getElementById('review-address').textContent = document.getElementById('address').value;

        const bachelorEntries = Array.from(document.querySelectorAll('.bachelor-degree-entry'));
        const masterEntries = Array.from(document.querySelectorAll('.master-degree-entry'));
        const doctoralEntries = Array.from(document.querySelectorAll('.doctoral-degree-entry'));

        const formatDegreeReview = (degree, school, year) => {
            if (!degree) return 'N/A';
            const schoolLabel = school || 'School not provided';
            const yearLabel = year || 'Year not provided';
            return `${degree} - ${schoolLabel} (${yearLabel})`;
        };

        const bachelorReviewLines = bachelorEntries
            .map((entry) => {
                const degree = (entry.querySelector('.bachelor-degree-select')?.value || '').trim();
                const school = (entry.querySelector('.bachelor-school-input')?.value || '').trim();
                const year = (entry.querySelector('.bachelor-year-select')?.value || '').trim();
                return formatDegreeReview(degree, school, year);
            })
            .filter((line) => line !== 'N/A');

        const masterReviewLines = masterEntries
            .map((entry) => {
                const degree = (entry.querySelector('.master-degree-select')?.value || '').trim();
                const school = (entry.querySelector('.master-school-input')?.value || '').trim();
                const year = (entry.querySelector('.master-year-select')?.value || '').trim();
                return formatDegreeReview(degree, school, year);
            })
            .filter((line) => line !== 'N/A');

        const doctoralReviewLines = doctoralEntries
            .map((entry) => {
                const degree = (entry.querySelector('.doctoral-degree-select')?.value || '').trim();
                const school = (entry.querySelector('.doctoral-school-input')?.value || '').trim();
                const year = (entry.querySelector('.doctoral-year-select')?.value || '').trim();
                return formatDegreeReview(degree, school, year);
            })
            .filter((line) => line !== 'N/A');

        document.getElementById('review-bachelor-degree').textContent =
            bachelorReviewLines.length ? bachelorReviewLines.join(' | ') : 'N/A';
        document.getElementById('review-master-degree').textContent =
            masterReviewLines.length ? masterReviewLines.join(' | ') : 'N/A';
        document.getElementById('review-doctoral-degree').textContent =
            doctoralReviewLines.length ? doctoralReviewLines.join(' | ') : 'N/A';
        document.getElementById('review-experience-years').textContent = document.getElementById('experience_years').value;
        document.getElementById('review-key-skills').textContent = document.getElementById('key_skills').value;

        document.getElementById('uni_add').textContent = document.getElementById('university_address').value;
        document.getElementById('work_po').textContent = document.getElementById('work_position').value;
        document.getElementById('work_em').textContent = document.getElementById('work_employer').value;
        document.getElementById('work_lo').textContent = document.getElementById('work_location').value;
        document.getElementById('work_du').textContent = document.getElementById('work_duration').value;

        const resumeInput = document.getElementById('resume');
        const coverInput  = document.getElementById('cover_letter');
        const certsInput  = document.getElementById('certifications');
        const personnalInput = document.getElementById('personal_data_sheet');
        const torInput  = document.getElementById('TOR');
        const diplomaInput  = document.getElementById('diploma');
        const boardRatingInput = document.getElementById('board_rating');
        const certificateEligibilityInput  = document.getElementById('certification_eligibility');
        const membershipInput  = document.getElementById('membership_affiliation');

        document.getElementById('review-resume-file').textContent =
            resumeInput.files.length ? resumeInput.files[0].name : 'None';
        document.getElementById('review-cover-file').textContent =
            coverInput.files.length ? coverInput.files[0].name : 'None';
        document.getElementById('review-certs-file').textContent =
            certsInput.files.length ? certsInput.files[0].name : 'None';
        document.getElementById('personal').textContent =
            personnalInput.files.length ? personnalInput.files[0].name : 'None';
        document.getElementById('tor').textContent =
            torInput.files.length ? torInput.files[0].name : 'None';
        document.getElementById('dip').textContent =
            diplomaInput.files.length ? diplomaInput.files[0].name : 'None';
        document.getElementById('prc').textContent =
            boardRatingInput.files.length ? boardRatingInput.files[0].name : 'None';
        document.getElementById('passing').textContent =
            certificateEligibilityInput.files.length ? certificateEligibilityInput.files[0].name : 'None';
        document.getElementById('membership').textContent =
            membershipInput.files.length ? membershipInput.files[0].name : 'None';


        transitionForms(documentsForm, reviewForm, 'forward');
        certifyCheckbox.checked = false;
        submitButton.disabled = true;
        setStep(4);
    });

    // Step 4 → Step 3
    btnBackToDocumentsFromReview.addEventListener('click', () => {
        transitionForms(reviewForm, documentsForm, 'back');
        setStep(3);
    });

    const bachelorDegreesContainer = document.getElementById('bachelor-degrees-container');
    const addBachelorDegreeBtn = document.getElementById('addBachelorDegreeBtn');
    const masterDegreesContainer = document.getElementById('master-degrees-container');
    const addMasterDegreeBtn = document.getElementById('addMasterDegreeBtn');
    const doctoralDegreesContainer = document.getElementById('doctoral-degrees-container');
    const addDoctoralDegreeBtn = document.getElementById('addDoctoralDegreeBtn');
    const yearRevealDelayMs = 140;
    const freshGraduateCheckbox = document.getElementById('fresh_graduate');
    const workPositionInput = document.getElementById('work_position');
    const workEmployerInput = document.getElementById('work_employer');
    const workLocationInput = document.getElementById('work_location');
    const workDurationInput = document.getElementById('work_duration');
    const experienceYearsInput = document.getElementById('experience_years');

    const updateSingleSelectColor = (select) => {
        if (!select) return;
        if (select.value) {
            select.classList.remove('text-secondary');
            select.classList.add('text-dark');
        } else {
            select.classList.remove('text-dark');
            select.classList.add('text-secondary');
        }
    };

    const toggleBachelorEntryFields = (entry, shouldResetWhenEmpty = true) => {
        if (!entry) return;
        const degreeSelect = entry.querySelector('.bachelor-degree-select');
        const schoolWrapper = entry.querySelector('.bachelor-school-wrapper');
        const schoolInput = entry.querySelector('.bachelor-school-input');
        const yearWrapper = entry.querySelector('.bachelor-year-wrapper');
        const yearInput = entry.querySelector('.bachelor-year-select');
        if (!degreeSelect || !schoolWrapper || !schoolInput || !yearWrapper || !yearInput) return;

        updateSingleSelectColor(degreeSelect);
        if (degreeSelect.value) {
            setTimeout(() => schoolWrapper.classList.remove('year-hidden'), yearRevealDelayMs);
            setTimeout(() => yearWrapper.classList.remove('year-hidden'), yearRevealDelayMs);
        } else {
            schoolWrapper.classList.add('year-hidden');
            yearWrapper.classList.add('year-hidden');
            if (shouldResetWhenEmpty) {
                schoolInput.value = '';
                yearInput.value = '';
            }
        }
    };

    const reindexBachelorEntries = () => {
        const entries = Array.from(bachelorDegreesContainer?.querySelectorAll('.bachelor-degree-entry') || []);
        entries.forEach((entry, index) => {
            entry.dataset.degreeIndex = index;

            const degreeSelect = entry.querySelector('.bachelor-degree-select');
            const schoolInput = entry.querySelector('.bachelor-school-input');
            const yearSelect = entry.querySelector('.bachelor-year-select');
            const degreeLabel = entry.querySelector('label[for^="bachelor_degree_"]');
            const schoolLabel = entry.querySelector('label[for^="bachelor_school_name_"]');
            const yearLabel = entry.querySelector('label[for^="bachelor_year_finished_"]');
            const removeBtn = entry.querySelector('.remove-bachelor-degree');

            if (degreeSelect) {
                degreeSelect.id = `bachelor_degree_${index}`;
                degreeSelect.name = `bachelor_degrees[${index}][degree]`;
            }
            if (schoolInput) {
                schoolInput.id = `bachelor_school_name_${index}`;
                schoolInput.name = `bachelor_degrees[${index}][school_name]`;
            }
            if (yearSelect) {
                yearSelect.id = `bachelor_year_finished_${index}`;
                yearSelect.name = `bachelor_degrees[${index}][year_finished]`;
            }
            if (degreeLabel) degreeLabel.setAttribute('for', `bachelor_degree_${index}`);
            if (schoolLabel) schoolLabel.setAttribute('for', `bachelor_school_name_${index}`);
            if (yearLabel) yearLabel.setAttribute('for', `bachelor_year_finished_${index}`);
            if (removeBtn) removeBtn.classList.toggle('d-none', index === 0 && entries.length === 1);
        });
    };

    const bindBachelorEntry = (entry, shouldResetWhenEmpty = true) => {
        if (!entry) return;
        const degreeSelect = entry.querySelector('.bachelor-degree-select');
        const removeBtn = entry.querySelector('.remove-bachelor-degree');

        degreeSelect?.addEventListener('change', () => toggleBachelorEntryFields(entry, true));
        degreeSelect?.addEventListener('input', () => toggleBachelorEntryFields(entry, false));
        removeBtn?.addEventListener('click', () => {
            const entries = bachelorDegreesContainer?.querySelectorAll('.bachelor-degree-entry') || [];
            if (entries.length <= 1) return;
            entry.remove();
            reindexBachelorEntries();
        });

        toggleBachelorEntryFields(entry, shouldResetWhenEmpty);
    };

    const addBachelorDegreeEntry = () => {
        const firstEntry = bachelorDegreesContainer?.querySelector('.bachelor-degree-entry');
        if (!firstEntry || !bachelorDegreesContainer) return;

        const clone = firstEntry.cloneNode(true);
        clone.querySelectorAll('input, select').forEach((field) => {
            if ((field.type || '').toLowerCase() === 'select-one') {
                field.selectedIndex = 0;
            } else {
                field.value = '';
            }
        });
        clone.querySelectorAll('.year-field-transition').forEach((wrapper) => wrapper.classList.add('year-hidden'));

        bachelorDegreesContainer.appendChild(clone);
        reindexBachelorEntries();
        bindBachelorEntry(clone, true);
    };

    addBachelorDegreeBtn?.addEventListener('click', addBachelorDegreeEntry);
    Array.from(bachelorDegreesContainer?.querySelectorAll('.bachelor-degree-entry') || []).forEach((entry) => {
        bindBachelorEntry(entry, false);
    });
    reindexBachelorEntries();

    const toggleMasterEntryFields = (entry, shouldResetWhenEmpty = true) => {
        if (!entry) return;
        const degreeSelect = entry.querySelector('.master-degree-select');
        const schoolWrapper = entry.querySelector('.master-school-wrapper');
        const schoolInput = entry.querySelector('.master-school-input');
        const yearWrapper = entry.querySelector('.master-year-wrapper');
        const yearInput = entry.querySelector('.master-year-select');
        if (!degreeSelect || !schoolWrapper || !schoolInput || !yearWrapper || !yearInput) return;

        updateSingleSelectColor(degreeSelect);
        if (degreeSelect.value) {
            setTimeout(() => schoolWrapper.classList.remove('year-hidden'), yearRevealDelayMs);
            setTimeout(() => yearWrapper.classList.remove('year-hidden'), yearRevealDelayMs);
        } else {
            schoolWrapper.classList.add('year-hidden');
            yearWrapper.classList.add('year-hidden');
            if (shouldResetWhenEmpty) {
                schoolInput.value = '';
                yearInput.value = '';
            }
        }
    };

    const reindexMasterEntries = () => {
        const entries = Array.from(masterDegreesContainer?.querySelectorAll('.master-degree-entry') || []);
        entries.forEach((entry, index) => {
            entry.dataset.degreeIndex = index;

            const degreeSelect = entry.querySelector('.master-degree-select');
            const schoolInput = entry.querySelector('.master-school-input');
            const yearSelect = entry.querySelector('.master-year-select');
            const degreeLabel = entry.querySelector('label[for^="master_degree_"]');
            const schoolLabel = entry.querySelector('label[for^="master_school_name_"]');
            const yearLabel = entry.querySelector('label[for^="master_year_finished_"]');
            const removeBtn = entry.querySelector('.remove-master-degree');

            if (degreeSelect) {
                degreeSelect.id = `master_degree_${index}`;
                degreeSelect.name = `master_degrees[${index}][degree]`;
            }
            if (schoolInput) {
                schoolInput.id = `master_school_name_${index}`;
                schoolInput.name = `master_degrees[${index}][school_name]`;
            }
            if (yearSelect) {
                yearSelect.id = `master_year_finished_${index}`;
                yearSelect.name = `master_degrees[${index}][year_finished]`;
            }
            if (degreeLabel) degreeLabel.setAttribute('for', `master_degree_${index}`);
            if (schoolLabel) schoolLabel.setAttribute('for', `master_school_name_${index}`);
            if (yearLabel) yearLabel.setAttribute('for', `master_year_finished_${index}`);
            if (removeBtn) removeBtn.classList.toggle('d-none', index === 0 && entries.length === 1);
        });
    };

    const bindMasterEntry = (entry, shouldResetWhenEmpty = true) => {
        if (!entry) return;
        const degreeSelect = entry.querySelector('.master-degree-select');
        const removeBtn = entry.querySelector('.remove-master-degree');

        degreeSelect?.addEventListener('change', () => toggleMasterEntryFields(entry, true));
        degreeSelect?.addEventListener('input', () => toggleMasterEntryFields(entry, false));
        removeBtn?.addEventListener('click', () => {
            const entries = masterDegreesContainer?.querySelectorAll('.master-degree-entry') || [];
            if (entries.length <= 1) return;
            entry.remove();
            reindexMasterEntries();
        });

        toggleMasterEntryFields(entry, shouldResetWhenEmpty);
    };

    const addMasterDegreeEntry = () => {
        const firstEntry = masterDegreesContainer?.querySelector('.master-degree-entry');
        if (!firstEntry || !masterDegreesContainer) return;

        const clone = firstEntry.cloneNode(true);
        clone.querySelectorAll('input, select').forEach((field) => {
            if ((field.type || '').toLowerCase() === 'select-one') {
                field.selectedIndex = 0;
            } else {
                field.value = '';
            }
        });
        clone.querySelectorAll('.year-field-transition').forEach((wrapper) => wrapper.classList.add('year-hidden'));

        masterDegreesContainer.appendChild(clone);
        reindexMasterEntries();
        bindMasterEntry(clone, true);
    };

    addMasterDegreeBtn?.addEventListener('click', addMasterDegreeEntry);
    Array.from(masterDegreesContainer?.querySelectorAll('.master-degree-entry') || []).forEach((entry) => {
        bindMasterEntry(entry, false);
    });
    reindexMasterEntries();

    const toggleDoctoralEntryFields = (entry, shouldResetWhenEmpty = true) => {
        if (!entry) return;
        const degreeSelect = entry.querySelector('.doctoral-degree-select');
        const schoolWrapper = entry.querySelector('.doctoral-school-wrapper');
        const schoolInput = entry.querySelector('.doctoral-school-input');
        const yearWrapper = entry.querySelector('.doctoral-year-wrapper');
        const yearInput = entry.querySelector('.doctoral-year-select');
        if (!degreeSelect || !schoolWrapper || !schoolInput || !yearWrapper || !yearInput) return;

        updateSingleSelectColor(degreeSelect);
        if (degreeSelect.value) {
            setTimeout(() => schoolWrapper.classList.remove('year-hidden'), yearRevealDelayMs);
            setTimeout(() => yearWrapper.classList.remove('year-hidden'), yearRevealDelayMs);
        } else {
            schoolWrapper.classList.add('year-hidden');
            yearWrapper.classList.add('year-hidden');
            if (shouldResetWhenEmpty) {
                schoolInput.value = '';
                yearInput.value = '';
            }
        }
    };

    const reindexDoctoralEntries = () => {
        const entries = Array.from(doctoralDegreesContainer?.querySelectorAll('.doctoral-degree-entry') || []);
        entries.forEach((entry, index) => {
            entry.dataset.degreeIndex = index;

            const degreeSelect = entry.querySelector('.doctoral-degree-select');
            const schoolInput = entry.querySelector('.doctoral-school-input');
            const yearSelect = entry.querySelector('.doctoral-year-select');
            const degreeLabel = entry.querySelector('label[for^="doctoral_degree_"]');
            const schoolLabel = entry.querySelector('label[for^="doctoral_school_name_"]');
            const yearLabel = entry.querySelector('label[for^="doctoral_year_finished_"]');
            const removeBtn = entry.querySelector('.remove-doctoral-degree');

            if (degreeSelect) {
                degreeSelect.id = `doctoral_degree_${index}`;
                degreeSelect.name = `doctoral_degrees[${index}][degree]`;
            }
            if (schoolInput) {
                schoolInput.id = `doctoral_school_name_${index}`;
                schoolInput.name = `doctoral_degrees[${index}][school_name]`;
            }
            if (yearSelect) {
                yearSelect.id = `doctoral_year_finished_${index}`;
                yearSelect.name = `doctoral_degrees[${index}][year_finished]`;
            }
            if (degreeLabel) degreeLabel.setAttribute('for', `doctoral_degree_${index}`);
            if (schoolLabel) schoolLabel.setAttribute('for', `doctoral_school_name_${index}`);
            if (yearLabel) yearLabel.setAttribute('for', `doctoral_year_finished_${index}`);
            if (removeBtn) removeBtn.classList.toggle('d-none', index === 0 && entries.length === 1);
        });
    };

    const bindDoctoralEntry = (entry, shouldResetWhenEmpty = true) => {
        if (!entry) return;
        const degreeSelect = entry.querySelector('.doctoral-degree-select');
        const removeBtn = entry.querySelector('.remove-doctoral-degree');

        degreeSelect?.addEventListener('change', () => toggleDoctoralEntryFields(entry, true));
        degreeSelect?.addEventListener('input', () => toggleDoctoralEntryFields(entry, false));
        removeBtn?.addEventListener('click', () => {
            const entries = doctoralDegreesContainer?.querySelectorAll('.doctoral-degree-entry') || [];
            if (entries.length <= 1) return;
            entry.remove();
            reindexDoctoralEntries();
        });

        toggleDoctoralEntryFields(entry, shouldResetWhenEmpty);
    };

    const addDoctoralDegreeEntry = () => {
        const firstEntry = doctoralDegreesContainer?.querySelector('.doctoral-degree-entry');
        if (!firstEntry || !doctoralDegreesContainer) return;

        const clone = firstEntry.cloneNode(true);
        clone.querySelectorAll('input, select').forEach((field) => {
            if ((field.type || '').toLowerCase() === 'select-one') {
                field.selectedIndex = 0;
            } else {
                field.value = '';
            }
        });
        clone.querySelectorAll('.year-field-transition').forEach((wrapper) => wrapper.classList.add('year-hidden'));

        doctoralDegreesContainer.appendChild(clone);
        reindexDoctoralEntries();
        bindDoctoralEntry(clone, true);
    };

    addDoctoralDegreeBtn?.addEventListener('click', addDoctoralDegreeEntry);
    Array.from(doctoralDegreesContainer?.querySelectorAll('.doctoral-degree-entry') || []).forEach((entry) => {
        bindDoctoralEntry(entry, false);
    });
    reindexDoctoralEntries();

    const toggleFreshGraduateFields = () => {
        if (
            !freshGraduateCheckbox
            || !workPositionInput
            || !workEmployerInput
            || !workLocationInput
            || !workDurationInput
            || !experienceYearsInput
        ) {
            return;
        }

        const isFreshGraduate = freshGraduateCheckbox.checked;
        const workFields = [workPositionInput, workEmployerInput, workLocationInput, workDurationInput];

        workFields.forEach((field) => {
            field.disabled = isFreshGraduate;
            if (isFreshGraduate) {
                field.value = '';
                clearErrorHighlight(field);
            }
        });

        if (isFreshGraduate) {
            const zeroToOneOption = Array.from(experienceYearsInput.options).find((option) => option.value.startsWith('0'));
            if (zeroToOneOption) {
                experienceYearsInput.value = zeroToOneOption.value;
            }
            // Keep it submittable while preventing edits for fresh graduates.
            experienceYearsInput.style.pointerEvents = 'none';
            experienceYearsInput.tabIndex = -1;
            experienceYearsInput.setAttribute('aria-disabled', 'true');
            experienceYearsInput.classList.remove('text-secondary');
            experienceYearsInput.classList.add('text-dark');
            clearErrorHighlight(experienceYearsInput);
        } else {
            experienceYearsInput.style.pointerEvents = '';
            experienceYearsInput.tabIndex = 0;
            experienceYearsInput.removeAttribute('aria-disabled');
            if (!experienceYearsInput.value) {
                experienceYearsInput.classList.remove('text-dark');
                experienceYearsInput.classList.add('text-secondary');
            }
        }
    };

    freshGraduateCheckbox?.addEventListener('change', toggleFreshGraduateFields);
    toggleFreshGraduateFields();

});

</script>








@endsection
