@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


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
        <select class="form-select" id="education" name="education" required>
            <option value="" disabled selected></option>
            <option value="High School">High School</option>
            <option value="Associate Degree">Associate Degree</option>
            <option value="Bachelor's Degree">Bachelor's Degree</option>
            <option value="Master's Degree">Master's Degree</option>
            <option value="Master's Degree">Master of Science (MSc)</option>
            <option value="Master's Degree">Master of Arts (MA)</option>
            <option value="Doctorate">Doctor of Philosophy (PhD)</option>
        </select>
        <label for="education">Highest Educational Attainment<span class="required-asterisk"> *</span></label>
    </div>

    <div class="mb-3 floating-input">
        <select class="form-select" id="field_study" name="field_study" required>
            <option value="" disabled selected></option>
            <option value="Computer Science">Computer Science</option>
            <option value="Business">Business</option>
            <option value="Engineering">Engineering</option>
            <option value="Education">Education</option>
            <option value="Health Sciences">Health Sciences</option>
        </select>
        <label for="field_study">Field of Study<span class="required-asterisk"> *</span></label>
    </div>

    <div class="mb-3 floating-input">
        <input type="text" class="form-select" id="university_name" name="university_name" placeholder=" " required>
        <label for="university_name">University Name<span class="required-asterisk"> *</span></label>
    </div>

    <div class="mb-3 floating-input">
        <input type="text" class="form-select" id="university_address" name="university_address" placeholder=" " required>
        <label for="university_address">Address<span class="required-asterisk"> *</span></label>
    </div>

    <div class="mb-3 floating-input">
        <input type="text" class="form-select" id="year_complete" name="year_complete" placeholder=" " required>
        <label for="year_complete">Year Complete<span class="required-asterisk"> *</span></label>
    </div>

    <h4 class="fw-bold mb-3 mt-4">Work Experience</h4>

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
                Highest Educational Attainment:
                <span id="review-education" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Field of Study:
                <span id="review-field-study" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                University:
                <span id="uni" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Address:
                <span id="uni_add" class="d-block text-uppercase text-secondary fw-semibold"></span>
            </p>

            <p class="text-uppercase fw-semibold">
                Year Complete:
                <span id="year_com" class="d-block text-uppercase text-secondary fw-semibold"></span>
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
    submitButton.disabled = true;

    certifyCheckbox.addEventListener('change', () => {
        submitButton.disabled = !certifyCheckbox.checked;
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

        document.getElementById('review-education').textContent = document.getElementById('education').value;
        document.getElementById('review-field-study').textContent = document.getElementById('field_study').value;
        document.getElementById('review-experience-years').textContent = document.getElementById('experience_years').value;
        document.getElementById('review-key-skills').textContent = document.getElementById('key_skills').value;

        document.getElementById('uni').textContent = document.getElementById('university_name').value;
        document.getElementById('uni_add').textContent = document.getElementById('university_address').value;
        document.getElementById('year_com').textContent = document.getElementById('year_complete').value;
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

});

</script>








@endsection
