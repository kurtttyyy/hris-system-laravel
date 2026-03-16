<!-- Documents -->
<div x-show="tab === 'documents'" x-transition class="w-full p-6 space-y-6">

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

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1.2fr)]">
        <div class="space-y-6">
            <form action="{{ route('admin.saveRequiredDocuments') }}" method="POST" class="rounded-[1.5rem] border border-amber-200 bg-[linear-gradient(180deg,rgba(255,251,235,0.98),rgba(255,255,255,0.98))] p-5 shadow-[0_16px_34px_rgba(217,119,6,0.08)]">
                @csrf
                <input type="hidden" name="applicant_id" :value="selectedEmployee?.applicant?.id">
                <input type="hidden" name="user_id" :value="selectedEmployee?.id">

                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-amber-700">
                            Requirement Rules
                        </div>
                        <h3 class="mt-4 text-xl font-black tracking-tight text-slate-900">Required documents notice</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Define what the employee still needs to submit and add an admin note for follow-up.
                        </p>
                    </div>
                    <span
                        class="inline-flex rounded-full border border-rose-200 bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700"
                        x-show="(selectedEmployee?.applicant?.missing_documents ?? []).length"
                        x-text="`${(selectedEmployee?.applicant?.missing_documents ?? []).length} Missing`"
                    ></span>
                </div>

                <div class="mt-5 space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Required Document Types</label>
                        <textarea
                            name="required_documents"
                            rows="5"
                            x-model="selectedEmployee.applicant.required_documents_text"
                            placeholder="One per line, e.g.&#10;NBI Clearance&#10;TOR&#10;Medical Certificate"
                            class="w-full rounded-2xl border border-amber-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
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
                            class="w-full rounded-2xl border border-amber-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                        ></textarea>
                    </div>
                </div>

                <div x-show="(selectedEmployee?.applicant?.missing_documents ?? []).length" class="mt-5 rounded-2xl border border-rose-200 bg-white p-4">
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

            <form action="{{ route('admin.addDocument') }}" method="POST" enctype="multipart/form-data" class="rounded-[1.5rem] border border-emerald-200 bg-white p-5 shadow-[0_18px_40px_rgba(15,23,42,0.06)]">
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
                    <div class="hidden h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 sm:flex">
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
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none focus:border-emerald-400 focus:bg-white focus:ring-2 focus:ring-emerald-100"
                    >
                </div>

                <label class="mt-5 block cursor-pointer overflow-hidden rounded-[1.5rem] border-2 border-dashed border-emerald-200 bg-[linear-gradient(180deg,rgba(236,253,245,0.92),rgba(255,255,255,0.98))] px-6 py-10 text-center transition hover:border-emerald-300 hover:bg-emerald-50">
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

        <div class="rounded-[1.5rem] border border-slate-200 bg-[linear-gradient(180deg,#fbfffd_0%,#f8fafc_100%)] p-5 shadow-[0_18px_40px_rgba(15,23,42,0.06)]">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-600">
                        Archive
                    </div>
                    <h3 class="mt-4 text-xl font-black tracking-tight text-slate-900">All documents</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Review uploaded files, then preview or download them from the employee record.
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600">
                    <span class="font-semibold text-slate-900" x-text="selectedEmployee?.applicant?.documents?.length ?? 0"></span>
                    file(s) available
                </div>
            </div>

            <div class="mt-5 max-h-[32rem] space-y-3 overflow-y-auto pr-1">
                <template x-for="doc in (selectedEmployee?.applicant?.documents ?? [])" :key="doc.id">
                    <div class="rounded-[1.25rem] border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex min-w-0 items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                                    <i class="fa-solid fa-file-lines"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="truncate text-sm font-bold text-slate-900" x-text="doc.type"></p>
                                        <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                            Uploaded
                                        </span>
                                    </div>
                                    <p class="mt-1 break-all text-xs text-slate-500" x-text="doc.filename"></p>
                                    <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-500">
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1" x-text="doc.formatted_size ?? doc.size"></span>
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1" x-text="doc.formatted_created_at ?? 'No upload date'"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-2">
                                <a
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:border-emerald-200 hover:text-emerald-700"
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
                    x-show="!selectedEmployee?.applicant?.documents?.length"
                    class="rounded-[1.25rem] border border-dashed border-slate-300 bg-white px-5 py-10 text-center"
                >
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                        <i class="fa-regular fa-folder-open text-xl"></i>
                    </div>
                    <p class="mt-4 text-sm font-semibold text-slate-700">No documents uploaded.</p>
                    <p class="mt-1 text-xs text-slate-500">Upload the first file from the panel on the left.</p>
                </div>
            </div>
        </div>
    </div>

</div>
