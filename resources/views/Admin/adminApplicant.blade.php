<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PeopleHub – HR Dashboard</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif; transition: margin-left 0.3s ease; }
    main { transition: margin-left 0.3s ease; }
    aside ~ main { margin-left: 16rem; }
  </style>
</head>
<body class="bg-slate-100">

<div class="flex min-h-screen">

  <!-- Sidebar -->
    @include('components.adminSideBar')


  <!-- Main Content -->
  <main class="flex-1 ml-16 transition-all duration-300">

    <!-- Header -->
     @include('components.adminHeader.applicantHeader')

    <!-- Dashboard Content -->
    <div class="p-4 md:p-8 space-y-6 pt-20">

    <!-- ===================== STATS ===================== -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <!-- Card -->
        <div class="bg-white rounded-xl p-5 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-3xl font-bold text-gray-900">{{$count_applicant}}</p>
                <p class="text-sm text-gray-500">Total Applicants</p>
            </div>
            <div class="text-right">
                <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mb-2">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full">+12%</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-3xl font-bold text-gray-900">{{$count_under_review}}</p>
                <p class="text-sm text-gray-500">Under Review</p>
            </div>
            <div class="text-right">
                <div class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center mb-2">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <span class="text-xs bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full">Pending</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-3xl font-bold text-gray-900">{{$count_final_interview}}</p>
                <p class="text-sm text-gray-500">Interviews Scheduled</p>
            </div>
            <div class="text-right">
                <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-2">
                    <i class="fa-regular fa-calendar"></i>
                </div>
                <span class="text-xs bg-indigo-100 text-indigo-600 px-2 py-1 rounded-full">This Week</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-3xl font-bold text-gray-900">{{$hired}}</p>
                <p class="text-sm text-gray-500">Hired This Month</p>
            </div>
            <div class="text-right">
                <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-2">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full">+8%</span>
            </div>
        </div>

    </div>

    <!-- ===================== TABLE ===================== -->
    <div class="bg-white rounded-xl shadow-sm p-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-lg text-gray-800">Recent Applicants</h2>

            <div class="flex gap-2">
                <select class="border rounded-lg px-3 py-1 text-sm">
                    <option>All Positions</option>
                </select>
                <select class="border rounded-lg px-3 py-1 text-sm">
                    <option>All Status</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-sm">
            <thead class="text-left text-gray-400 border-b">
            <tr>
                <th class="py-3">APPLICANT</th>
                <th>POSITION</th>
                <th>APPLIED DATE</th>
                <th>STATUS</th>
                <th>RATING</th>
                <th>ACTIONS</th>
            </tr>
            </thead>

            <tbody class="divide-y" id="applicantsTableBody">
            @foreach($applicant as $app)
            <!-- Row -->
             <input type="hidden" id="applicant_id" name="applicant_id" value="{{$app->id}}">

            <tr class="hover:bg-slate-50">
                <td class="py-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-sky-500 text-white rounded-full flex items-center justify-center">SM</div>
                    <div>
                        <p class="font-medium">{{$app->first_name}} {{$app->last_name}}</p>
                        <p class="text-xs text-gray-400">{{$app->email}}</p>
                    </div>
                </td>
                <td>
                    <p class="font-medium">{{$app->position->title}}</p>
                    <p class="text-xs text-gray-400">{{$app->collage_name}}</p>
                </td>
                <td>{{$app->created_at->format('F d, Y')}}</td>
                @php
                    $statusStyles = [
                        'pending' => 'background-color: rgba(255, 193, 7, 0.3); color: #ff9307;', // yellow
                        'Initial Interview' => 'background-color: rgba(13, 110, 253, 0.2); color: #0d6efd;', // blue
                        'Final Interview' => 'background-color: rgba(111, 66, 193, 0.2); color: #6f42c1;', // purple
                        'Completed' => 'background-color: rgba(25, 135, 84, 0.2); color: #198754;', // green
                        'Hired' => 'background-color: rgba(25, 135, 84, 0.2); color: #198754;',  // green
                        'Rejected' => 'background-color: rgba(220, 53, 69, 0.2); color: #dc3545;', // red
                        'default' => 'background-color: rgba(13, 110, 253, 0.2); color: #0d6efd;' // blue
                    ];

                    $badgeStyle = $statusStyles[$app->application_status] ?? $statusStyles['default'];
                @endphp

                <td>
                    <span class="px-3 py-1 text-xs rounded-full" style="{{ $badgeStyle }}">
                        {{ $app->application_status }}
                    </span>
                </td>
                <td>
                    @for ($i = 0; $i < 5; $i++)
                        @if ($i < $app->starRatings)
                            <span class="text-yellow-400">&#9733;</span>
                        @else
                            <span class="text-gray-300">&#9733;</span>
                        @endif
                    @endfor
                </td>
              <td class="text-gray-400 space-x-3">
                <!-- 👁 OPEN MODAL -->
                <i class="fa-regular fa-eye cursor-pointer hover:text-indigo-600"
                   onclick="openApplicantModal({{ $app->id }})"></i>
                <i class="fa-regular fa-calendar cursor-pointer hover:text-indigo-600"
                onclick="openScheduleModal({{ $app->id }})"></i>

                <i class="fa-solid fa-xmark cursor-pointer"></i>
              </td>
            </tr>
            @endforeach
            </tbody>
        </table>

   <!-- Pagination Controls -->
