<!-- Personal Details -->
<div class="w-full p-6 grid grid-cols-1 md:grid-cols-2 gap-6"
  x-show="tab === 'personal'"
  x-transition
>

  <!-- Personal Information -->
  <div class="bg-slate-50 p-6 rounded-xl shadow-sm space-y-5">
    <div class="flex items-center gap-2 text-indigo-600 font-semibold text-lg">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M5.121 17.804A9.003 9.003 0 1118.879 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
      Personal Information
    </div>

    <div class="flex gap-3">
      <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M4.5 20.25a7.5 7.5 0 0115 0" />
      </svg>
      <div>
        <span class="block text-xs uppercase text-gray-400 font-semibold">Full Name</span>
        <span x-text="selectedEmployee?.applicant?.first_name + ' ' + selectedEmployee?.applicant?.last_name"></span>
      </div>
    </div>

    <div class="flex gap-3">
      <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M8 7V3m8 4V3m-11 8h14m-15 9.75A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V7.5A2.25 2.25 0 0018.75 5.25H5.25A2.25 2.25 0 003 7.5v11.25z" />
      </svg>
      <div>
        <span class="block text-xs uppercase text-gray-400 font-semibold">Date of Birth</span>
        <span x-text="selectedEmployee?.applicant?.date_of_birth ?? '—'"></span>
      </div>
    </div>

<div class="flex gap-3">
  <svg
    class="w-5 h-5 text-gray-400 mt-1"
    fill="none"
    stroke="currentColor"
    stroke-width="1.5"
    viewBox="0 0 24 24"
  >
    <!-- Female -->
    <circle cx="9" cy="8" r="3" />
    <path stroke-linecap="round" d="M9 11v4m-2 0h4" />

    <!-- Male -->
    <circle cx="15" cy="14" r="3" />
    <path stroke-linecap="round" d="M17 12l3-3m0 0h-3m3 0v3" />
  </svg>

  <div>
    <span class="block text-xs uppercase text-gray-400 font-semibold">Gender</span>
    <span x-text="selectedEmployee?.applicant?.gender ?? '—'"></span>
  </div>
</div>


<div class="flex gap-3">
  <!-- Flag Icon -->
  <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v16M4 4h16v4H4v12"/>
  </svg>

  <div>
    <span class="block text-xs uppercase text-gray-400 font-semibold">Nationality</span>
    <span x-text="selectedEmployee?.applicant?.nationality ?? '—'"></span>
  </div>
</div>


<div class="flex gap-3">
  <!-- Two-Person Icon -->
  <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
    <!-- Person 1 -->
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25a7.5 7.5 0 0115 0"/>
    <!-- Person 2 -->
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14a7.5 7.5 0 00-7.5 6.75"/>
  </svg>

  <div>
    <span class="block text-xs uppercase text-gray-400 font-semibold">Marital Status</span>
    <span x-text="selectedEmployee?.applicant?.marital_status ?? '—'"></span>
  </div>
</div>

  </div>

  <!-- Address -->
  <div class="bg-slate-50 p-6 rounded-xl shadow-sm space-y-5">
<div class="flex items-center gap-2 text-indigo-600 font-semibold text-lg">
  <!-- Location Marker / Map Pin Icon -->
  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a7 7 0 017 7c0 4.418-7 13-7 13S5 13.418 5 9a7 7 0 017-7z"/>
    <circle cx="12" cy="9" r="2.5"/>
  </svg>
  Address
</div>


<div class="flex gap-3">
  <!-- House Icon for Street Address -->
  <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9v9a3 3 0 01-3 3H6a3 3 0 01-3-3v-9z" />
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 21V12h6v9" />
  </svg>
  
  <div>
    <span class="block text-xs uppercase text-gray-400 font-semibold">Street Address</span>
    <span x-text="selectedEmployee?.applicant?.address?.street ?? '—'"></span>
  </div>
</div>


<div class="flex gap-3">
  <!-- Location Marker Icon for Province -->
  <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a7 7 0 017 7c0 4.418-7 13-7 13S5 13.418 5 9a7 7 0 017-7z" />
    <circle cx="12" cy="9" r="2.5" />
  </svg>

  <div>
    <span class="block text-xs uppercase text-gray-400 font-semibold">Province</span>
    <span x-text="selectedEmployee?.applicant?.address?.province ?? '—'"></span>
  </div>
</div>


<div class="flex gap-3">
  <!-- Location Marker Icon for Municipality -->
  <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a7 7 0 017 7c0 4.418-7 13-7 13S5 13.418 5 9a7 7 0 017-7z" />
    <circle cx="12" cy="9" r="2.5" />
  </svg>

  <div>
    <span class="block text-xs uppercase text-gray-400 font-semibold">Municipality</span>
    <span x-text="selectedEmployee?.applicant?.address?.municipality ?? '—'"></span>
  </div>
