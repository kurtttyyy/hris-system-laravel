<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantDocument;
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
            'email' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'bachelor_degree' => 'required|string',
            'bachelor_school_name' => 'required|string',
            'bachelor_year_finished' => 'required|string',
            'master_degree' => 'nullable|string',
            'master_school_name' => 'required_with:master_degree|nullable|string',
            'master_year_finished' => 'required_with:master_degree|nullable|string',
            'doctoral_degree' => 'nullable|string',
            'doctoral_school_name' => 'required_with:doctoral_degree|nullable|string',
            'doctoral_year_finished' => 'required_with:doctoral_degree|nullable|string',
            'position' => 'required|exists:open_positions,id',
            'fresh_graduate' => 'nullable|boolean',
            'experience_years' => 'required|string',
            'key_skills' => 'required|string',
            'documents' => 'required|array',
            'documents.*.file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'documents.*.type' => 'required',
            'university_address' => 'required',
            'work_position' => 'required_unless:fresh_graduate,1|nullable|string',
            'work_employer' => 'required_unless:fresh_graduate,1|nullable|string',
            'work_location' => 'required_unless:fresh_graduate,1|nullable|string',
            'work_duration' => 'required_unless:fresh_graduate,1|nullable|string',
            'experience_years' => 'required',
        ]);

        // Keep applicant identity in session so vacancy pages can hide jobs already applied to.
        session(['applicant_email' => $attrs['email']]);

        $existingApplication = Applicant::whereRaw('LOWER(email) = ?', [Str::lower($attrs['email'])])
            ->exists();

        if ($existingApplication) {
            return redirect()->route('guest.index')
                ->with('popup_error', 'You can only apply once using the same email address.');
        }

        $applicant_store = Applicant::create([
            'first_name' => $attrs['first_name'],
            'last_name' => $attrs['last_name'],
            'email' => $attrs['email'],
            'phone' => $attrs['phone'],
            'address' => $attrs['address'],
            'field_study' => $attrs['bachelor_degree'],
            'bachelor_degree' => $attrs['bachelor_degree'],
            'bachelor_school_name' => $attrs['bachelor_school_name'],
            'bachelor_year_finished' => $attrs['bachelor_year_finished'],
            'master_degree' => $attrs['master_degree'] ?? null,
            'master_school_name' => $attrs['master_school_name'] ?? null,
            'master_year_finished' => $attrs['master_year_finished'] ?? null,
            'doctoral_degree' => $attrs['doctoral_degree'] ?? null,
            'doctoral_school_name' => $attrs['doctoral_school_name'] ?? null,
            'doctoral_year_finished' => $attrs['doctoral_year_finished'] ?? null,
            'experience_years' => $attrs['experience_years'],
            'skills_n_expertise' => $attrs['key_skills'],
            'open_position_id' => $attrs['position'],
            'application_status' => 'pending',
            'fresh_graduate' => (bool) ($attrs['fresh_graduate'] ?? false),
            'university_address' => $attrs['university_address'],
            'work_position' => !empty($attrs['fresh_graduate']) ? null : ($attrs['work_position'] ?? null),
            'work_employer' => !empty($attrs['fresh_graduate']) ? null : ($attrs['work_employer'] ?? null),
            'work_location' => !empty($attrs['fresh_graduate']) ? null : ($attrs['work_location'] ?? null),
            'work_duration' => !empty($attrs['fresh_graduate']) ? null : ($attrs['work_duration'] ?? null),
            'experience_years' => !empty($attrs['fresh_graduate']) ? '0-1' : $attrs['experience_years'],
        ]);


        DB::transaction(function () use ($request, $applicant_store, &$filePaths) {

            foreach ($request->documents as $doc) {

                $file = $doc['file'];
                $type = $doc['type'];

                if ($file->isValid()) {

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

                    $filePaths[] = 'uploads/' . $fileName;
                }
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

        if (!Applicant::where('email', $attrs['email'])->exists()) {
            return redirect('/');
        }

        $applicants = Applicant::with(
            'position'
        )->where('email', $attrs['email'])->get();


        return view('guest.Application', compact('applicants'));
    }
}
