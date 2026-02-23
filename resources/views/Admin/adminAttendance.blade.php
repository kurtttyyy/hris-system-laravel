<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PeopleHub - HR Dashboard</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    body { font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif; transition: margin-left 0.3s ease; }
    main { transition: margin-left 0.3s ease; }
    aside ~ main { margin-left: 16rem; }
  </style>
</head>
<body class="bg-slate-100">

<div class="flex min-h-screen">
  @include('components.adminSideBar')

  <main class="flex-1 ml-16 transition-all duration-300">
    @include('components.adminHeader.attendanceHeader')

    <div class="p-4 md:p-8 space-y-6 pt-20">
      @php
        $currentAttendanceRoute = match ($activeAttendanceTab) {
          'present' => 'admin.attendance.present',
          'absent' => 'admin.attendance.absent',
          'tardiness' => 'admin.attendance.tardiness',
          'total_employee' => 'admin.attendance.totalEmployee',
          default => 'admin.adminAttendance',
        };

        $attendanceQuery = array_filter([
          'from_date' => $fromDate,
          'to_date' => $toDate ?? null,
          'upload_id' => $selectedUploadId,
          'job_type' => $selectedJobType ?? null,
          'search_name' => $searchName ?? null,
        ], fn ($value) => !is_null($value) && $value !== '');

        $baseCardClasses = 'relative bg-white rounded-2xl p-6 border border-gray-200 flex items-center justify-center transition';
      @endphp

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('admin.attendance.present', $attendanceQuery) }}"
           class="{{ $baseCardClasses }} hover:border-green-400 hover:bg-green-50 {{ $activeAttendanceTab === 'present' ? 'border-green-500 bg-green-50' : '' }}">
          <div class="text-center">
            <div class="text-4xl font-bold text-gray-800">{{ $presentCount }}</div>
            <div class="text-sm text-gray-500 mt-1">Present</div>
          </div>
        </a>

        <a href="{{ route('admin.attendance.absent', $attendanceQuery) }}"
           class="{{ $baseCardClasses }} hover:border-red-400 hover:bg-red-50 {{ $activeAttendanceTab === 'absent' ? 'border-red-500 bg-red-50' : '' }}">
          <div class="text-center">
            <div class="text-4xl font-bold text-gray-800">{{ $absentCount }}</div>
            <div class="text-sm text-gray-500 mt-1">Absent</div>
          </div>
        </a>

        <a href="{{ route('admin.attendance.tardiness', $attendanceQuery) }}"
           class="{{ $baseCardClasses }} hover:border-amber-400 hover:bg-amber-50 {{ $activeAttendanceTab === 'tardiness' ? 'border-amber-500 bg-amber-50' : '' }}">
          <div class="text-center">
            <div class="text-4xl font-bold text-gray-800">{{ $tardyCount }}</div>
            <div class="text-sm text-gray-500 mt-1">Tardiness</div>
          </div>
        </a>

        <a href="{{ route('admin.attendance.totalEmployee', $attendanceQuery) }}"
           class="{{ $baseCardClasses }} hover:border-blue-400 hover:bg-blue-50 {{ $activeAttendanceTab === 'total_employee' ? 'border-blue-500 bg-blue-50' : '' }}">
          <div class="text-center">
            <div class="text-4xl font-bold text-gray-800">{{ $totalCount }}</div>
            <div class="text-sm text-gray-500 mt-1"> Employees</div>
          </div>
        </a>
      </div>

      @if ($activeAttendanceTab === 'all')
      <div class="bg-white rounded-xl border border-gray-200 p-6 max-full mx-auto">
        @if (session('success'))
          <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
          </div>
        @endif

        @if ($errors->has('excel_file'))
          <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first('excel_file') }}
          </div>
        @endif

        <form action="{{ route('admin.uploadAttendanceExcel') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
          @csrf

          <label for="excel_file" class="cursor-pointer border-2 border-dashed border-blue-300 rounded-lg p-6 flex flex-col items-center justify-center text-center hover:bg-blue-50 transition">
            <i class="fa-solid fa-cloud-arrow-up text-3xl text-blue-500 mb-2"></i>
            <p class="text-sm text-blue-600 font-medium">Browse Excel file to upload</p>
            <p class="text-xs text-gray-400 mt-1">(.xlsx only)</p>
            <p class="text-xs text-gray-400 mt-1">Required columns: employee_id, am_time, pm_time</p>
            <p id="selected_excel_name" class="text-xs text-slate-500 mt-2">No file selected</p>
          </label>

          <input id="excel_file" name="excel_file" type="file" accept=".xlsx" class="hidden" required />

          <div class="flex justify-end">
            <button id="upload_excel_btn" type="submit" disabled class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-300 disabled:cursor-not-allowed text-white px-4 py-2 rounded-lg text-sm font-medium transition">
              <i class="fa-solid fa-upload mr-2"></i>
              Upload Excel
            </button>
          </div>
        </form>

        <div class="bg-white border-2 border-gray-200 rounded-xl p-6 mt-6 relative">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-sm font-semibold text-gray-700">Files Status</h3>

            <form method="GET" action="{{ route($currentAttendanceRoute) }}" class="flex items-center gap-2" style="margin-top: -7px;">
              @if ($selectedUploadId)
                <input type="hidden" name="upload_id" value="{{ $selectedUploadId }}">
              @endif
              @if (!empty($selectedJobType))
                <input type="hidden" name="job_type" value="{{ $selectedJobType }}">
              @endif
              @if (!empty($searchName))
                <input type="hidden" name="search_name" value="{{ $searchName }}">
              @endif
              <label class="text-sm text-gray-600">From Date:</label>
              <input
                name="from_date"
                value="{{ $fromDate }}"
                type="date"
                class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
              />
              <label class="text-sm text-gray-600">To Date:</label>
              <input
                name="to_date"
                value="{{ $toDate ?? '' }}"
                type="date"
                class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
              />
              <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                Filter
              </button>
              <button type="button" id="scan_btn" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                <i class="fa-solid fa-barcode mr-1"></i>Scan
              </button>
            </form>
          </div>

          <div class="space-y-3">
            @forelse ($attendanceFiles as $file)
              @php
                $progress = 0;
                if ($file->status === 'Processed') {
                  $progress = 100;
                } elseif ($file->status === 'Processing' && $file->processed_rows) {
                  $progress = min(75, $file->processed_rows);
                } elseif ($file->status === 'Pending') {
                  $progress = 0;
                }
              @endphp
              <div class="file-item flex items-center gap-3 bg-blue-50 p-3 rounded-lg cursor-pointer hover:bg-blue-100 transition" data-file-id="{{ $file->id }}">
                <input type="radio" name="selected_file" value="{{ $file->id }}" class="file-checkbox cursor-pointer">
                <i class="fa-solid fa-file-excel text-green-600 text-xl"></i>

                <div class="flex-1">
                  <div class="flex items-center justify-between mb-2">
                    <div>
                      <p class="text-sm font-medium">
                        {{ $file->original_name }}
                        <span class="text-gray-500">- <span class="file-status">{{ $file->status }}</span></span>
                      </p>
                      <p class="text-xs text-gray-500">
                        {{ number_format((float) $file->file_size / 1024, 2) }} KB
                        @if (!is_null($file->processed_rows))
                          | {{ $file->processed_rows }} rows processed
                        @endif
                      </p>
                    </div>
                    <div class="text-xs font-semibold text-gray-700 min-w-[40px] text-right">
                      {{ $progress }}%
                    </div>
                  </div>
                  <div class="w-full bg-gray-300 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                  </div>
                </div>

                <div class="text-right text-xs text-gray-500 min-w-[120px]">
                  {{ optional($file->uploaded_at)->format('M d, Y') ?? '-' }}<br>
                  {{ optional($file->uploaded_at)->format('h:i A') ?? '-' }}
                </div>

                <button type="button" class="delete-btn ml-4 text-red-600 hover:text-red-800 transition" data-file-id="{{ $file->id }}" title="Delete file">
                  <i class="fa-solid fa-trash-can text-lg"></i>
                </button>
              </div>
            @empty
              <div class="rounded-lg border border-dashed border-gray-300 p-5 text-center text-sm text-gray-500">
                No uploaded files yet.
              </div>
            @endforelse
          </div>
        </div>

      </div>
      @else
      <div class="bg-white rounded-xl border border-gray-200 p-6 max-full mx-auto space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="text-sm font-semibold text-gray-700">Attendance List</h3>
          <form method="GET" action="{{ route($currentAttendanceRoute) }}" class="flex items-center gap-2">
            @if ($selectedUploadId)
              <input type="hidden" name="upload_id" value="{{ $selectedUploadId }}">
            @endif
            @if (!empty($searchName))
              <input type="hidden" name="search_name" value="{{ $searchName }}">
            @endif
            <label class="text-sm text-gray-600">Job Type:</label>
            <select
              name="job_type"
              class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
              <option value="">All Job Types</option>
              @foreach (($jobTypeOptions ?? collect()) as $jobTypeOption)
                <option value="{{ $jobTypeOption }}" {{ ($selectedJobType ?? null) === $jobTypeOption ? 'selected' : '' }}>
                  {{ $jobTypeOption }}
                </option>
              @endforeach
            </select>
            <label class="text-sm text-gray-600">From Date:</label>
            <input
              name="from_date"
              value="{{ $fromDate }}"
              type="date"
              class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
            />
            <label class="text-sm text-gray-600">To Date:</label>
            <input
              name="to_date"
              value="{{ $toDate ?? '' }}"
              type="date"
              class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
            />
            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
              Filter
            </button>
          </form>
        </div>

        @if ($activeAttendanceTab === 'present')
          @include('Admin.attendanceTable.presentEmployee', ['rows' => $presentEmployees])
        @elseif ($activeAttendanceTab === 'absent')
          @include('Admin.attendanceTable.absentEmployee', ['rows' => $absentEmployees])
        @elseif ($activeAttendanceTab === 'tardiness')
          @include('Admin.attendanceTable.tardinessEmployee', ['rows' => $tardyEmployees])
        @elseif ($activeAttendanceTab === 'total_employee')
          @include('Admin.attendanceTable.totalEmployee', ['rows' => $allEmployees])
        @endif
      </div>
      @endif
    </div>
  </main>