</div>


<div class="flex gap-3">
  <!-- Location Marker Icon for Postal Code -->
  <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a7 7 0 017 7c0 4.418-7 13-7 13S5 13.418 5 9a7 7 0 017-7z" />
    <circle cx="12" cy="9" r="2.5" />
  </svg>

  <div>
    <span class="block text-xs uppercase text-gray-400 font-semibold">Postal Code</span>
    <span x-text="selectedEmployee?.applicant?.address?.postal_code ?? '—'"></span>
  </div>
</div>

  </div>

  <!-- Emergency Contact -->
  <div class="bg-slate-50 p-6 rounded-xl shadow-sm space-y-5">
    <div class="flex items-center gap-2 text-red-600 font-semibold text-lg">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M18.364 5.636a9 9 0 11-12.728 0" />
      </svg>
      Emergency Contact
    </div>

<div class="flex gap-3">
  <svg
    class="w-5 h-5 text-gray-400 mt-1"
    fill="none"
    stroke="currentColor"
    stroke-width="1.5"
    viewBox="0 0 24 24"
  >
    <!-- Head -->
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
    />
    <!-- Body -->
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M4.5 20.25a7.5 7.5 0 0115 0"
    />
  </svg>

  <div>
    <span class="block text-xs uppercase text-gray-400 font-semibold">
      Contact Name
    </span>
    <span x-text="selectedEmployee?.emergency_contact?.name ?? '—'"></span>
  </div>
</div>


<div class="flex gap-3">
  <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
    <!-- Two heads + shoulders -->
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25a7.5 7.5 0 0115 0" />
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14a7.5 7.5 0 00-7.5 6.75" />
  </svg>

  <div>
    <span class="block text-xs uppercase text-gray-400 font-semibold">Relationship</span>
    <span x-text="selectedEmployee?.emergency_contact?.relationship ?? '—'"></span>
  </div>
</div>


<div class="flex gap-3">
  <svg
    class="w-5 h-5 text-gray-400 mt-1"
    fill="none"
    stroke="currentColor"
    stroke-width="1.5"
    viewBox="0 0 24 24"
  >
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a2.25 2.25 0 002.25-2.25v-1.372a1.125 1.125 0 00-.852-1.091l-4.423-1.106a1.125 1.125 0 00-1.173.417l-.97 1.293a12.035 12.035 0 01-5.292-5.292l1.293-.97a1.125 1.125 0 00.417-1.173L6.963 4.102A1.125 1.125 0 005.872 3.25H4.5A2.25 2.25 0 002.25 5.5v1.25z"
    />
  </svg>

  <div>
    <span class="block text-xs uppercase text-gray-400 font-semibold">Phone Number</span>
    <span x-text="selectedEmployee?.emergency_contact?.phone ?? '—'"></span>
  </div>
</div>

  </div>

  <!-- Bank Details -->
  <div class="bg-slate-50 p-6 rounded-xl shadow-sm space-y-5">
    <div class="flex items-center gap-2 text-indigo-600 font-semibold text-lg">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M3 10h18M12 2v20M7 18h10" />
      </svg>
      Bank Details
    </div>

<div class="flex gap-3">
  <svg
    class="w-5 h-5 text-gray-400 mt-1"
    fill="none"
    stroke="currentColor"
    stroke-width="1.5"
    viewBox="0 0 24 24"
  >
    <!-- Bank / building structure -->
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M3 10l9-7 9 7M4.5 10.5v9h15v-9"
    />
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M9 14h1.5M13.5 14H15M9 17h1.5M13.5 17H15"
    />
  </svg>

  <div>
    <span class="block text-xs uppercase text-gray-400 font-semibold">
      Bank Name
    </span>
    <span x-text="selectedEmployee?.bank?.name ?? '—'"></span>
  </div>
</div>


  <div class="flex gap-3 items-center">
    <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
      <!-- Credit Card / Account Icon -->
      <rect x="2.25" y="6.75" width="19.5" height="10.5" rx="2" />
      <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 10.5h19.5" />
    </svg>
    <div>
      <span class="block text-xs uppercase text-gray-400 font-semibold">Account Number</span>
      <span x-text="selectedEmployee?.bank?.account_number ?? '—'"></span>
    </div>
  </div>

  <!-- Routing Number -->
  <div class="flex gap-3 items-center">
    <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
      <!-- Document / Ledger Icon -->
      <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4z" />
      <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h8M8 17h4" />
    </svg>
    <div>
      <span class="block text-xs uppercase text-gray-400 font-semibold">Routing Number</span>
      <span x-text="selectedEmployee?.bank?.routing_number ?? '—'"></span>
    </div>
  </div>

</div>
