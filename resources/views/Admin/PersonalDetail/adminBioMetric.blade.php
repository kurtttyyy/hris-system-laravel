
<div x-show="tab === 'biometric'" x-transition class="w-full p-6 space-y-6">
    <div class="p-8 space-y-6">
      <div>

<div class="max-w-5xl mx-auto bg-white px-5 py-8 border border-gray-400 text-[13px] text-black">

<!-- EDIT & DOWNLOAD BUTTONS -->
<div id="action-buttons" class="flex justify-between items-center mb-4 space-x-2">

  <!-- Edit Icon -->
  <div class="relative group">
    <button @click="openEditProfile = true; modalTarget = 'biometric'" class="p-2 bg-green-600 text-white rounded hover:bg-green-700">
      <!-- Pencil/Edit Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M16.5 3.5l4 4-8 8H8v-4l8.5-8.5z" />
      </svg>
    </button>

    <!-- Bubble Chat Tooltip -->
    <div
      class="absolute bottom-full mb-3 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
      <div class="relative bg-green-600 text-white text-xs rounded-lg px-3 py-1 shadow-lg whitespace-nowrap">
        Edit Profile
        <!-- Tail using pseudo-circle -->
        <div class="absolute left-1/2 -bottom-1 w-2 h-2 bg-green-600 rotate-45 -translate-x-1/2"></div>
      </div>
    </div>
  </div>

  <!-- Download Icon -->
  <div class="relative group">
    <button onclick="downloadProfilePDF()"
      class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700">
      <!-- Download Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v8m0 0l-4-4m4 4l4-4M12 4v8" />
      </svg>
    </button>


    <!-- Bubble Chat Tooltip -->
    <div
      class="absolute bottom-full mb-3 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
      <div class="relative bg-blue-600 text-white text-xs rounded-lg px-3 py-1 shadow-lg whitespace-nowrap">
        Download Profile
        <!-- Tail -->
        <div class="absolute left-1/2 -bottom-1 w-2 h-2 bg-blue-600 rotate-45 -translate-x-1/2"></div>
      </div>
    </div>
  </div>

</div>