<div class="mt-4 flex justify-end items-center gap-2" id="paginationControls"></div>


    </div>


    </div>
  </main>
</div>
<!-- ================= EXACT APPLICANT PROFILE MODAL ================= -->
<div id="applicantModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center">

  <div class="bg-white w-full max-w-5xl rounded-xl shadow-2xl relative">

    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b">
      <h2 class="font-semibold text-lg">Applicant Profile</h2>
      <button onclick="closeApplicantModal()" class="text-gray-400 hover:text-gray-600 text-xl">
        &times;
      </button>
    </div>

    <!-- Body -->
    <div class="max-h-[82vh] overflow-y-auto p-6 grid grid-cols-3 gap-6">

      <!-- ================= LEFT COLUMN ================= -->
      <div class="col-span-2 space-y-6">

        <!-- Profile Header -->
        <div class="flex items-start gap-4">

          <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-sky-400 to-blue-600
                      text-white flex items-center justify-center text-xl font-bold">
            SM
          </div>

          <div class="flex-1">
            <h3 class="text-xl font-semibold" id="name"></h3>
            <p class="text-sm text-gray-400" id="email"></p>

            <div class="flex flex-wrap gap-2 mt-2">
              <span class="px-3 py-1 text-xs rounded-full bg-indigo-100 text-indigo-600" id="title">

              </span>
              <span class="px-3 py-1 text-xs rounded-full bg-indigo-50 text-indigo-500" id="status">

              </span>
            </div>

            <div class="flex gap-4 mt-2 text-sm text-gray-400">
              <span>
                <i class="fa-regular fa-calendar mr-1"></i>
                Applied: <p id="one"></p>
              </span>
              <span>
                <i class="fa-solid fa-location-dot mr-1"></i>
                <p id="location"></p>
              </span>
            </div>
          </div>

          <div class="flex gap-2 items-center">
            <button
              onclick="scheduleInterview()"
              class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium"
            >
              Schedule Interview
            </button>
            <form action="{{ route('admin.updateStatus')}}" id="updateStatus"  method="POST">
                @csrf
                <input type="hidden" name="reviewId" id="statusId">
                <select name ="status"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                onchange="confirmStatusChange(this)"
                >
                <option value="-- Choose Option --">-- Choose Option --</option>
                <option value="Under Review">Under Review</option>
                <option value="Initial Interview">Initial Interview</option>
                <option value="Final Interview">Final Interview</option>
                <option value="Hired">Hired</option>
                <option value="Rejected">Rejected</option>
                <option value="Passing Document">Passing Document</option>
                <option value="Completed">Completed</option>
                </select>
            </form>
          </div>


        </div>

        <!-- Professional Summary -->
        <div class="bg-slate-50 rounded-xl p-5">
          <h4 class="font-semibold mb-2 flex items-center gap-2">
            <i class="fa-regular fa-user text-indigo-500"></i>
            Professional Summary
          </h4>
          <p class="text-sm text-gray-600 leading-relaxed" id="passionate">

          </p>
        </div>

        <!-- Work Experience -->
        <div class="bg-white border rounded-xl p-5 space-y-4">
          <h4 class="font-semibold flex items-center gap-2">
            <i class="fa-solid fa-briefcase text-indigo-500"></i>
            Work Experience
          </h4>

          <div class="flex gap-4">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
              <i class="fa-solid fa-code text-indigo-600"></i>
            </div>
            <div>
              <p class="font-medium" id="work_info"></p>
              <p class="text-sm text-gray-600 mt-1">
                Led development of customer portal serving 100K+ users.
                Reduced development time by 40%.
              </p>
            </div>
          </div>
        </div>

        <!-- Education -->
        <div class="bg-white border rounded-xl p-5">
          <h4 class="font-semibold flex items-center gap-2 mb-2">
            <i class="fa-solid fa-graduation-cap text-indigo-500"></i>
            Education
          </h4>
          <p class="font-medium" id="university_info"></p>
        </div>

      </div>

      <!-- ================= RIGHT COLUMN ================= -->
      <div class="space-y-6">

        <!-- Skills -->
        <div class="bg-white border rounded-xl p-5">
          <h4 class="font-semibold mb-3">Skills</h4>
          <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 text-xs rounded-full bg-indigo-50 text-indigo-600" id="skills"></span>
          </div>
        </div>

        <!-- Contact -->
        <div class="bg-white border rounded-xl p-5 space-y-2">
          <h4 class="font-semibold mb-2">Contact Information</h4>
          <p class="text-sm text-gray-600">
            <i class="fa-regular fa-envelope mr-2"></i> <p id="contact_email"></p>
          </p>
          <p class="text-sm text-gray-600">
            <i class="fa-solid fa-phone mr-2"></i> <p id="number"></p>
          </p>
        </div>

        <!-- Documents -->
        <div class="bg-white border rounded-xl p-5">
          <h4 class="font-semibold mb-3">Documents</h4>

          <div id="documents" class="space-y-3"></div>
        </div>
        <form action="{{ route('admin.adminStarStore') }}" method="POST" id="starRatings">
            @csrf
            <input type="hidden" name="ratingId" id="ratingStarId">
            <input type="hidden" name="rating" id="ratingValue">
        </form>
        <!-- Rating Container -->
        <div class="bg-white border rounded-xl p-4 flex items-center justify-between shadow-sm mt-4">
            <div>
                <p class="text-sm font-medium text-gray-700">Applicant Rating</p>

                <div class="text-yellow-400 flex gap-1 text-lg mt-1 cursor-pointer" id="ratingStars">
                    <i class="fa-regular fa-star star" data-value="1"></i>
                    <i class="fa-regular fa-star star" data-value="2"></i>
                    <i class="fa-regular fa-star star" data-value="3"></i>
                    <i class="fa-regular fa-star star" data-value="4"></i>
                    <i class="fa-regular fa-star star" data-value="5"></i>
                </div>
            </div>

            <div class="text-sm text-gray-500 font-medium" id="ratingText">
                0 / 5
            </div>
            <input type="hidden" id="ratingStarId" value="">
        </div>


      </div>
    </div>
  </div>
