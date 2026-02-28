@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
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

<main class="container my-5">

    {{-- step 1 --}}
    <div class="card shadow-sm mb-4 animated-card delay-5 hover-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="mb-1">Apply for {{ $openPosition->title}}</h2>
                    <h6 class="text-secondary mb-1">Please fill out all fields to  complete your application</h6>
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

    <div class="d-flex justify-content-end mt-auto">
        <div></div>
        <button type="button" id="btnToExperience" class="btn btn-primary">Proceed</button>
    </div>
</div>



    <!-- Work Experience & Education Form -->
<div id="experienceForm" class="mt-4 d-none form-step">

    <h4 class="fw-bold mb-3">Educational Background</h4>

    <div class="mb-3 floating-input">
        <select class="form-select text-secondary" id="bachelor_degree" name="bachelor_degree" required>
            <option value="" disabled selected style="color: #6c757d;">Select Bachelor Degree</option>
            <option value="Nursing">Nursing</option>
            <option value="Medicine">Medicine</option>
            <option value="Pharmacy">Pharmacy</option>
            <option value="Public Health">Public Health</option>
            <option value="Physical Therapy">Physical Therapy</option>
            <option value="Computer Science">Computer Science</option>
            <option value="Information Technology">Information Technology</option>
            <option value="Cybersecurity">Cybersecurity</option>
            <option value="Software Engineering">Software Engineering</option>
            <option value="Data Science">Data Science</option>
            <option value="Civil Engineering">Civil Engineering</option>
            <option value="Mechanical Engineering">Mechanical Engineering</option>
            <option value="Electrical Engineering">Electrical Engineering</option>
            <option value="Chemical Engineering">Chemical Engineering</option>
            <option value="Accounting">Accounting</option>
            <option value="Marketing">Marketing</option>
            <option value="Finance">Finance</option>
            <option value="Business Administration">Business Administration</option>
            <option value="Human Resource Management">Human Resource Management</option>
            <option value="Law">Law</option>
            <option value="Political Science">Political Science</option>
            <option value="Public Administration">Public Administration</option>
            <option value="Criminology">Criminology</option>
            <option value="Elementary Education">Elementary Education</option>
            <option value="Secondary Education">Secondary Education</option>
            <option value="Special Education">Special Education</option>
            <option value="Fine Arts">Fine Arts</option>
            <option value="Music">Music</option>
            <option value="Literature">Literature</option>
            <option value="History">History</option>
            <option value="Graphic Design">Graphic Design</option>
            <option value="Biology">Biology</option>
            <option value="Chemistry">Chemistry</option>
            <option value="Physics">Physics</option>
            <option value="Mathematics">Mathematics</option>
        </select>
        <label for="bachelor_degree">Bachelor Degree<span class="required-asterisk"> *</span></label>
    </div>

    <div class="mb-3 floating-input year-field-transition year-hidden" id="bachelor-school-wrapper">
        <input type="text" class="form-select" id="bachelor_school_name" name="bachelor_school_name" placeholder=" ">
        <label for="bachelor_school_name">Bachelor School Name</label>
    </div>

    <div class="mb-3 floating-input year-field-transition year-hidden" id="bachelor-year-wrapper">
        <select class="form-select text-secondary" id="bachelor_year_finished" name="bachelor_year_finished">
            <option value="" selected style="color: #6c757d;">Select Year Finished</option>
            @for ($year = 2026; $year >= 1900; $year--)
                <option value="{{ $year }}">{{ $year }}</option>
            @endfor
        </select>
        <label for="bachelor_year_finished">Bachelor Year Finished</label>
    </div>

    <div class="mb-3 floating-input">
        <select class="form-select text-secondary" id="master_degree" name="master_degree">
            <option value="" selected style="color: #6c757d;">Select Master Degree</option>
            <option value="MA (Master of Arts)">MA (Master of Arts)</option>
            <option value="MSc/MS (Master of Science)">MSc/MS (Master of Science)</option>
            <option value="MSN (Master of Science in Nursing)">MSN (Master of Science in Nursing)</option>
            <option value="MBA (Master of Business Administration)">MBA (Master of Business Administration)</option>
            <option value="MEd (Master of Education)">MEd (Master of Education)</option>
            <option value="MFA (Master of Fine Arts)">MFA (Master of Fine Arts)</option>
            <option value="LLM (Master of Laws)">LLM (Master of Laws)</option>
            <option value="MSW (Master of Social Work)">MSW (Master of Social Work)</option>
            <option value="MPH (Master of Public Health)">MPH (Master of Public Health)</option>
            <option value="MEng (Master of Engineering)">MEng (Master of Engineering)</option>
            <option value="MRes (Master of Research)">MRes (Master of Research)</option>
            <option value="MPhil (Master of Philosophy)">MPhil (Master of Philosophy)</option>
            <option value="MSt (Master of Studies)">MSt (Master of Studies)</option>
            <option value="MTech (Master of Technology)">MTech (Master of Technology)</option>
            <option value="MSIT (Master of Science in Information Technology)">MSIT (Master of Science in Information Technology)</option>
            <option value="MCA (Master of Computer Applications)">MCA (Master of Computer Applications)</option>
            <option value="MVSc (Master of Veterinary Science)">MVSc (Master of Veterinary Science)</option>
            <option value="MArch (Master of Architecture)">MArch (Master of Architecture)</option>
            <option value="MPA (Master of Public Administration)">MPA (Master of Public Administration)</option>
        </select>
        <label for="master_degree">Master Degree</label>
    </div>

    <div class="mb-3 floating-input year-field-transition year-hidden" id="master-school-wrapper">
        <input type="text" class="form-select" id="master_school_name" name="master_school_name" placeholder=" ">
        <label for="master_school_name">University Name</label>
    </div>

    <div class="mb-3 floating-input year-field-transition year-hidden" id="master-year-wrapper">
        <select class="form-select text-secondary" id="master_year_finished" name="master_year_finished">
            <option value="" selected style="color: #6c757d;">Select Year Finished</option>
            @for ($year = 2026; $year >= 1900; $year--)
                <option value="{{ $year }}">{{ $year }}</option>
            @endfor
        </select>
        <label for="master_year_finished">Master Year Finished</label>
    </div>

    <div class="mb-3 floating-input">
        <select class="form-select text-secondary" id="doctoral_degree" name="doctoral_degree">
            <option value="" selected style="color: #6c757d;">Select Doctoral Degree</option>
            <option value="PhD (Doctor of Philosophy)">PhD (Doctor of Philosophy)</option>
            <option value="EdD (Doctor of Education)">EdD (Doctor of Education)</option>
            <option value="DMA (Doctor of Musical Arts)">DMA (Doctor of Musical Arts)</option>
            <option value="ThD (Doctor of Theology)">ThD (Doctor of Theology)</option>
            <option value="DSc/ScD (Doctor of Science)">DSc/ScD (Doctor of Science)</option>
            <option value="DA (Doctor of Arts)">DA (Doctor of Arts)</option>
            <option value="DBA (Doctor of Business Administration)">DBA (Doctor of Business Administration)</option>
            <option value="MD (Doctor of Medicine)">MD (Doctor of Medicine)</option>
            <option value="DO (Doctor of Osteopathic Medicine)">DO (Doctor of Osteopathic Medicine)</option>
            <option value="DDS (Doctor of Dental Surgery)">DDS (Doctor of Dental Surgery)</option>
            <option value="DNP/DNSc (Doctor of Nursing Practice)">DNP/DNSc (Doctor of Nursing Practice)</option>
            <option value="PharmD (Doctor of Pharmacy)">PharmD (Doctor of Pharmacy)</option>
            <option value="DPM (Doctor of Podiatric Medicine)">DPM (Doctor of Podiatric Medicine)</option>
            <option value="DPT (Doctor of Physical Therapy)">DPT (Doctor of Physical Therapy)</option>
            <option value="JD (Juris Doctor (JD)">JD (Juris Doctor (JD)</option>
            <option value="JSD/SJD (Doctor of Juridical Science)">JSD/SJD (Doctor of Juridical Science)</option>
            <option value="JCD (Doctor of Canon Law)">JCD (Doctor of Canon Law)</option>
            <option value="PsyD (Doctor of Psychology)">PsyD (Doctor of Psychology)</option>
            <option value="DPA (Doctor of Public Administration)">DPA (Doctor of Public Administration)</option>
            <option value="DDes (Doctor of Design)">DDes (Doctor of Design)</option>
            <option value="DFA (Doctor of Fine Arts)">DFA (Doctor of Fine Arts)</option>
            <option value="DBH (Doctor of Behavioral Health)">DBH (Doctor of Behavioral Health)</option>
            <option value="DCJ (Doctor of Criminal Justice)">DCJ (Doctor of Criminal Justice)</option>
            <option value="DIT (Doctor of Information Technology)">DIT (Doctor of Information Technology)</option>
            <option value="DSW (Doctor of Social Work)">DSW (Doctor of Social Work)</option>
            <option value="DArch (Doctor of Architecture)">DArch (Doctor of Architecture)</option>
            <option value="DPS (Doctor of Professional Studies)">DPS (Doctor of Professional Studies)</option>
            <option value="DSus (Doctor of Sustainability)">DSus (Doctor of Sustainability)</option>
        </select>
        <label for="doctoral_degree">Doctoral Degree</label>
    </div>

    <div class="mb-3 floating-input year-field-transition year-hidden" id="doctoral-school-wrapper">
        <input type="text" class="form-select" id="doctoral_school_name" name="doctoral_school_name" placeholder=" ">
        <label for="doctoral_school_name">University Name</label>
    </div>

    <div class="mb-3 floating-input year-field-transition year-hidden" id="doctoral-year-wrapper">
        <select class="form-select text-secondary" id="doctoral_year_finished" name="doctoral_year_finished">
            <option value="" selected style="color: #6c757d;">Select Year Finished</option>
            @for ($year = 2026; $year >= 1900; $year--)
                <option value="{{ $year }}">{{ $year }}</option>
            @endfor
        </select>
        <label for="doctoral_year_finished">Doctoral Year Finished</label>
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

    <div class="d-flex justify-content-between mt-auto">
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
            <input type="file" id="diploma" name="documents[4][file]" accept=".pdf,.doc,.docx" required>
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
            <input type="file" id="board_rating" name="documents[5][file]" accept=".pdf,.doc,.docx" required>
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
            <input type="file" id="certification_eligibility" name="documents[6][file]" accept=".pdf,.doc,.docx" required>
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
            <input type="file" id="membership_affiliation" name="documents[8][file]" accept=".pdf,.doc,.docx" required>
            <input type="hidden" name="documents[8][type]" value="Membership/Affiliation">
        </label>
    </div>

    <div class="d-flex justify-content-between">
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
                Bachelor Degree:
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




        <div class="d-flex justify-content-between">
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

    function setStep(stepNumber) {
        steps.forEach((step, index) => {
            step.classList.remove('active', 'completed1');
            if (index + 1 < stepNumber) step.classList.add('completed1');
            else if (index + 1 === stepNumber) step.classList.add('active');
        });

        lines.forEach((line, index) => {
            line.classList.toggle('completed1', index < stepNumber - 1);
        });
    }

    setStep(1); // Initial step

    /* =======================
       CERTIFICATION CHECKBOX
    ======================= */
    const certifyCheckbox = document.getElementById('certifyCheckbox');
    const submitButton = reviewForm.querySelector('button[type="submit"]');
    const applicationForm = document.getElementById('formPersonal');
    submitButton.disabled = true;

    certifyCheckbox.addEventListener('change', () => {
        submitButton.disabled = !certifyCheckbox.checked;
    });

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
            field.addEventListener('input', () => clearErrorHighlight(field));
            field.addEventListener('change', () => clearErrorHighlight(field));
        });

        applicationForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const requiredFields = Array.from(applicationForm.querySelectorAll('[required]'))
                .filter((field) => !field.disabled);

            const invalidFields = requiredFields.filter((field) => {
                const type = (field.type || '').toLowerCase();
                if (type === 'file') return !(field.files && field.files.length > 0);
                if (type === 'checkbox') return !field.checked;
                return !field.checkValidity();
            });

            if (!invalidFields.length) {
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

        const bachelorDegree = (document.getElementById('bachelor_degree')?.value || '').trim();
        const bachelorSchool = (document.getElementById('bachelor_school_name')?.value || '').trim();
        const bachelorYear = (document.getElementById('bachelor_year_finished')?.value || '').trim();
        const masterDegree = (document.getElementById('master_degree')?.value || '').trim();
        const masterSchool = (document.getElementById('master_school_name')?.value || '').trim();
        const masterYear = (document.getElementById('master_year_finished')?.value || '').trim();
        const doctoralDegree = (document.getElementById('doctoral_degree')?.value || '').trim();
        const doctoralSchool = (document.getElementById('doctoral_school_name')?.value || '').trim();
        const doctoralYear = (document.getElementById('doctoral_year_finished')?.value || '').trim();

        const formatDegreeReview = (degree, school, year) => {
            if (!degree) return 'N/A';
            const schoolLabel = school || 'School not provided';
            const yearLabel = year || 'Year not provided';
            return `${degree} - ${schoolLabel} (${yearLabel})`;
        };

        document.getElementById('review-bachelor-degree').textContent =
            formatDegreeReview(bachelorDegree, bachelorSchool, bachelorYear);
        document.getElementById('review-master-degree').textContent =
            formatDegreeReview(masterDegree, masterSchool, masterYear);
        document.getElementById('review-doctoral-degree').textContent =
            formatDegreeReview(doctoralDegree, doctoralSchool, doctoralYear);
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

    const degreeSelectIds = ['bachelor_degree', 'master_degree', 'doctoral_degree'];
    degreeSelectIds.forEach((id) => {
        const select = document.getElementById(id);
        if (!select) return;

        const updateSelectColor = () => {
            if (select.value) {
                select.classList.remove('text-secondary');
                select.classList.add('text-dark');
            } else {
                select.classList.remove('text-dark');
                select.classList.add('text-secondary');
            }
        };

        select.addEventListener('change', updateSelectColor);
        updateSelectColor();
    });

    const bachelorDegreeSelect = document.getElementById('bachelor_degree');
    const bachelorSchoolWrapper = document.getElementById('bachelor-school-wrapper');
    const bachelorSchoolInput = document.getElementById('bachelor_school_name');
    const bachelorYearWrapper = document.getElementById('bachelor-year-wrapper');
    const bachelorYearInput = document.getElementById('bachelor_year_finished');
    const masterDegreeSelect = document.getElementById('master_degree');
    const masterSchoolWrapper = document.getElementById('master-school-wrapper');
    const masterSchoolInput = document.getElementById('master_school_name');
    const masterYearWrapper = document.getElementById('master-year-wrapper');
    const masterYearInput = document.getElementById('master_year_finished');
    const doctoralDegreeSelect = document.getElementById('doctoral_degree');
    const doctoralSchoolWrapper = document.getElementById('doctoral-school-wrapper');
    const doctoralSchoolInput = document.getElementById('doctoral_school_name');
    const doctoralYearWrapper = document.getElementById('doctoral-year-wrapper');
    const doctoralYearInput = document.getElementById('doctoral_year_finished');
    const yearRevealDelayMs = 140;
    const freshGraduateCheckbox = document.getElementById('fresh_graduate');
    const workPositionInput = document.getElementById('work_position');
    const workEmployerInput = document.getElementById('work_employer');
    const workLocationInput = document.getElementById('work_location');
    const workDurationInput = document.getElementById('work_duration');
    const experienceYearsInput = document.getElementById('experience_years');

    const toggleBachelorYearField = () => {
        if (!bachelorDegreeSelect || !bachelorSchoolWrapper || !bachelorSchoolInput || !bachelorYearWrapper || !bachelorYearInput) return;

        if (bachelorDegreeSelect.value) {
            setTimeout(() => bachelorSchoolWrapper.classList.remove('year-hidden'), yearRevealDelayMs);
            setTimeout(() => bachelorYearWrapper.classList.remove('year-hidden'), yearRevealDelayMs);
            bachelorSchoolInput.setAttribute('required', 'required');
            bachelorYearInput.setAttribute('required', 'required');
        } else {
            bachelorSchoolWrapper.classList.add('year-hidden');
            bachelorSchoolInput.removeAttribute('required');
            bachelorSchoolInput.value = '';
            bachelorYearWrapper.classList.add('year-hidden');
            bachelorYearInput.removeAttribute('required');
            bachelorYearInput.value = '';
        }
    };

    bachelorDegreeSelect?.addEventListener('change', toggleBachelorYearField);
    toggleBachelorYearField();

    const toggleMasterYearField = () => {
        if (!masterDegreeSelect || !masterSchoolWrapper || !masterSchoolInput || !masterYearWrapper || !masterYearInput) return;

        if (masterDegreeSelect.value) {
            setTimeout(() => masterSchoolWrapper.classList.remove('year-hidden'), yearRevealDelayMs);
            setTimeout(() => masterYearWrapper.classList.remove('year-hidden'), yearRevealDelayMs);
            masterSchoolInput.setAttribute('required', 'required');
            masterYearInput.setAttribute('required', 'required');
        } else {
            masterSchoolWrapper.classList.add('year-hidden');
            masterSchoolInput.removeAttribute('required');
            masterSchoolInput.value = '';
            masterYearWrapper.classList.add('year-hidden');
            masterYearInput.removeAttribute('required');
            masterYearInput.value = '';
        }
    };

    masterDegreeSelect?.addEventListener('change', toggleMasterYearField);
    toggleMasterYearField();

    const toggleDoctoralYearField = () => {
        if (!doctoralDegreeSelect || !doctoralSchoolWrapper || !doctoralSchoolInput || !doctoralYearWrapper || !doctoralYearInput) return;

        if (doctoralDegreeSelect.value) {
            setTimeout(() => doctoralSchoolWrapper.classList.remove('year-hidden'), yearRevealDelayMs);
            setTimeout(() => doctoralYearWrapper.classList.remove('year-hidden'), yearRevealDelayMs);
            doctoralSchoolInput.setAttribute('required', 'required');
            doctoralYearInput.setAttribute('required', 'required');
        } else {
            doctoralSchoolWrapper.classList.add('year-hidden');
            doctoralSchoolInput.removeAttribute('required');
            doctoralSchoolInput.value = '';
            doctoralYearWrapper.classList.add('year-hidden');
            doctoralYearInput.removeAttribute('required');
            doctoralYearInput.value = '';
        }
    };

    doctoralDegreeSelect?.addEventListener('change', toggleDoctoralYearField);
    toggleDoctoralYearField();

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
            experienceYearsInput.disabled = true;
            experienceYearsInput.classList.remove('text-secondary');
            experienceYearsInput.classList.add('text-dark');
            clearErrorHighlight(experienceYearsInput);
        } else {
            experienceYearsInput.disabled = false;
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