<div id="profile-form">

  <!-- HEADER -->
  <div class="text-center mb-6 leading-tight">
    <p class="font-semibold uppercase">Northeastern College</p>
    <p>Santiago City, Philippines</p>
    <p>Telephone No.: (078) 305-3226</p>

    <p class="mt-4 font-semibold uppercase">Office of the Human Resource</p>
    <p class="font-semibold uppercase">Employees Profile Form</p>
  </div>

  <!-- ROW STYLES -->
  <style>
    .row {
      padding: 4px 8px;
      border-bottom: 1px solid #d1d5db;
      font-size: 0.875rem;
    }
    .label {
      font-weight: 500;
      display: inline;
    }
    .value {
      display: inline;
      margin-left: 4px;
    }
  </style>

  <!-- TOP SECTION -->
  <div class="grid grid-cols-2 gap-4">

    <!-- LEFT BOX -->
    <div class="border border-gray-500">
      <div class="row">
        <span class="label">Last Name:</span>
        <span class="value" x-text="selectedEmployee?.last_name ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">First Name:</span>
        <span class="value" x-text="selectedEmployee?.first_name ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Middle Name:</span>
        <span class="value" x-text="selectedEmployee?.middle_name ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">ID Number:</span>
        <span class="value" x-text="selectedEmployee?.employee?.employee_id ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Account No.:</span>
        <span class="value" x-text="selectedEmployee?.employee?.account_number ?? '-'"></span>
      </div>
    </div>

    <!-- RIGHT BOX -->
    <div class="border border-gray-500">
      <div class="row">
        <span class="label">Sex:</span>
        <span class="value" x-text="selectedEmployee?.employee?.sex ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Civil Status:</span>
        <span class="value" x-text="selectedEmployee?.employee?.civil_status ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Contact No.:</span>
        <span class="value" x-text="selectedEmployee?.employee?.contact_number ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Date of Birth:</span>
        <span class="value" x-text="selectedEmployee?.employee?.formatted_birthday ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Address:</span>
        <span class="value" x-text="selectedEmployee?.employee?.address ?? '-'"></span>
      </div>
    </div>
  </div>

  <!-- EMPLOYMENT SECTION -->
  <div class="grid grid-cols-2 gap-4 mt-4">

    <div class="border border-gray-500">
      <div class="row">
        <span class="label">Employment Date:</span>
        <span
          class="value"
          x-text="(() => {
            const raw = selectedEmployee?.employee?.employement_date;
            if (!raw) return '-';
            const parsed = new Date(raw);
            return Number.isNaN(parsed.getTime())
              ? raw
              : parsed.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
          })()"
        ></span>
      </div>
      <div class="row">
        <span class="label">Position:</span>
        <span class="value" x-text="selectedEmployee?.employee?.position ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Department:</span>
        <span class="value" x-text="selectedEmployee?.employee?.department ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Classification:</span>
        <span class="value">
          <label class="mr-2">
            <input type="checkbox" disabled
              :checked="selectedEmployee?.employee?.classification === 'Full-Time'"> Full-time
          </label>
          <label class="mr-2">
            <input type="checkbox" disabled
              :checked="selectedEmployee?.employee?.classification === 'Part-Time'"> Part-time
          </label>
          <label>
            <input type="checkbox" disabled
              :checked="selectedEmployee?.employee?.classification === 'NT'"> NT
          </label>
        </span>
      </div>
    </div>

    <div class="border border-gray-500">
      <div class="row">
        <span class="label">SSS:</span>
        <span class="value" x-text="selectedEmployee?.government?.SSS ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">TIN:</span>
        <span class="value" x-text="selectedEmployee?.government?.TIN ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">PhilHealth:</span>
        <span class="value" x-text="selectedEmployee?.government?.PhilHealth ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Pag-IBIG MID:</span>
        <span class="value" x-text="selectedEmployee?.government?.MID ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Pag-IBIG RTN:</span>
        <span class="value" x-text="selectedEmployee?.government?.RTN ?? '-'"></span>
      </div>
    </div>
  </div>

  <!-- LICENSE + EDUCATION -->
  <div class="grid grid-cols-2 gap-4 mt-4">

    <div class="border border-gray-500">
      <div class="row">
        <span class="label">License:</span>
        <span class="value" x-text="selectedEmployee?.license?.license ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Registration No.:</span>
        <span class="value" x-text="selectedEmployee?.license?.registration_number ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Registration Date:</span>
        <span class="value" x-text="selectedEmployee?.license?.registration_date ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Valid Until:</span>
        <span class="value" x-text="selectedEmployee?.license?.valid_until ?? '-'"></span>
      </div>
    </div>

    <div class="border border-gray-500">
      <div class="row">
        <span class="label">Bachelor’s Degree:</span>
        <span class="value" x-text="selectedEmployee?.education?.bachelor ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Master’s Degree:</span>
        <span class="value" x-text="selectedEmployee?.education?.master ?? '-'"></span>
      </div>
      <div class="row">
        <span class="label">Doctorate Degree:</span>
        <span class="value" x-text="selectedEmployee?.education?.doctorate ?? '-'"></span>
      </div>
    </div>
  </div>

  <!-- SALARY -->
  <div class="border border-gray-500 mt-4" style="width:356px;">
    <div class="row">
      <span class="label">Basic Salary:</span>
      <span class="value" x-text="selectedEmployee?.salary?.salary ?? '-'"></span>
    </div>
    <div class="row">
      <span class="label">Rate per Hour:</span>
      <span class="value" x-text="selectedEmployee?.salary?.rate_per_hour ?? '-'"></span>
    </div>
    <div class="row">
      <span class="label">COLA:</span>
      <span class="value" x-text="selectedEmployee?.salary?.cola ?? '-'"></span>
    </div>
  </div>

  <div class="border-t border-dashed border-gray-500 my-4"></div>

  <!-- EMPLOYEE DETAILS -->
  <div class="row font-semibold bg-gray-100">Employee Details</div>

  <div class="row">
    <span class="label">Full Name:</span>
    <span class="value"
      x-text="`${selectedEmployee?.first_name ?? ''} ${selectedEmployee?.middle_name ?? ''} ${selectedEmployee?.last_name ?? ''}`">
    </span>
  </div>

  <div class="row">
    <span class="label">ID Number:</span>
    <span class="value" x-text="selectedEmployee?.employee?.employee_id ?? '-'"></span>
  </div>

  <div class="row">
    <span class="label">Department:</span>
    <span class="value" x-text="selectedEmployee?.employee?.department ?? '-'"></span>
  </div>

  <div class="row">
    <span class="label">Person to Contact in Emergency:</span>
    <span class="value" x-text="selectedEmployee?.employee?.emergency_contact_name ?? '-'"></span>
  </div>

  <div class="row">
    <span class="label">Address:</span>
    <span class="value" x-text="selectedEmployee?.employee?.address ?? '-'"></span>
  </div>

  <div class="row">
    <span class="label">Cellphone Number:</span>
    <span class="value" x-text="selectedEmployee?.employee?.emergency_contact_number ?? '-'"></span>
  </div>

  <!-- FOOTER -->
  <div class="mt-6 text-xs text-gray-600">
    NC HR Form No. 16a – Employees Profile Rev. 01
  </div>

