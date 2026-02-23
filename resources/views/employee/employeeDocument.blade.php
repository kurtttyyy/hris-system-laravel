<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Documents</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { font-family: Inter, system-ui, sans-serif; transition: margin-left 0.3s ease; }

        main {
            transition: margin-left 0.3s ease;
        }

        aside:not(:hover) ~ main {
            margin-left: 4rem;
        }

        aside:hover ~ main {
            margin-left: 14rem;
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    @include('components.employeeSideBar')

    <!-- MAIN -->
    <main class="flex-1 ml-16 transition-all duration-300">
    @include('components.employeeHeader.documentHeader')
<div class="p-4 md:p-8 space-y-8 pt-20">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- 201 FILE -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6">
                <h2 class="text-lg font-bold text-gray-900">201 File Submission</h2>
                <p class="text-gray-500 text-sm mt-1 mb-6">
                    Upload one document at a time for your employee 201 file.
                </p>

                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('employee.upload_documents') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label for="document_name" class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                        <input
                            id="document_name"
                            type="text"
                            name="document_name"
                            placeholder="e.g. NBI Clearance"
                            value="{{ old('document_name') }}"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <div class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-5">
                        <label for="uploadFile" class="block text-sm font-medium text-gray-700 mb-2">Attach File</label>
                        <input
                            id="uploadFile"
                            type="file"
                            name="uploadFile"
                            required
                            class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-200"
                        >
                        <p class="mt-2 text-xs text-gray-500">Accepted: PDF, XLSX, DOC, DOCX (max 5MB)</p>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save Document
                    </button>

                    @if(!empty($missingDocuments))
                        <div class="rounded-lg border border-rose-200 bg-rose-50 p-3">
                            <p class="text-xs font-semibold text-rose-700 mb-2">Missing Document(s)</p>
                            <ul class="list-disc ml-5 text-xs text-rose-700 space-y-1">
                                @foreach($missingDocuments as $missingDoc)
                                    <li>{{ $missingDoc }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-xs text-green-700">
                            Complete: no missing required documents.
                        </div>
                    @endif
                </form>

                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Latest Upload</h3>
                    @if($latestDocument)
                        <div class="rounded-xl border border-green-300 bg-green-50 p-4">
                            <div class="flex items-center gap-3">
                                <span class="icon bg-green-200 text-green-600 w-10 h-10 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-circle-check text-xl"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-800 truncate">{{ $latestDocument->type ?: ($latestDocument->filename ?? 'Document') }}</p>
                                    <p class="text-sm text-gray-600 truncate">{{ $latestDocument->filename }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Uploaded - {{ $latestDocument->formatted_size }} - {{ $latestDocument->formatted_created_at }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-500">
                            No uploaded document yet.
                        </div>
                    @endif
                </div>
            </div>

            <!-- PERSONAL DOCUMENTS --> // This section is for documents that are not part of the 201 file but the employee still wants to keep a copy in the system for their own reference. These documents will not be seen by admin and will not be included in the 201 file.
            <div class="bg-white border border-gray-200 rounded-2xl p-6">
                <h2 class="text-lg font-bold text-gray-900">My Personal Documents</h2>
                <p class="text-gray-500 text-sm mt-1 mb-6">
                    Access your uploaded documents
                </p>

                <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4">
                    <h3 class="text-sm font-semibold text-amber-900 mb-2">Documents Needed to Pass</h3>
                    @if(!empty($documentNotice))
                        <p class="text-xs text-amber-800 mb-3">{{ $documentNotice }}</p>
                    @endif

                    @if(!empty($requiredDocuments))
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach($requiredDocuments as $requiredDoc)
                                <span class="px-2 py-1 text-xs rounded-full bg-white border border-amber-200 text-amber-800">{{ $requiredDoc }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-amber-800 mb-3">No required document list has been set by admin yet.</p>
                    @endif

                </div>

                <div class="max-h-[34rem] overflow-y-auto pr-2">
                    @forelse($documents as $document)
                        @php
                            $fileType = strtoupper(pathinfo((string) ($document->filename ?? ''), PATHINFO_EXTENSION));
                            if ($fileType === '') {
                                $fileType = strtoupper(str_replace('application/', '', (string) ($document->mime_type ?? 'FILE')));
                            }
                        @endphp
                        <div class="bg-white border-2 border-gray-200 rounded-xl p-4 max-w mb-4 flex items-center gap-4">
                            <span class="doc-icon bg-blue-100 text-blue-600 w-10 h-10 rounded flex items-center justify-center">
                                <i class="fa-solid fa-file"></i>
                            </span>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $document->type ?: ($document->filename ?? 'Document') }}</p>
                                <p class="text-sm text-gray-500">{{ $fileType }} - {{ $document->formatted_size }} - {{ $document->formatted_created_at }}</p>
                            </div>
                            @php
                                $documentUrl = route('employee.employeeDocument.preview', ['id' => $document->id]);
                            @endphp
                            <a
                                href="{{ $documentUrl }}"
                                class="text-blue-600 font-medium hover:underline"
                            >
                                View
                            </a>
                        </div>
                    @empty
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-500">
                            No uploaded personal documents yet.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
    </main>
</div>

<style>
.nav { //
    @apply flex px-4 py-3 rounded-lg hover:bg-gray-100 cursor-pointer;
}
.nav.active {
    @apply bg-indigo-500 text-white;
}
.status {
    @apply flex items-center gap-4 border rounded-xl p-4 mb-3;
}
.uploaded {
    @apply bg-green-50 border-green-200;
}
.pending {
    @apply bg-yellow-50 border-yellow-200;
}
.icon {
    @apply w-9 h-9 rounded-full flex items-center justify-center;
}
.doc {
    @apply flex items-center gap-4 border rounded-xl p-4 mb-4;
}
.doc-icon {
    @apply w-12 h-12 rounded-xl flex items-center justify-center;
}
.view {
    @apply text-indigo-600 font-medium text-sm hover:underline;
}
</style>

<script>
    const sidebar = document.querySelector('aside');
    const main = document.querySelector('main');

    if (sidebar && main) {
        sidebar.addEventListener('mouseenter', function() {
            main.classList.remove('ml-16');
            main.classList.add('ml-56');
        });

        sidebar.addEventListener('mouseleave', function() {
            main.classList.remove('ml-56');
            main.classList.add('ml-16');
        });
    }

</script>

</body>
</html>
