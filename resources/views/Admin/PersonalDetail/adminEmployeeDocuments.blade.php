<!-- Documents -->
<div x-show="tab === 'documents'" x-transition class="w-full p-6 space-y-6">
    <style>
        .doc-archive-panel {
            background:
                radial-gradient(circle at top right, rgba(14, 165, 233, 0.12), transparent 34%),
                radial-gradient(circle at bottom left, rgba(245, 158, 11, 0.08), transparent 30%),
                linear-gradient(180deg, #fbfffd 0%, #f7fafc 100%);
        }

        .doc-folder-card {
            position: relative;
            overflow: hidden;
            transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
        }

        .doc-folder-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.72), rgba(255, 255, 255, 0));
            pointer-events: none;
            opacity: 0.9;
        }

        .doc-folder-card:hover {
            transform: translateY(-4px);
        }

        .doc-folder-unfiled {
            background: linear-gradient(180deg, rgba(255, 251, 235, 0.98), rgba(255, 255, 255, 1));
            border-color: rgba(245, 158, 11, 0.45);
            box-shadow: 0 10px 26px rgba(245, 158, 11, 0.12);
        }

        .doc-folder-regular {
            background: linear-gradient(180deg, rgba(240, 249, 255, 0.98), rgba(255, 255, 255, 1));
            border-color: rgba(56, 189, 248, 0.4);
            box-shadow: 0 10px 26px rgba(14, 165, 233, 0.1);
        }

        .doc-folder-count {
            min-width: 1.9rem;
            justify-content: center;
            border-radius: 999px;
            padding: 0.28rem 0.62rem;
            font-size: 0.74rem;
            font-weight: 800;
            line-height: 1;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
        }

        .doc-folder-count-amber {
            background: #fff7ed;
            color: #b45309;
            border: 1px solid #fed7aa;
        }

        .doc-folder-count-sky {
            background: #f0f9ff;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .doc-folder-icon {
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.85), 0 8px 18px rgba(15, 23, 42, 0.1);
        }
    </style>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid items-start gap-6 2xl:grid-cols-[minmax(0,1.02fr)_minmax(340px,0.98fr)]">
        <div class="min-w-0 space-y-6">
            <form action="{{ route('admin.saveRequiredDocuments') }}" method="POST" class="rounded-[1.65rem] border border-amber-200/80 bg-[linear-gradient(180deg,rgba(255,251,235,0.98),rgba(255,255,255,0.98))] p-5 shadow-[0_18px_42px_rgba(217,119,6,0.10)] ring-1 ring-white/70 md:p-6">
                @csrf
                <input type="hidden" name="applicant_id" :value="selectedEmployee?.applicant?.id">
                <input type="hidden" name="user_id" :value="selectedEmployee?.id">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0">
                        <div class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-amber-700">
                            Requirement Rules
                        </div>
                        <h3 class="mt-4 text-xl font-black tracking-tight text-slate-900">Required documents notice</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Define what the employee still needs to submit and add an admin note for follow-up.
                        </p>
                    </div>
                    <span
                        class="inline-flex w-fit shrink-0 rounded-full border border-rose-200 bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700"
                        x-show="(selectedEmployee?.applicant?.missing_documents ?? []).length"
                        x-text="`${(selectedEmployee?.applicant?.missing_documents ?? []).length} Missing`"
                    ></span>
                </div>

                <div class="mt-5 space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Required Document Types</label>
                        <textarea
                            name="required_documents"
                            rows="7"
                            x-model="selectedEmployee.applicant.required_documents_text"
                            placeholder="One per line, e.g.&#10;NBI Clearance&#10;TOR&#10;Medical Certificate"
                            class="min-h-[13rem] w-full resize-y rounded-2xl border border-amber-200 bg-white/95 px-4 py-3 text-sm leading-6 text-slate-700 outline-none transition focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                        ></textarea>
                        <p class="mt-1 text-xs text-slate-500">Enter one document type per line.</p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Admin Notice</label>
                        <textarea
                            name="document_notice"
                            rows="3"
                            x-model="selectedEmployee.applicant.document_notice"
                            placeholder="Example: Please submit missing documents before month end."
                            class="w-full rounded-2xl border border-amber-200 bg-white/95 px-4 py-3 text-sm leading-6 text-slate-700 outline-none transition focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                        ></textarea>
                    </div>
                </div>

                <div x-show="(selectedEmployee?.applicant?.missing_documents ?? []).length" class="mt-5 rounded-[1.25rem] border border-rose-200 bg-white/95 p-4 shadow-sm">
                    <p class="text-sm font-semibold text-rose-700">Missing Documents</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <template x-for="requiredDoc in (selectedEmployee?.applicant?.missing_documents ?? [])" :key="requiredDoc">
                            <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-3 py-1 text-xs font-medium text-rose-700">
                                <span x-text="requiredDoc"></span>
                                <button
                                    type="button"
                                    class="text-rose-700 transition hover:text-rose-900"
                                    title="Remove missing requirement"
                                    @click.prevent="removeMissingDocumentNeed(requiredDoc)"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </span>
                        </template>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Click the `x`, then save to apply the change permanently.</p>
                </div>

                <div class="mt-5 flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600 disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="!selectedEmployee?.applicant?.id"
                    >
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        Save Notice
                    </button>
                </div>
            </form>

            <form action="{{ route('admin.addDocument') }}" method="POST" enctype="multipart/form-data" class="rounded-[1.65rem] border border-emerald-200/80 bg-[linear-gradient(180deg,rgba(255,255,255,0.98),rgba(240,253,248,0.92))] p-5 shadow-[0_18px_42px_rgba(15,23,42,0.07)] ring-1 ring-white/70 md:p-6">
                @csrf

                <input type="hidden" name="applicant_id" :value="selectedEmployee?.applicant?.id">
                <input type="hidden" name="user_id" :value="selectedEmployee?.id">

                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-700">
                            Upload Desk
                        </div>
                        <h3 class="mt-4 text-xl font-black tracking-tight text-slate-900">Upload new document</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Add a named document to this employee profile for preview and download.
                        </p>
                    </div>
                    <div class="hidden aspect-square h-14 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 sm:flex">
                        <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                    </div>
                </div>

                <div class="mt-5">
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Document Name</label>
                    <input
                        type="text"
                        name="document_name"
                        placeholder="e.g. Resume, Offer Letter"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50/90 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:bg-white focus:ring-2 focus:ring-emerald-100"
                    >
                </div>

                <label class="mt-5 block cursor-pointer overflow-hidden rounded-[1.5rem] border-2 border-dashed border-emerald-200 bg-[linear-gradient(180deg,rgba(236,253,245,0.96),rgba(255,255,255,0.98))] px-6 py-10 text-center transition hover:-translate-y-0.5 hover:border-emerald-300 hover:bg-emerald-50/90 hover:shadow-[0_18px_30px_rgba(16,185,129,0.08)]">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-white text-emerald-600 shadow-sm">
                        <i class="fa-solid fa-file-arrow-up text-2xl"></i>
                    </div>
                    <h4 class="mt-4 text-lg font-bold text-slate-900">Choose a document file</h4>
                    <p class="mt-2 text-sm text-slate-500">PDF, DOC, and DOCX files are supported.</p>

                    <input
                        type="file"
                        name="documents"
                        accept=".pdf,.doc,.docx"
                        required
                        class="mt-5 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-emerald-500 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-emerald-600"
                    >
                </label>

                <div class="mt-5 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                        <i class="fa-solid fa-upload text-xs"></i>
                        Save Document
                    </button>
                </div>
            </form>
        </div>

        <div class="doc-archive-panel min-w-0 rounded-[1.65rem] border border-slate-200/80 p-5 shadow-[0_18px_42px_rgba(15,23,42,0.07)] ring-1 ring-white/80 md:p-6 2xl:sticky 2xl:top-28 2xl:self-start">
            <div class="flex flex-col gap-4 border-b border-slate-200/80 pb-5 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-600">
                        Archive
                    </div>
                    <h3 class="mt-4 text-xl font-black tracking-tight text-slate-900" x-text="selectedDocumentFolderKey() === 'all' ? 'Document folders' : selectedDocumentFolderName()"></h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        <span x-show="selectedDocumentFolderKey() === 'all'">Open a folder to review the files saved by the employee.</span>
                        <span x-show="selectedDocumentFolderKey() !== 'all'">Review uploaded files inside the selected folder, then preview or download them.</span>
                    </p>
                </div>
                <div class="rounded-[1.1rem] border border-slate-200 bg-white/90 px-4 py-3 text-sm text-slate-600 shadow-sm backdrop-blur-sm">
                    <span class="font-extrabold text-slate-900" x-text="currentDocumentCount()"></span>
                    <span x-text="selectedDocumentFolderKey() === 'all' ? 'file(s) total' : 'file(s) inside'"></span>
                </div>
            </div>

            <div x-show="selectedDocumentFolderKey() !== 'all'" class="mt-5 flex items-center justify-between gap-3 rounded-[1.1rem] border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm">
                <div class="min-w-0">
                    <p class="font-semibold text-slate-900">Viewing folder</p>
                    <p class="truncate text-xs text-slate-500" x-text="selectedDocumentFolderName()"></p>
                </div>
                <button
                    type="button"
                    @click="openDocumentFolder('all')"
                    class="inline-flex shrink-0 items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-100"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to folders
                </button>
            </div>

            <div x-show="selectedDocumentFolderKey() === 'all'" class="mt-5 grid grid-cols-1 gap-4 xl:grid-cols-2">
                <button
                    type="button"
                    @click="openDocumentFolder('unfiled')"
                    class="doc-folder-card doc-folder-unfiled group min-h-[9.25rem] rounded-[1.35rem] border p-4 text-left"
                >
                    <div class="relative z-[1] flex h-full flex-col justify-between gap-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-base font-bold text-slate-900">Unfiled</p>
                                    <span class="doc-folder-count doc-folder-count-amber inline-flex items-center" x-text="selectedEmployee?.applicant?.unfiled_count ?? 0"></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="doc-folder-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                                <i class="fa-solid fa-inbox"></i>
                            </div>
                        </div>
                        <div class="flex items-center justify-end text-[11px] font-semibold uppercase tracking-[0.15em] text-amber-700/80 transition group-hover:text-amber-800">
                            Review Folder
                        </div>
                    </div>
                </button>

                <template x-for="folder in applicantFolders()" :key="folder.key">
                    <button
                        type="button"
                        @click="openDocumentFolder(folder.key)"
                        class="doc-folder-card doc-folder-regular group min-h-[9.25rem] rounded-[1.35rem] border p-4 text-left"
                    >
                        <div class="relative z-[1] flex h-full flex-col justify-between gap-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="truncate text-base font-bold text-slate-900" x-text="folder.name"></p>
                                        <span class="doc-folder-count doc-folder-count-sky inline-flex items-center" x-text="folder.count ?? 0"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="doc-folder-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-sky-100 text-sky-700">
                                    <i class="fa-solid fa-folder"></i>
                                </div>
                            </div>
                            <div class="flex items-center justify-end text-[11px] font-semibold uppercase tracking-[0.15em] text-sky-700/80 transition group-hover:text-sky-800">
                                Open Folder
                            </div>
                        </div>
                    </button>
                </template>

                <div
                    x-show="(applicantFolders().length === 0) && ((selectedEmployee?.applicant?.unfiled_count ?? 0) === 0)"
                    class="sm:col-span-2 rounded-[1.25rem] border border-dashed border-slate-300 bg-white px-5 py-10 text-center shadow-sm"
                >
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                        <i class="fa-regular fa-folder-open text-xl"></i>
                    </div>
                    <p class="mt-4 text-sm font-semibold text-slate-700">No folders or files yet.</p>
                    <p class="mt-1 text-xs text-slate-500">Once the employee uploads files into folders, they will appear here.</p>
                </div>
            </div>

            <div x-show="selectedDocumentFolderKey() !== 'all'" class="mt-5 max-h-[34rem] space-y-3 overflow-y-auto pr-1">
                <template x-for="doc in displayedApplicantDocuments()" :key="doc.id">
                    <div class="rounded-[1.25rem] border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex min-w-0 items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[linear-gradient(180deg,#ffe4e6,#fff1f2)] text-rose-600">
                                    <i class="fa-solid fa-file-lines"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="truncate text-sm font-bold leading-5 text-slate-900" x-text="doc.type"></p>
                                        <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                            Uploaded
                                        </span>
                                        <span
                                            x-show="documentIsNew(doc)"
                                            class="inline-flex rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700"
                                        >
                                            New
                                        </span>
                                        <span
                                            x-show="documentIsPreviousApplication(doc)"
                                            class="inline-flex rounded-full border border-slate-300 bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700"
                                        >
                                            Previous Application
                                        </span>
                                    </div>
                                    <p class="mt-1 break-all text-xs text-slate-500 underline-offset-2" x-text="doc.filename"></p>
                                    <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-500">
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1" x-text="doc.formatted_size ?? doc.size"></span>
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1" x-text="doc.formatted_created_at ?? 'No upload date'"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-2">
                                <a
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:-translate-y-0.5 hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                :href="`/storage/${doc.filepath}`"
                                target="_blank"
                                rel="noopener noreferrer"
                                title="View document"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:-translate-y-0.5 hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700"
                                :href="`/storage/${doc.filepath}`"
                                :download="doc.filename"
                                title="Download document"
                                >
                                    <i class="fa-solid fa-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>

                <div
                    x-show="displayedApplicantDocuments().length === 0"
                    class="rounded-[1.25rem] border border-dashed border-slate-300 bg-white px-5 py-10 text-center shadow-sm"
                >
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                        <i class="fa-regular fa-folder-open text-xl"></i>
                    </div>
                    <p class="mt-4 text-sm font-semibold text-slate-700">No files inside this folder.</p>
                    <p class="mt-1 text-xs text-slate-500">Select another folder or wait for uploaded files to appear here.</p>
                </div>
            </div>
        </div>
    </div>

</div>