</div>

<!-- Add this at the end of your body, after the applicant profile modal -->

<!-- ===================== SCHEDULE INTERVIEW MODAL ===================== -->
<div id="scheduleInterviewModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center">

  <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden">

    <!-- Header -->
    <div class="bg-purple-600 px-6 py-4 flex justify-between items-center">
      <h2 class="text-white font-semibold text-lg">Schedule Interview</h2>
      <button onclick="closeScheduleModal()" class="text-white text-xl hover:text-gray-200">&times;</button>
    </div>

    <!-- Body -->
    <div class="p-6 space-y-4">

      <!-- Applicant Info -->
      <div class="flex items-center gap-4 bg-purple-50 p-4 rounded-lg">
        <div class="w-12 h-12 bg-blue-400 text-white rounded-full flex items-center justify-center font-bold">SM</div>
        <div>
          <p class="font-medium text-gray-800" id ="names"></p>
          <p class="text-sm text-gray-500" id ="titles"></p>
        </div>
      </div>

      <!-- Form -->
      <form class="space-y-4" action = "{{ route('admin.storeNewInterview') }}" method="POST" id="form">
        @csrf
        <!-- Interview Type -->
         <input type="hidden" id="applicants_id" name="applicants_id">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Interview Type</label>
          <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
          name = "interview_type">
            <option value="HR Interview">HR Interview</option>
            <option value="Final Interview">Final Interview</option>
          </select>
        </div>

        <!-- Date & Time -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
            name="date">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
            <input type="time" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
            name="time">
          </div>
        </div>

        <!-- Duration -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
          <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
          name="duration">
            <option value="5 minutes">5 minutes</option>
            <option value="30 minutes">30 minutes</option>
            <option value="45 minutes">45 minutes</option>
            <option value="60 minutes">60 minutes</option>
            <option value="90 minutes">90 minutes</option>
          </select>
        </div>

        <!-- Interviewers -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Interviewer(s)</label>
          <input type="text" placeholder="Enter interviewer name(s)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
          name="interviewers">
        </div>

        <!-- Email Link -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email Link</label>
          <input type="email" placeholder="Enter Email Address: " class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
          name="email_link">
        </div>

        <!-- Meeting Link -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Link (Optional)</label>
          <input type="url" placeholder="https://meet.google.com/..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500"
          name="url">
        </div>

        <!-- Notes -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
          <textarea placeholder="Add any additional notes or instructions..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500 h-24 resize-none"
          name="notes"></textarea>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-3 mt-2">
          <button type="button" onclick="closeScheduleModal()" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700">Schedule Interview</button>
        </div>

      </form>

    </div>

  </div>
