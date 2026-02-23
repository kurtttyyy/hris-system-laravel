<!-- Documents -->
<div x-show="tab === 'documents'" x-transition class="w-full p-6 space-y-6">

    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.saveRequiredDocuments') }}" method="POST" class="bg-amber-50 border border-amber-200 rounded-2xl p-4 space-y-3">
        @csrf
        <input type="hidden" name="applicant_id" :value="selectedEmployee?.applicant?.id">
        <input type="hidden" name="user_id" :value="selectedEmployee?.id">

        <div class="flex items-center justify-between gap-4">
            <h3 class="font-semibold text-amber-900">Required Documents Notice</h3>
            <span
                class="text-xs px-2 py-1 rounded-full bg-rose-100 text-rose-700"
                x-show="(selectedEmployee?.applicant?.missing_documents ?? []).length"
                x-text="`${(selectedEmployee?.applicant?.missing_documents ?? []).length} Missing`"
            ></span>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Required Document Types</label>
            <textarea
                name="required_documents"
                rows="4"
                x-model="selectedEmployee.applicant.required_documents_text"
                placeholder="One per line, e.g.&#10;NBI Clearance&#10;TOR&#10;Medical Certificate"
                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            ></textarea>
            <p class="mt-1 text-xs text-gray-500">Employee will see these as required to pass.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notice</label>
            <textarea
                name="document_notice"
                rows="2"
                x-model="selectedEmployee.applicant.document_notice"
                placeholder="Example: Please submit missing documents before month end."
                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            ></textarea>
        </div>

        <div class="flex justify-end">
            <button
                type="submit"
                class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 disabled:opacity-50"
                :disabled="!selectedEmployee?.applicant?.id"
            >
                Save Notice
            </button>
        </div>

        <div x-show="(selectedEmployee?.applicant?.missing_documents ?? []).length" class="rounded-xl bg-white border border-rose-200 p-3">
            <p class="text-sm font-medium text-rose-700 mb-2">Missing Documents</p>
            <div class="flex flex-wrap gap-2">
                <template x-for="requiredDoc in (selectedEmployee?.applicant?.missing_documents ?? [])" :key="requiredDoc">
                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-rose-100 text-rose-700">
                        <span x-text="requiredDoc"></span>
                        <button
                            type="button"
                            class="ml-1 text-rose-700 hover:text-rose-900"
                            title="Remove missing requirement"
                            @click.prevent="removeMissingDocumentNeed(requiredDoc)"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </span>
                </template>
            </div>
            <p class="mt-2 text-xs text-gray-500">Click X then press Save Notice to apply permanently.</p>
        </div>
    </form>

    <form action="{{ route('admin.addDocument') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="applicant_id" :value="selectedEmployee?.applicant?.id">
        <input type="hidden" name="user_id" :value="selectedEmployee?.id">

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Document Name</label>
            <input
                type="text"
                name="document_name"
                placeholder="e.g. Resume, Offer Letter"
                required
                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
        </div>

        <div class="border-2 border-dashed border-indigo-200 bg-indigo-50 rounded-xl p-8 text-center">
            <div class="text-3xl mb-2 text-indigo-600"><i class="fa-solid fa-cloud-arrow-up"></i></div>
            <h3 class="font-semibold text-gray-800">Upload New Document</h3>
            <p class="text-sm text-gray-500 mb-4">Drag and drop files here or click to browse</p>

            <input
                type="file"
                name="documents"
                accept=".pdf,.doc,.docx"
                required
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition"
            >
        </div>

        <div class="mt-4 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save</button>
        </div>
    </form>

    <div class="bg-gray-50 rounded-2xl p-4 shadow-inner">
        <h3 class="font-semibold text-gray-800 mb-3">All Documents</h3>

        <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
            <template x-for="doc in (selectedEmployee?.applicant?.documents ?? [])" :key="doc.id">
                <div class="bg-white rounded-xl p-4 flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-red-100 text-red-600">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800" x-text="doc.type"></p>
                            <p
                                class="text-xs text-gray-500"
                                x-text="doc.filename + ' | ' + (doc.formatted_size ?? doc.size) + ' | ' + (doc.formatted_created_at ?? '')"
                            ></p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a
                            class="text-gray-500 hover:text-indigo-600"
                            :href="`/storage/${doc.filepath}`"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>

                        <a
                            class="text-gray-500 hover:text-indigo-600"
                            :href="`/storage/${doc.filepath}`"
                            :download="doc.filename"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 12v8m0 0l-4-4m4 4l4-4M12 4v8" />
                            </svg>
                        </a>
                    </div>
                </div>
            </template>
            <p
                x-show="!selectedEmployee?.applicant?.documents?.length"
                class="text-sm text-gray-500"
            >
                No documents uploaded.
            </p>
        </div>
    </div>

</div>
