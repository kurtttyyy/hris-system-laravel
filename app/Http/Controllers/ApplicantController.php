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
            'education' => 'required|string',
            'field_study' => 'required|string',
            'position' => 'required|exists:open_positions,id',
            'experience_years' => 'required|string',
            'key_skills' => 'required|string',
            'documents' => 'required|array',
            'documents.*.file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'documents.*.type' => 'required',
            'university_name' => 'required',
            'university_address' => 'required',
            'year_complete' => 'required',
            'work_position' => 'required',
            'work_employer' => 'required',
            'work_location' => 'required',
            'work_duration' => 'required',
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
            'education_attainment' => $attrs['education'],
            'field_study' => $attrs['field_study'],
            'experience_years' => $attrs['experience_years'],
            'skills_n_expertise' => $attrs['key_skills'],
            'open_position_id' => $attrs['position'],
            'application_status' => 'pending',
            'university_name' => $attrs['university_name'],
            'university_address' => $attrs['university_address'],
            'year_complete' => $attrs['year_complete'],
            'work_position' => $attrs['work_position'],
            'work_employer' => $attrs['work_employer'],
            'work_location' => $attrs['work_location'],
            'work_duration' => $attrs['work_duration'],
            'experience_years' => $attrs['experience_years'],
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

        return redirect()->route('guest.index')->with('success', 'Submitted successfully');
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
