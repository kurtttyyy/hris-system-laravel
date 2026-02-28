<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Payslip</title>
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
    <div class="p-4 md:p-8 pt-10 space-y-6">
      <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h1 class="text-2xl font-semibold text-slate-800">Payslip</h1>
        <p class="text-sm text-slate-500 mt-2">Upload stores the file. Scan currently reads the Excel/CSV for preview only (not saved to payslip records yet).</p>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-6 max-full mx-auto">
        <div id="payslip_message" class="hidden mb-4 rounded-lg border px-4 py-3 text-sm"></div>
        @if (session('success'))
          <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
          </div>
        @endif
        @if ($errors->has('payslip_file'))
          <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first('payslip_file') }}
          </div>
        @endif

        <form id="payslip_upload_form" action="{{ route('admin.uploadPayslipFile') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <label for="payslip_file" class="cursor-pointer border-2 border-dashed border-blue-300 rounded-lg p-6 flex flex-col items-center justify-center text-center hover:bg-blue-50 transition">
            <i class="fa-solid fa-cloud-arrow-up text-3xl text-blue-500 mb-2"></i>
            <p class="text-sm text-blue-600 font-medium">Browse payslip file to upload</p>
            <p class="text-xs text-gray-400 mt-1">Accepted formats: .xlsx, .csv</p>
            <p id="selected_payslip_name" class="text-xs text-slate-500 mt-2">No file selected</p>
          </label>

          <input id="payslip_file" name="payslip_file" type="file" accept=".xlsx,.csv" class="hidden" />

          <div class="flex justify-end mt-4 gap-2">
            <button id="upload_payslip_btn" type="submit" disabled class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-300 disabled:cursor-not-allowed text-white px-4 py-2 rounded-lg text-sm font-medium transition">
              <i class="fa-solid fa-upload mr-2"></i>
              Upload
            </button>
            <button id="scan_payslip_btn" type="button" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
              <i class="fa-solid fa-barcode mr-2"></i>
              Scan
            </button>
          </div>
        </form>

        <div class="bg-white border-2 border-gray-200 rounded-xl p-6 mt-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Files Status</h3>
            <span class="text-xs text-slate-500">Scan reads and saves file data for payslip view.</span>
          </div>

          <div id="payslip_file_list" class="space-y-3">
            @forelse (($payslipFiles ?? collect()) as $file)
              @php
                $isScanned = strcasecmp((string) ($file->status ?? ''), 'Scanned') === 0
                  || strcasecmp((string) ($file->status ?? ''), 'Processed') === 0;
                $progress = $isScanned ? 100 : 0;
              @endphp
              <div class="payslip-file-item flex items-center gap-3 bg-blue-50 p-3 rounded-lg cursor-pointer hover:bg-blue-100 transition" data-file-id="{{ $file->id }}">
                <input type="radio" name="selected_payslip_file" value="{{ $file->id }}" class="payslip-file-radio cursor-pointer">
                <i class="fa-solid fa-file-excel text-green-600 text-xl"></i>
                <div class="flex-1">
                  <div class="flex items-center justify-between gap-2">
                    <p class="text-sm font-medium">
                      {{ $file->original_name }}
                      <span class="payslip-scan-status text-gray-500">- {{ $file->status }}</span>
                    </p>
                    <span class="payslip-progress-text text-xs font-semibold text-gray-700">{{ $progress }}%</span>
                  </div>
                  <div class="w-full bg-gray-300 rounded-full h-2 mt-2">
                    <div class="payslip-progress-bar bg-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                  </div>
                </div>
                <div class="min-w-[140px] text-xs text-gray-500 flex flex-col items-end justify-center">
                  <span>{{ optional($file->uploaded_at)->format('M d, Y') ?? '-' }}</span>
                  <span>{{ optional($file->uploaded_at)->format('h:i A') ?? '-' }}</span>
                  <a href="{{ route('admin.adminPaySlipView', ['upload_id' => $file->id]) }}" class="payslip-view-link {{ $isScanned ? '' : 'hidden' }} mt-2 inline-flex w-full items-center justify-center rounded-md border border-blue-200 bg-blue-50 px-2 py-1 text-sm font-medium text-blue-600 hover:bg-blue-100 hover:text-blue-800">
                    View
                  </a>
                </div>
              </div>
            @empty
              <div class="rounded-lg border border-dashed border-gray-300 p-5 text-center text-sm text-gray-500">
                No uploaded files yet.
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<script>
  const sidebar = document.querySelector('aside');
  const main = document.querySelector('main');
  const payslipInput = document.getElementById('payslip_file');
  const payslipName = document.getElementById('selected_payslip_name');
  const uploadPayslipBtn = document.getElementById('upload_payslip_btn');
  const scanPayslipBtn = document.getElementById('scan_payslip_btn');
  const payslipFileList = document.getElementById('payslip_file_list');
  const payslipMessage = document.getElementById('payslip_message');
  const payslipUploadForm = document.getElementById('payslip_upload_form');
  const payslipScanUrlBase = @json(url('admin/payslip/update-status'));
  let scanInProgress = false;

  const showPayslipMessage = (text, type = 'success') => {
    if (!payslipMessage) return;
    payslipMessage.classList.remove(
      'hidden',
      'border-green-200',
      'bg-green-50',
      'text-green-700',
      'border-red-200',
      'bg-red-50',
      'text-red-700'
    );

    if (type === 'error') {
      payslipMessage.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
    } else {
      payslipMessage.classList.add('border-green-200', 'bg-green-50', 'text-green-700');
    }

    payslipMessage.textContent = text;
  };

  if (payslipInput && payslipName && uploadPayslipBtn && scanPayslipBtn) {
    payslipInput.addEventListener('change', function () {
      const hasFile = this.files && this.files.length > 0;
      payslipName.textContent = hasFile ? this.files[0].name : 'No file selected';
      uploadPayslipBtn.disabled = !hasFile;
    });

    payslipUploadForm?.addEventListener('submit', function (event) {
      if (!payslipInput.files || !payslipInput.files.length) {
        event.preventDefault();
        showPayslipMessage('Please choose a file first.', 'error');
        return;
      }
      uploadPayslipBtn.disabled = true;
      uploadPayslipBtn.classList.add('opacity-60', 'cursor-not-allowed');
    });

    scanPayslipBtn.addEventListener('click', function () {
      if (scanInProgress) return;
      const selected = document.querySelector('input[name="selected_payslip_file"]:checked');
      if (!selected) {
        showPayslipMessage('Please select an uploaded file first.', 'error');
        return;
      }

      const row = selected.closest('.payslip-file-item');
      const fileId = selected.value;
      const bar = row?.querySelector('.payslip-progress-bar');
      const status = row?.querySelector('.payslip-scan-status');
      const progressText = row?.querySelector('.payslip-progress-text');
      const viewLink = row?.querySelector('.payslip-view-link');
      if (!bar || !status || !progressText || !fileId) return;
      const previousStatusText = status.textContent;
      const previousProgressText = progressText.textContent;
      const previousBarWidth = bar.style.width;

      scanInProgress = true;
      scanPayslipBtn.disabled = true;
      uploadPayslipBtn.disabled = true;
      scanPayslipBtn.classList.add('opacity-60', 'cursor-not-allowed');
      uploadPayslipBtn.classList.add('opacity-60', 'cursor-not-allowed');
      scanPayslipBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Scanning...';
      status.textContent = ' - Scanning...';
      progressText.textContent = '0%';

      // Smooth progress animation using requestAnimationFrame.
      const durationMs = 2200;
      const maxBeforeComplete = 92;
      const startTime = performance.now();
      const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

      const animate = (now) => {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / durationMs, 1);
        const eased = easeOutCubic(progress);
        const width = Math.round(eased * maxBeforeComplete);

        bar.style.width = `${width}%`;
        progressText.textContent = `${width}%`;

        if (progress < 1) {
          requestAnimationFrame(animate);
          return;
        }

        fetch(`${payslipScanUrlBase}/${fileId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('#payslip_upload_form input[name="_token"]')?.value || '',
          },
          body: JSON.stringify({ status: 'Scanned' }),
        })
          .then((response) => response.json())
          .then((payload) => {
            if (!payload?.success) {
              throw new Error(payload?.message || 'Scan update failed.');
            }

            bar.style.width = '100%';
            progressText.textContent = '100%';
            status.textContent = ' - Scanned';
            scanPayslipBtn.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Scanned';
            scanPayslipBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            scanPayslipBtn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
            if (viewLink) {
              viewLink.classList.remove('hidden');
            }
            const rows = Number(payload?.processed_rows || 0);
            showPayslipMessage(`File scanned and saved successfully. Matched rows: ${rows}.`);
          })
          .catch((error) => {
            console.error(error);
            status.textContent = previousStatusText;
            progressText.textContent = previousProgressText;
            bar.style.width = previousBarWidth;
            showPayslipMessage('Failed to scan and save file data.', 'error');
          })
          .finally(() => {
            scanPayslipBtn.disabled = false;
            uploadPayslipBtn.disabled = false;
            scanPayslipBtn.classList.remove('opacity-60', 'cursor-not-allowed');
            uploadPayslipBtn.classList.remove('opacity-60', 'cursor-not-allowed');
            scanInProgress = false;
          });
      };

      requestAnimationFrame(animate);
    });
  }

  const payslipFileItems = document.querySelectorAll('.payslip-file-item');
  payslipFileItems.forEach((item) => {
    item.addEventListener('click', function (event) {
      if (event.target.tagName.toLowerCase() === 'a') return;

      const radio = this.querySelector('.payslip-file-radio');
      if (radio) {
        radio.checked = true;
      }

      payslipFileItems.forEach((row) => row.classList.remove('bg-blue-200'));
      this.classList.add('bg-blue-200');
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
</body>
</html>