</div>







</body>
<script>
  // Open/Close Schedule Interview Modal
  function openScheduleModal(appId) {

    if (!appId) {
        alert('No applicant selected');
        return;
    }
    document.getElementById('applicants_id').value = appId;
    fetch(`/system/applicants/ID/${appId}`)
        .then(res => res.json())
        .then(data => {
        // Basic applicant info
        document.getElementById('names').innerText = data.name;
        document.getElementById('titles').innerText = data.title;
        });

    document.getElementById('scheduleInterviewModal').classList.remove('hidden');
  }

  function scheduleInterview() {
    const appId = document.getElementById('applicant_id').value;

    if (!appId) {
        alert('Please select an applicant first.');
        return;
    }
    document.getElementById('applicants_id').value = appId;

    fetch(`/system/applicants/ID/${appId}`)
        .then(res => res.json())
        .then(data => {
        // Basic applicant info
        document.getElementById('names').innerText = data.name;
        document.getElementById('titles').innerText = data.title;
        });

    document.getElementById('scheduleInterviewModal').classList.remove('hidden');
    }


  function closeScheduleModal() {
    document.getElementById('scheduleInterviewModal').classList.add('hidden');
  }

  // Open/Close Applicant Modal (existing)
  function openApplicantModal(applicantId) {
    document.getElementById('statusId').value = applicantId;
    document.getElementById('ratingStarId').value = applicantId;
    console.log(document.getElementById('statusId').value);
    fetch(`/system/applicants/ID/${applicantId}`)
        .then(res => res.json())
        .then(data => {
        // Basic applicant info
        document.getElementById('name').innerText = data.name;
        document.getElementById('email').innerText = data.email;
        document.getElementById('contact_email').innerText = data.email;
        document.getElementById('title').innerText = data.title;
        document.getElementById('status').innerText = data.status;
        document.getElementById('location').innerText = data.location;
        document.getElementById('one').innerText = data.one;
        document.getElementById('passionate').innerText = data.passionate;
        document.getElementById('skills').innerText = data.skills;
        document.getElementById('number').innerText = data.number;

        const workInfo = [
            data.work_position,
            data.work_employer,
            data.work_location,
            data.work_duration
        ].filter(Boolean).join(' • ');

        const universityInfo = [
            data.university_name,
            data.university_address,
            data.university_year
        ].filter(Boolean).join(' • ');

        document.getElementById('work_info').innerText = workInfo;
        document.getElementById('university_info').innerText = universityInfo;
        // ✅ Documents
        const docsContainer = document.getElementById('documents');
        docsContainer.innerHTML = '';

        if (data.documents && data.documents.length > 0) {
            data.documents.forEach(doc => {
            docsContainer.innerHTML += `
                <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fa-regular fa-file text-blue-600"></i>
                    </div>

                    <div>
                    <p class="text-sm font-medium">${doc.name}</p>
                    <p class="text-xs text-gray-400">${doc.type ?? ''}</p>
                    </div>
                </div>

                <a href="${doc.url}" target="_blank"
                    class="fa-solid fa-download text-gray-400 hover:text-indigo-600 cursor-pointer"></a>
                </div>
            `;
            });
        } else {
            docsContainer.innerHTML = `
            <p class="text-sm text-gray-400">No documents uploaded</p>
            `;
        }

        // ⭐ Update stars
        const stars = document.querySelectorAll('#ratingStars .star');
        const ratingText = document.getElementById('ratingText');
        const rating = data.star || 0; // from backend

        stars.forEach(star => {
            if (star.dataset.value <= rating) {
                star.classList.remove('fa-regular');
                star.classList.add('fa-solid');
            } else {
                star.classList.remove('fa-solid');
                star.classList.add('fa-regular');
            }
        });

        ratingText.innerText = `${rating} / 5`;

        // Open modal LAST
        document.getElementById('applicantModal').classList.remove('hidden');
        });
    }


  function closeApplicantModal() {
    document.getElementById('applicantModal').classList.add('hidden');
  }


    //for start clickable
    const stars = document.querySelectorAll('.star');
    const ratingText = document.getElementById('ratingText');
    const ratingInput = document.getElementById('ratingValue');

    stars.forEach((star) => {
        star.addEventListener('click', function () {
            const rating = parseInt(this.dataset.value);

            // build star HTML for popup
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                starsHtml += i <= rating
                    ? '<i class="fa-solid fa-star text-yellow-400 text-2xl mx-1"></i>'
                    : '<i class="fa-regular fa-star text-gray-300 text-2xl mx-1"></i>';
            }

            Swal.fire({
                title: 'Confirm Rating',
                html: `
                    <div class="flex justify-center mb-2">
                        ${starsHtml}
                    </div>
                    <p class="text-sm text-gray-600">Rate this applicant ${rating} / 5</p>
                `,
                showCancelButton: true,
                confirmButtonText: 'Yes, rate',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    setRating(rating);
                    document.getElementById('starRatings').submit();
                }
            });
        });
    });

    function setRating(rating) {
        ratingInput.value = rating;

        stars.forEach((star) => {
            if (star.dataset.value <= rating) {
                star.classList.remove('fa-regular');
                star.classList.add('fa-solid');
            } else {
                star.classList.remove('fa-solid');
                star.classList.add('fa-regular');
            }
        });

        ratingText.textContent = `${rating} / 5`;
    }

    //for pop up
    function confirmStatusChange(select) {
        const newStatus = select.value;

        // If user selects "Choose Option", do nothing
        if (newStatus === '-- Choose Option --') {
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `Change applicant status to "${newStatus}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (!result.isConfirmed) {
                // revert back to "-- Choose Option --"
                select.value = '-- Choose Option --';
            } else {
                // submit or process change
                document.getElementById('updateStatus').submit();
            }
        });
    }
</script>
<script>
  // Get applicants from Blade as JSON
  const applicants = @json($applicant); // Pass your Laravel collection to JS
  const rowsPerPage = 5; // Number of rows per page
  let currentPage = 1;

  function renderTable(page = 1) {
    const tbody = document.getElementById('applicantsTableBody');
    tbody.innerHTML = '';

    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const paginatedItems = applicants.slice(start, end);

    paginatedItems.forEach(app => {
      const statusStyles = {
        'pending': 'background-color: rgba(255, 193, 7, 0.3); color: #ff9307;',
        'Initial Interview': 'background-color: rgba(13, 110, 253, 0.2); color: #0d6efd;',
        'Final Interview': 'background-color: rgba(111, 66, 193, 0.2); color: #6f42c1;',
        'Completed': 'background-color: rgba(25, 135, 84, 0.2); color: #198754;',
        'Hired': 'background-color: rgba(25, 135, 84, 0.2); color: #198754;',
        'Rejected': 'background-color: rgba(220, 53, 69, 0.2); color: #dc3545;',
        'default': 'background-color: rgba(13, 110, 253, 0.2); color: #0d6efd;'
      };
      const badgeStyle = statusStyles[app.application_status] || statusStyles['default'];

      tbody.innerHTML += `
      <tr class="hover:bg-slate-50">
        <td class="py-4 flex items-center gap-3">
          <div class="w-10 h-10 bg-sky-500 text-white rounded-full flex items-center justify-center">SM</div>
          <div>
            <p class="font-medium">${app.first_name} ${app.last_name}</p>
            <p class="text-xs text-gray-400">${app.email}</p>
          </div>
        </td>
        <td>
          <p class="font-medium">${app.position.title}</p>
          <p class="text-xs text-gray-400">${app.collage_name}</p>
        </td>
        <td>${new Date(app.created_at).toLocaleDateString()}</td>
        <td>
          <span class="px-3 py-1 text-xs rounded-full" style="${badgeStyle}">${app.application_status}</span>
        </td>
        <td class="text-yellow-400">
          ${[...Array(5)].map((_, i) => i < (app.starRatings || 0)
            ? '<span class="text-yellow-400">&#9733;</span>'
            : '<span class="text-gray-300">&#9733;</span>'
          ).join('')}
        </td>
        <td class="text-gray-400 space-x-3">
          <i class="fa-regular fa-eye cursor-pointer hover:text-indigo-600" onclick="openApplicantModal(${app.id})"></i>
          <i class="fa-regular fa-calendar cursor-pointer hover:text-indigo-600" onclick="openScheduleModal(${app.id})"></i>
          <i class="fa-solid fa-xmark cursor-pointer"></i>
        </td>
      </tr>`;
    });

    renderPagination();
  }

  function renderPagination() {
    const pagination = document.getElementById('paginationControls');
    pagination.innerHTML = '';
    const pageCount = Math.ceil(applicants.length / rowsPerPage);

    for (let i = 1; i <= pageCount; i++) {
      const btn = document.createElement('button');
      btn.innerText = i;
      btn.className = `px-3 py-1 border rounded ${i === currentPage ? 'bg-indigo-600 text-white' : 'bg-white'}`;
      btn.addEventListener('click', () => {
        currentPage = i;
        renderTable(currentPage);
      });
      pagination.appendChild(btn);
    }
  }

  // Initial render
  renderTable();
</script>

<script>
  const sidebar = document.querySelector('aside');
  const main = document.querySelector('main');
  if (sidebar && main) {
    sidebar.addEventListener('mouseenter', function() {
      main.classList.remove('ml-16');
      main.classList.add('ml-64');
    });
    sidebar.addEventListener('mouseleave', function() {
      main.classList.remove('ml-64');
      main.classList.add('ml-16');
    });
  }
</script>

</html>