</div>

</div>

<!-- MODAL -->
<!-- FULL FORM MODAL -->
<div
  x-show="openEditProfile && modalTarget === 'biometric'"
  x-transition
  class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div
    @click.outside="openEditProfile = false; modalTarget = ''"
    class="bg-white w-full max-w-5xl rounded shadow-lg p-6 text-sm overflow-y-auto max-h-[90vh]"
  >

    <h2 class="text-lg font-semibold mb-4">Edit Employee Profile</h2>
    <form action="{{ route('admin.updateBio')}}" method="POST">
        @csrf
        <input type="hidden" name="user_id" :value="selectedEmployee?.id">
    <!-- PERSONAL INFO -->
    <div class="grid grid-cols-2 gap-4">
      <input class="border px-2 py-1" name="last" placeholder="Last Name" x-model="selectedEmployee.last_name">
      <input class="border px-2 py-1" name="first" placeholder="First Name" x-model="selectedEmployee.first_name">
      <input class="border px-2 py-1" name="middle" placeholder="Middle Name" x-model="selectedEmployee.middle_name">
      <input class="border px-2 py-1" name="employee_id" placeholder="ID Number" x-model="selectedEmployee.employee.employee_id">
      <input class="border px-2 py-1" name="account_number" placeholder="Account No." x-model="selectedEmployee.employee.account_number">
      <select name="gender" class="border px-2 py-1" x-model="selectedEmployee.employee.sex">
        <option value= "">Sex</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>
      <input class="border px-2 py-1" name="civil_status" placeholder="Civil Status" x-model="selectedEmployee.employee.civil_status">
      <input class="border px-2 py-1" name="contact_number" placeholder="Contact No." x-model="selectedEmployee.employee.contact_number">
      <div>
      <label class="block text-xs text-gray-600">Date of Birth</label>
        <input type="date" name="birthday" class="w-full border px-2 py-1" :value="selectedEmployee?.employee?.birthday ? selectedEmployee.employee.birthday.split('T')[0] : ''">
      </div>
      <input class="border px-2 py-1" name="address" placeholder="Address" x-model="selectedEmployee.employee.address">
    </div>

    <!-- EMPLOYMENT -->
    <h3 class="font-semibold mt-6">Employment Information</h3>
    <div class="grid grid-cols-2 gap-4">
      <div>
      <label class="block text-xs text-gray-600">Employment Date</label>
        <input
          type="date"
          name="employment_date"
          class="w-full border px-2 py-1"
          :value="selectedEmployee?.employee?.employement_date
            ? selectedEmployee.employee.employement_date.split('T')[0]
            : (selectedEmployee?.applicant?.date_hired
              ? selectedEmployee.applicant.date_hired.split('T')[0]
              : '')"
        >
      </div>
      <input class="border px-2 py-1" name = "position" placeholder="Position" x-model="selectedEmployee.employee.position">
      <input class="border px-2 py-1" name = "department" placeholder="Department" x-model="selectedEmployee.employee.department">

      <select name = "classification" class="border px-2 py-1" x-model="selectedEmployee.employee.classification">
        <option value ="">Classification</option>
        <option value ="Full-Time">Full-time</option>
        <option value ="Part-Time">Part-time</option>
        <option value ="NT">NT</option>
      </select>
    </div>

    <!-- GOVERNMENT IDS -->
    <h3 class="font-semibold mt-6">Government IDs</h3>
    <div class="grid grid-cols-2 gap-4">
      <input name = "SSS" class="border px-2 py-1" placeholder="SSS" x-model="selectedEmployee.government.SSS">
      <input name = "TIN" class="border px-2 py-1" placeholder="TIN" x-model="selectedEmployee.government.TIN">
      <input name = "PhilHealth" class="border px-2 py-1" placeholder="PhilHealth" x-model="selectedEmployee.government.PhilHealth">
      <input name = "MID" class="border px-2 py-1" placeholder="Pag-IBIG MID" x-model="selectedEmployee.government.MID">
      <input name = "RTN" class="border px-2 py-1" placeholder="Pag-IBIG RTN" x-model="selectedEmployee.government.RTN">
    </div>

    <!-- LICENSE -->
    <h3 class="font-semibold mt-6">License</h3>
    <div class="grid grid-cols-2 gap-4">
      <input name = "license" class="border px-2 py-1" placeholder="License" x-model="selectedEmployee.license.license">
      <input name = "registration_number" class="border px-2 py-1" placeholder="Registration No." x-model="selectedEmployee.license.registration_number">
      <div>
        <label class="block text-xs text-gray-600">Registration Date</label>
        <input name = "registration_date" type="date" class="w-full border px-2 py-1" :value="selectedEmployee?.license?.registration_date ? selectedEmployee.license.registration_date.split('T')[0] : ''">
      </div>
      <div>
        <label class="block text-xs text-gray-600">Valid Until</label>
        <input name = "valid_until" type="date" class="w-full border px-2 py-1" :value="selectedEmployee?.license?.valid_until ? selectedEmployee.license.valid_until.split('T')[0] : ''">
      </div>

    </div>

    <!-- EDUCATION -->
    <h3 class="font-semibold mt-6">Education</h3>
    <div class="grid grid-cols-2 gap-4">
      <input name = "bachelor" class="border px-2 py-1" placeholder="Bachelor’s Degree" x-model="selectedEmployee.education.bachelor">
      <input name = "master" class="border px-2 py-1" placeholder="Master’s Degree" x-model="selectedEmployee.education.master">
      <input name = "doctorate" class="border px-2 py-1" placeholder="Doctorate Degree" x-model="selectedEmployee.education.doctorate">
    </div>

    <!-- SALARY -->
    <h3 class="font-semibold mt-6">Salary</h3>
    <div class="grid grid-cols-3 gap-4">
      <input name = "salary" class="border px-2 py-1" placeholder="Basic Salary" x-model="selectedEmployee.salary.salary">
      <input name = "rate_per_hour" class="border px-2 py-1" placeholder="Rate per Hour" x-model="selectedEmployee.salary.rate_per_hour">
      <input name = "cola" class="border px-2 py-1" placeholder="COLA" x-model="selectedEmployee.salary.cola">
    </div>

    <h3 class="font-semibold mt-6">Emergency Contact Person</h3>
    <div class="grid grid-cols-3 gap-4">
      <input name="emergency_contact_name" class="border px-2 py-1" placeholder="Name" x-model="selectedEmployee.employee.emergency_contact_name">
      <input name="emergency_contact_number" class="border px-2 py-1" placeholder="Contact Number" x-model="selectedEmployee.employee.emergency_contact_number">
      <input name="emergency_contact_relationship" class="border px-2 py-1" placeholder="Relationship" x-model="selectedEmployee.employee.emergency_contact_relationship">
    </div>

    <!-- ACTIONS -->
    <div class="flex justify-end gap-2 mt-6">
      <button type="button"
        @click="openEditProfile = false; modalTarget = ''"
        class="px-4 py-1 border rounded">
        Cancel
      </button>

      <button type="submit"
        class="px-4 py-1 bg-green-600 text-white rounded">
        Save
      </button>
    </div>

    </form>

  </div>
</div>


<style>
  .row {
    border-bottom: 1px solid #6b7280;
    padding: 6px 8px;
    height: 30px;
  }
  .row:last-child {
    border-bottom: none;
  }

  /* PRINT ONLY THE FORM */
  @media print {
    body * {
      visibility: hidden;
    }

    #profile-form,
    #profile-form * {
      visibility: visible;
    }

    #profile-form {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
    }

      .row {
        @apply flex justify-between px-2 py-1 border-b border-gray-300 text-sm;
      }
      .label {
        @apply font-medium;
      }
      .value {
        @apply text-right;
      }
  }
</style>

<!-- HTML2PDF Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
function downloadProfilePDF() {
  const element = document.getElementById('profile-form');
  const opt = {
    margin: 10,
    filename: 'Employee_Profile_' + new Date().getTime() + '.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
  };
  html2pdf().set(opt).from(element).save();
}
</script>



    </div>
    </div>
</div>

