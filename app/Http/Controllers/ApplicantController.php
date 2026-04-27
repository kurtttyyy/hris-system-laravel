<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantDegree;
use App\Models\ApplicantDocument;
use App\Models\Resignation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApplicantController extends Controller
{
    public function applicant_stores(Request $request){
        $attrs = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'bachelor_degree' => 'nullable|string',
            'bachelor_school_name' => 'nullable|string',
            'bachelor_year_finished' => 'nullable|string',
            'bachelor_degrees' => 'nullable|array|min:1',
            'bachelor_degrees.*.degree' => 'nullable|string',
            'bachelor_degrees.*.school_name' => 'nullable|string',
            'bachelor_degrees.*.year_finished' => 'nullable|string',
            'master_degrees' => 'nullable|array',
            'master_degrees.*.degree' => 'nullable|string',
            'master_degrees.*.school_name' => 'nullable|string',
            'master_degrees.*.year_finished' => 'nullable|string',
            'master_degree' => 'nullable|string',
            'master_school_name' => 'nullable|string',
            'master_year_finished' => 'nullable|string',
            'doctoral_degrees' => 'nullable|array',
            'doctoral_degrees.*.degree' => 'nullable|string',
            'doctoral_degrees.*.school_name' => 'nullable|string',
            'doctoral_degrees.*.year_finished' => 'nullable|string',
            'doctoral_degree' => 'nullable|string',
            'doctoral_school_name' => 'nullable|string',
            'doctoral_year_finished' => 'nullable|string',
            'position' => 'required|exists:open_positions,id',
            'fresh_graduate' => 'nullable|boolean',
            'experience_years' => 'required|string',
            'key_skills' => 'required|string',
            'documents' => 'required|array',
            'documents.*.file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'documents.0.file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'documents.1.file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'documents.2.file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'documents.3.file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'documents.7.file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'documents.*.type' => 'required',
            'university_address' => 'required',
            'work_position' => 'required_unless:fresh_graduate,1|nullable|string',
            'work_employer' => 'required_unless:fresh_graduate,1|nullable|string',
            'work_location' => 'required_unless:fresh_graduate,1|nullable|string',
            'work_duration' => 'required_unless:fresh_graduate,1|nullable|string',
        ]);

        $normalizedBachelorDegrees = collect($attrs['bachelor_degrees'] ?? [])
            ->map(function ($degree) {
                return [
                    'degree' => trim((string) ($degree['degree'] ?? '')),
                    'school_name' => trim((string) ($degree['school_name'] ?? '')) ?: null,
                    'year_finished' => trim((string) ($degree['year_finished'] ?? '')) ?: null,
                ];
            })
            ->filter(fn ($degree) => $degree['degree'] !== '')
            ->values();

        // Backward-compatible fallback for previous single-field payloads.
        if ($normalizedBachelorDegrees->isEmpty() && !empty($attrs['bachelor_degree'])) {
            $normalizedBachelorDegrees = collect([[
                'degree' => trim((string) $attrs['bachelor_degree']),
                'school_name' => trim((string) ($attrs['bachelor_school_name'] ?? '')) ?: null,
                'year_finished' => trim((string) ($attrs['bachelor_year_finished'] ?? '')) ?: null,
            ]]);
        }

        if ($normalizedBachelorDegrees->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['bachelor_degrees' => 'Please add at least one bachelor degree.']);
        }

        $primaryBachelor = $normalizedBachelorDegrees->first();
        $normalizedMasterDegrees = collect($attrs['master_degrees'] ?? [])
            ->map(function ($degree) {
                return [
                    'degree' => trim((string) ($degree['degree'] ?? '')),
                    'school_name' => trim((string) ($degree['school_name'] ?? '')) ?: null,
                    'year_finished' => trim((string) ($degree['year_finished'] ?? '')) ?: null,
                ];
            })
            ->filter(fn ($degree) => $degree['degree'] !== '')
            ->values();

        if ($normalizedMasterDegrees->isEmpty() && !empty($attrs['master_degree'])) {
            $normalizedMasterDegrees = collect([[
                'degree' => trim((string) $attrs['master_degree']),
                'school_name' => trim((string) ($attrs['master_school_name'] ?? '')) ?: null,
                'year_finished' => trim((string) ($attrs['master_year_finished'] ?? '')) ?: null,
            ]]);
        }

        $primaryMaster = $normalizedMasterDegrees->first();

        $normalizedDoctoralDegrees = collect($attrs['doctoral_degrees'] ?? [])
            ->map(function ($degree) {
                return [
                    'degree' => trim((string) ($degree['degree'] ?? '')),
                    'school_name' => trim((string) ($degree['school_name'] ?? '')) ?: null,
                    'year_finished' => trim((string) ($degree['year_finished'] ?? '')) ?: null,
                ];
            })
            ->filter(fn ($degree) => $degree['degree'] !== '')
            ->values();

        if ($normalizedDoctoralDegrees->isEmpty() && !empty($attrs['doctoral_degree'])) {
            $normalizedDoctoralDegrees = collect([[
                'degree' => trim((string) $attrs['doctoral_degree']),
                'school_name' => trim((string) ($attrs['doctoral_school_name'] ?? '')) ?: null,
                'year_finished' => trim((string) ($attrs['doctoral_year_finished'] ?? '')) ?: null,
            ]]);
        }

        $primaryDoctoral = $normalizedDoctoralDegrees->first();

        // Keep applicant identity in session so vacancy pages can hide jobs already applied to.
        session(['applicant_email' => $attrs['email']]);

        $normalizedEmail = Str::lower(trim((string) $attrs['email']));
        $rehireUser = $this->findLatestResignedEmployeeByEmail($normalizedEmail);

        $existingApplication = Applicant::whereRaw('LOWER(TRIM(email)) = ?', [$normalizedEmail])
            ->where('open_position_id', $attrs['position'])
            ->when($rehireUser, function ($query) use ($rehireUser) {
                $latestResignationDate = $this->latestApprovedResignationDateForUser((int) $rehireUser->id);
                if ($latestResignationDate) {
                    $query->where(function ($innerQuery) use ($latestResignationDate) {
                        $innerQuery
                            ->whereNull('created_at')
                            ->orWhere('created_at', '>', $latestResignationDate);
                    });
                }
            })
            ->exists();

        if ($existingApplication) {
            return redirect()->back()
                ->withInput()
                ->with('popup_error', 'You already submitted an application for this position using this email.');
        }

        DB::transaction(function () use ($request, $attrs, $primaryBachelor, $normalizedBachelorDegrees, $normalizedMasterDegrees, $normalizedDoctoralDegrees, $primaryMaster, $primaryDoctoral) {
            $normalizedEmail = Str::lower(trim((string) ($attrs['email'] ?? '')));
            $rehireUser = $this->findLatestResignedEmployeeByEmail($normalizedEmail);

            if ($rehireUser) {
                $this->releaseApplicantEmailForRehire($normalizedEmail);
            }

            $applicant_store = Applicant::create([
                'first_name' => $attrs['first_name'],
                'last_name' => $attrs['last_name'],
                'email' => $attrs['email'],
                'phone' => $attrs['phone'],
                'address' => $attrs['address'],
                'field_study' => $primaryBachelor['degree'],
                // Keep legacy columns populated using the first bachelor entry.
                'bachelor_degree' => $primaryBachelor['degree'],
                'bachelor_school_name' => $primaryBachelor['school_name'],
                'bachelor_year_finished' => $primaryBachelor['year_finished'],
                'master_degree' => $primaryMaster['degree'] ?? null,
                'master_school_name' => $primaryMaster['school_name'] ?? null,
                'master_year_finished' => $primaryMaster['year_finished'] ?? null,
                'doctoral_degree' => $primaryDoctoral['degree'] ?? null,
                'doctoral_school_name' => $primaryDoctoral['school_name'] ?? null,
                'doctoral_year_finished' => $primaryDoctoral['year_finished'] ?? null,
                'experience_years' => $attrs['experience_years'],
                'skills_n_expertise' => $attrs['key_skills'],
                'open_position_id' => $attrs['position'],
                'application_status' => 'pending',
                'fresh_graduate' => (bool) ($attrs['fresh_graduate'] ?? false),
                'university_address' => $attrs['university_address'],
                // Keep NOT NULL DB constraints satisfied for fresh graduates.
                'work_position' => !empty($attrs['fresh_graduate']) ? 'N/A' : ($attrs['work_position'] ?? 'N/A'),
                'work_employer' => !empty($attrs['fresh_graduate']) ? 'N/A' : ($attrs['work_employer'] ?? 'N/A'),
                'work_location' => !empty($attrs['fresh_graduate']) ? 'N/A' : ($attrs['work_location'] ?? 'N/A'),
                'work_duration' => !empty($attrs['fresh_graduate']) ? 'N/A' : ($attrs['work_duration'] ?? 'N/A'),
                'experience_years' => !empty($attrs['fresh_graduate']) ? '0-1' : $attrs['experience_years'],
            ]);

            foreach ($normalizedBachelorDegrees as $index => $degree) {
                ApplicantDegree::create([
                    'applicant_id' => $applicant_store->id,
                    'degree_level' => 'bachelor',
                    'degree_name' => $degree['degree'],
                    'school_name' => $degree['school_name'],
                    'year_finished' => $degree['year_finished'],
                    'sort_order' => $index,
                ]);
            }

            foreach ($normalizedMasterDegrees as $index => $degree) {
                ApplicantDegree::create([
                    'applicant_id' => $applicant_store->id,
                    'degree_level' => 'master',
                    'degree_name' => $degree['degree'],
                    'school_name' => $degree['school_name'],
                    'year_finished' => $degree['year_finished'],
                    'sort_order' => $index,
                ]);
            }

            foreach ($normalizedDoctoralDegrees as $index => $degree) {
                ApplicantDegree::create([
                    'applicant_id' => $applicant_store->id,
                    'degree_level' => 'doctorate',
                    'degree_name' => $degree['degree'],
                    'school_name' => $degree['school_name'],
                    'year_finished' => $degree['year_finished'],
                    'sort_order' => $index,
                ]);
            }

            foreach ((array) $request->input('documents', []) as $index => $docMeta) {
                $type = $docMeta['type'] ?? null;
                $file = $request->file("documents.$index.file");

                if (!$type || !$file || !$file->isValid()) {
                    continue;
                }

                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                $file->storeAs('uploads', $fileName, 'public');

                ApplicantDocument::create([
                    'applicant_id' => $applicant_store->id,
                    'type'         => $type,
                    'filename'     => $file->getClientOriginalName(),
                    'filepath'     => 'uploads/' . $fileName,
                    'mime_type'    => $file->getMimeType(),
                    'size'         => $file->getSize(),
                ]);
            }
        });

        return redirect()->route('guest.index')
            ->with('success', 'Submitted successfully')
            ->with('show_rating_modal', true);
    }

    public function store_rating(Request $request)
    {
        $attrs = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $applicantEmail = session('applicant_email');
        if (!$applicantEmail) {
            return redirect()->route('guest.index')
                ->with('popup_error', 'Unable to save rating. Please submit an application first.');
        }

        $applicant = Applicant::query()
            ->whereRaw('LOWER(email) = ?', [Str::lower($applicantEmail)])
            ->latest('id')
            ->first();

        if (!$applicant) {
            return redirect()->route('guest.index')
                ->with('popup_error', 'Unable to save rating. Applicant record was not found.');
        }

        $applicant->update([
            'starRatings' => (string) $attrs['rating'],
        ]);

        return redirect()->route('guest.index')
            ->with('success', 'Thank you for rating the system.');
    }

    public function display_application(Request $request){
        $attrs = $request->validate([
            'email' => 'required|email',
        ]);

        session(['applicant_email' => $attrs['email']]);

        $applicantsQuery = Applicant::with([
            'position',
            'degrees' => function ($query) {
                $query->orderBy('degree_level')->orderBy('sort_order');
            },
            'documents' => function ($query) {
                $query->orderByDesc('created_at');
            },
        ]);
        $this->applyApplicantEmailHistoryFilter($applicantsQuery, (string) $attrs['email']);

        if (!(clone $applicantsQuery)->exists()) {
            return redirect('/');
        }

        $applicants = $applicantsQuery
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(function (Applicant $applicant) use ($attrs) {
                $applicant->setAttribute(
                    'is_email_history_match',
                    strtolower(trim((string) ($applicant->email ?? ''))) !== strtolower(trim((string) ($attrs['email'] ?? '')))
                );

                return $applicant;
            });


        return view('guest.application', compact('applicants'));
    }

    private function findLatestResignedEmployeeByEmail(string $email): ?User
    {
        if ($email === '') {
            return null;
        }

        return User::query()
            ->whereRaw('LOWER(TRIM(email)) = ?', [$email])
            ->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('resignations')
                    ->whereColumn('resignations.user_id', 'users.id')
                    ->whereRaw("LOWER(TRIM(COALESCE(resignations.status, ''))) IN (?, ?)", ['approved', 'completed']);
            })
            ->orderByDesc('id')
            ->first();
    }

    private function latestApprovedResignationDateForUser(int $userId)
    {
        if ($userId <= 0) {
            return null;
        }

        $resignation = Resignation::query()
            ->where('user_id', $userId)
            ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) IN (?, ?)", ['approved', 'completed'])
            ->orderByDesc(DB::raw('COALESCE(effective_date, processed_at, submitted_at, created_at)'))
            ->orderByDesc('id')
            ->first();

        if (!$resignation) {
            return null;
        }

        return $resignation->effective_date
            ?? $resignation->processed_at
            ?? $resignation->submitted_at
            ?? $resignation->created_at;
    }

    private function releaseApplicantEmailForRehire(string $email): void
    {
        if ($email === '') {
            return;
        }

        Applicant::query()
            ->whereRaw('LOWER(TRIM(email)) = ?', [$email])
            ->orderByDesc('id')
            ->get()
            ->each(function (Applicant $applicant) {
                $archivedEmail = $this->buildArchivedApplicantEmail(
                    (string) ($applicant->email ?? ''),
                    (int) $applicant->id
                );

                if ($archivedEmail !== '' && $archivedEmail !== $applicant->email) {
                    $applicant->forceFill([
                        'email' => $archivedEmail,
                    ])->save();
                }
            });
    }

    private function buildArchivedApplicantEmail(string $email, int $applicantId): string
    {
        $trimmedEmail = trim($email);
        if ($trimmedEmail === '' || $applicantId <= 0) {
            return $trimmedEmail;
        }

        $parts = explode('@', $trimmedEmail, 2);
        $local = trim((string) ($parts[0] ?? ''));
        $domain = trim((string) ($parts[1] ?? 'archived.local'));
        if ($local === '') {
            $local = 'archived-applicant';
        }
        if ($domain === '') {
            $domain = 'archived.local';
        }

        $suffix = '.archived.'.$applicantId;
        $maxLocalLength = max(1, 255 - strlen($domain) - 1 - strlen($suffix));
        $safeLocal = substr($local, 0, $maxLocalLength);

        return $safeLocal.$suffix.'@'.$domain;
    }

    private function applyApplicantEmailHistoryFilter($query, string $email): void
    {
        $normalizedEmail = strtolower(trim($email));
        if ($normalizedEmail === '') {
            $query->whereRaw('1 = 0');
            return;
        }

        $parts = explode('@', $normalizedEmail, 2);
        $local = trim((string) ($parts[0] ?? ''));
        $domain = trim((string) ($parts[1] ?? ''));

        $query->where(function ($innerQuery) use ($normalizedEmail, $local, $domain) {
            $innerQuery->whereRaw('LOWER(TRIM(email)) = ?', [$normalizedEmail]);

            if ($local !== '' && $domain !== '') {
                $innerQuery->orWhereRaw('LOWER(TRIM(email)) LIKE ?', [$local.'.archived.%@'.$domain]);
            }
        });
    }
}