</div>

</body>

<script>
  const sidebar = document.querySelector('aside');
  const main = document.querySelector('main');
  const excelInput = document.getElementById('excel_file');
  const excelName = document.getElementById('selected_excel_name');
  const uploadBtn = document.getElementById('upload_excel_btn');
  const scanBtn = document.getElementById('scan_btn');
  const fileItems = document.querySelectorAll('.file-item');
  const fileCheckboxes = document.querySelectorAll('.file-checkbox');
  const deleteButtons = document.querySelectorAll('.delete-btn');
  const fromDateInput = document.querySelector('input[name="from_date"]');
  const toDateInput = document.querySelector('input[name="to_date"]');

  // File selection handling
  fileItems.forEach(item => {
    item.addEventListener('click', function(e) {
      if (e.target.type !== 'radio') {
        const checkbox = this.querySelector('.file-checkbox');
        checkbox.checked = true;
      }
      fileItems.forEach(f => f.classList.remove('bg-blue-200'));
      this.classList.add('bg-blue-200');
    });
  });

  fileCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      fileItems.forEach(item => {
        if (item.querySelector('.file-checkbox') === this) {
          item.classList.add('bg-blue-200');
        } else {
          item.classList.remove('bg-blue-200');
        }
      });
    });
  });

  // Scan button handler
  if (scanBtn) {
    scanBtn.addEventListener('click', function() {
      const selectedCheckbox = document.querySelector('.file-checkbox:checked');
      if (!selectedCheckbox) {
        alert('Please select a file first');
        return;
      }

      const fileId = selectedCheckbox.value;
      const fileItem = document.querySelector(`[data-file-id="${fileId}"]`);
      const statusElement = fileItem.querySelector('.file-status');
      const progressBar = fileItem.querySelector('.bg-green-500');
      const progressTextElement = fileItem.querySelector('.min-w-\\[40px\\]');

      // Disable button during processing
      scanBtn.disabled = true;
      scanBtn.classList.add('opacity-50', 'cursor-not-allowed');

      // Start progress animation
      let progress = parseInt(progressTextElement.textContent);
      const targetProgress = 100;
      const animationDuration = 3000; // 3 seconds
      const startTime = Date.now();

      const animateProgress = () => {
        const elapsed = Date.now() - startTime;
        const fraction = Math.min(elapsed / animationDuration, 1);
        
        // Easing function for smooth animation
        const easeProgress = progress + (targetProgress - progress) * (fraction < 0.8 ? fraction * 1.25 : 0.8 + (fraction - 0.8) * 0.5);
        const currentProgress = Math.floor(easeProgress);

        if (progressBar) {
          progressBar.style.width = currentProgress + '%';
        }
        if (progressTextElement) {
          progressTextElement.textContent = currentProgress + '%';
        }

        if (fraction < 1) {
          requestAnimationFrame(animateProgress);
        }
      };

      // Start animation
      animateProgress();

      // Send update to server after animation completes
      setTimeout(() => {
        fetch(`/admin/attendance/update-status/${fileId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
          },
          body: JSON.stringify({
            status: 'Processed',
            from_date: fromDateInput?.value || null,
            to_date: toDateInput?.value || null
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            statusElement.textContent = 'Processed';
            if (progressBar) {
              progressBar.style.width = '100%';
            }
            if (progressTextElement) {
              progressTextElement.textContent = '100%';
            }
            alert('File scan completed successfully!');
            scanBtn.disabled = false;
            scanBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            if (data.redirect_url) {
              window.location.href = data.redirect_url;
            }
          } else {
            alert('Failed to update status');
            scanBtn.disabled = false;
            scanBtn.classList.remove('opacity-50', 'cursor-not-allowed');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error updating status');
          scanBtn.disabled = false;
          scanBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
      }, animationDuration);
    });
  }

  if (excelInput && excelName && uploadBtn) {
    excelInput.addEventListener('change', function () {
      const hasFile = this.files && this.files.length > 0;
      uploadBtn.disabled = !hasFile;
      excelName.textContent = hasFile ? this.files[0].name : 'No file selected';
    });
  }

  // Delete button handler
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      const fileId = this.dataset.fileId;
      
      if (confirm('Are you sure you want to delete this file?')) {
        fetch(`/admin/attendance/delete/${fileId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const fileItem = document.querySelector(`[data-file-id="${fileId}"]`);
            fileItem.closest('.file-item').remove();
            alert('File deleted successfully');
          } else {
            alert('Failed to delete file');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error deleting file');
        });
      }
    });
  });

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

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
</html>
