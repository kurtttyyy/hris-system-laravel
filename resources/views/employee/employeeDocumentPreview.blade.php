<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Preview</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">
    <div class="max-w-6xl mx-auto p-4 md:p-6">
        <div class="bg-white rounded-xl border border-slate-200 p-4 md:p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div>
                    <h1 class="text-lg font-semibold text-slate-800">Secure Document Preview</h1>
                    <p class="text-sm text-slate-500">{{ $document->filename ?? 'Document' }}</p>
                </div>
                <a href="{{ route('employee.employeeDocument') }}" class="px-3 py-2 text-sm rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">
                    Back
                </a>
            </div>

            @if($isPdf)
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 mb-3">
                    <div class="flex items-center justify-between">
                        <button id="prevPage" type="button" class="px-3 py-1.5 text-sm rounded bg-slate-200 hover:bg-slate-300">Prev</button>
                        <p class="text-sm text-slate-600">Page <span id="pageNum">1</span> of <span id="pageCount">1</span></p>
                        <button id="nextPage" type="button" class="px-3 py-1.5 text-sm rounded bg-slate-200 hover:bg-slate-300">Next</button>
                    </div>
                </div>

                <div class="overflow-auto rounded-lg border border-slate-200 bg-slate-900 p-3">
                    <canvas id="pdfCanvas" class="mx-auto"></canvas>
                </div>
            @elseif($isImage)
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                    <img src="{{ $previewUrl }}" alt="Document Preview" class="w-full h-auto rounded">
                </div>
            @else
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-800 text-sm">
                    Preview for <span class="font-semibold">{{ strtoupper($extension ?: 'FILE') }}</span> is not supported.
                    For secure inline viewing, use PDF or image files.
                </div>
            @endif
        </div>
    </div>

    @if($isPdf)
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
        <script>
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

            const url = @json($previewUrl);
            const canvas = document.getElementById('pdfCanvas');
            const ctx = canvas.getContext('2d');
            const pageNumEl = document.getElementById('pageNum');
            const pageCountEl = document.getElementById('pageCount');
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');

            let pdfDoc = null;
            let pageNum = 1;
            let isRendering = false;
            let pendingPage = null;

            function renderPage(num) {
                isRendering = true;
                pdfDoc.getPage(num).then((page) => {
                    const viewport = page.getViewport({ scale: 1.3 });
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    const renderContext = {
                        canvasContext: ctx,
                        viewport: viewport
                    };

                    page.render(renderContext).promise.then(() => {
                        isRendering = false;
                        if (pendingPage !== null) {
                            renderPage(pendingPage);
                            pendingPage = null;
                        }
                    });

                    pageNumEl.textContent = num;
                });
            }

            function queueRenderPage(num) {
                if (isRendering) {
                    pendingPage = num;
                } else {
                    renderPage(num);
                }
            }

            prevBtn.addEventListener('click', () => {
                if (pageNum <= 1) return;
                pageNum--;
                queueRenderPage(pageNum);
            });

            nextBtn.addEventListener('click', () => {
                if (pageNum >= pdfDoc.numPages) return;
                pageNum++;
                queueRenderPage(pageNum);
            });

            document.addEventListener('contextmenu', function (event) {
                event.preventDefault();
            });

            pdfjsLib.getDocument(url).promise.then((pdfDoc_) => {
                pdfDoc = pdfDoc_;
                pageCountEl.textContent = pdfDoc.numPages;
                renderPage(pageNum);
            });
        </script>
    @endif
</body>
</html>
