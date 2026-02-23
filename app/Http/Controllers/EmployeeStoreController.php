<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantDocument;
use App\Models\LeaveApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeStoreController extends Controller
{
    public function upload_store(Request $request){
        Log::info($request->all());
        $attrs = $request->validate([
            'document_name' => 'required|string|max:255',
            'uploadFile' => 'required|file|mimes:pdf,xlsx,doc,docx|max:5120',
        ]);

        $user = Auth::id();

        $applicant = Applicant::where('user_id', $user)
                                    ->where('application_status', 'Hired')->first();

        if (!$applicant) {
            return redirect()->back()->with('error', 'No hired applicant record found.');
        }

        $file = $request->file('uploadFile');

        if (!$file || !$file->isValid()) {
            return back()->withErrors(['uploadFile' => 'Invalid file upload.']);
        }

        $originalName = $file->getClientOriginalName();
        $mimeType     = $file->getMimeType();
        $size         = $file->getSize();

        $fileName = time() . '_' . $originalName;

        // Store file
        $filePath = $file->storeAs('uploads', $fileName, 'public');

        ApplicantDocument::create([
            'applicant_id' => $applicant->id,
            'type'         => $attrs['document_name'],
            'filename'     => $originalName,
            'filepath'     => $filePath, // already "uploads/filename"
            'mime_type'    => $mimeType,
            'size'         => $size,
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function leave_application_store(Request $request)
    {
        $attrs = $request->validate([
            'office_department' => 'nullable|string|max:255',
            'employee_name' => 'nullable|string|max:255',
            'filing_date' => 'nullable|date',
            'position' => 'nullable|string|max:255',
            'salary' => 'nullable|string|max:255',
            'leave_type' => 'nullable|string|max:50',
            'number_of_working_days' => 'nullable|numeric|min:0',
            'inclusive_dates' => 'nullable|string|max:255',
            'as_of_label' => 'nullable|string|max:255',
            'earned_date_label' => 'nullable|string|max:255',
            'beginning_vacation' => 'nullable|numeric|min:0',
            'beginning_sick' => 'nullable|numeric|min:0',
            'beginning_total' => 'nullable|numeric|min:0',
            'earned_vacation' => 'nullable|numeric|min:0',
            'earned_sick' => 'nullable|numeric|min:0',
            'earned_total' => 'nullable|numeric|min:0',
            'applied_vacation' => 'nullable|numeric|min:0',
            'applied_sick' => 'nullable|numeric|min:0',
            'applied_total' => 'nullable|numeric|min:0',
            'ending_vacation' => 'nullable|numeric|min:0',
            'ending_sick' => 'nullable|numeric|min:0',
            'ending_total' => 'nullable|numeric|min:0',
            'days_with_pay' => 'nullable|numeric|min:0',
            'days_without_pay' => 'nullable|numeric|min:0',
            'commutation' => 'nullable|string|max:50',
        ]);

        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $authUser->loadMissing('employee');

        $latestLeaveApplication = LeaveApplication::query()
            ->where('user_id', $authUser->id)
            ->orderByDesc('created_at')
            ->first();

        $beginningVacation = round((float) ($latestLeaveApplication?->ending_vacation ?? $attrs['beginning_vacation'] ?? 0), 1);
        $beginningSick = round((float) ($latestLeaveApplication?->ending_sick ?? $attrs['beginning_sick'] ?? 0), 1);
        $beginningTotal = round($beginningVacation + $beginningSick, 1);

        $earnedVacation = round((float) ($attrs['earned_vacation'] ?? 0), 1);
        $earnedSick = round((float) ($attrs['earned_sick'] ?? 0), 1);
        $earnedTotal = round($earnedVacation + $earnedSick, 1);

        $appliedVacation = round((float) ($attrs['applied_vacation'] ?? 0), 1);
        $appliedSick = round((float) ($attrs['applied_sick'] ?? 0), 1);
        $appliedTotal = round($appliedVacation + $appliedSick, 1);

        $endingVacation = round(max(($beginningVacation + $earnedVacation) - $appliedVacation, 0), 1);
        $endingSick = round(max(($beginningSick + $earnedSick) - $appliedSick, 0), 1);
        $endingTotal = round($endingVacation + $endingSick, 1);

        $record = LeaveApplication::create([
            'user_id' => $authUser->id,
            'employee_id' => (string) ($authUser->employee?->employee_id ?? ''),
            'office_department' => $attrs['office_department'] ?? null,
            'employee_name' => $attrs['employee_name'] ?? null,
            'filing_date' => $attrs['filing_date'] ?? null,
            'position' => $attrs['position'] ?? null,
            'salary' => $attrs['salary'] ?? null,
            'leave_type' => $attrs['leave_type'] ?? null,
            'number_of_working_days' => round((float) ($attrs['number_of_working_days'] ?? 0), 1),
            'inclusive_dates' => $attrs['inclusive_dates'] ?? null,
            'as_of_label' => $attrs['as_of_label'] ?? null,
            'earned_date_label' => $attrs['earned_date_label'] ?? null,
            'beginning_vacation' => $beginningVacation,
            'beginning_sick' => $beginningSick,
            'beginning_total' => $beginningTotal,
            'earned_vacation' => $earnedVacation,
            'earned_sick' => $earnedSick,
            'earned_total' => $earnedTotal,
            'applied_vacation' => $appliedVacation,
            'applied_sick' => $appliedSick,
            'applied_total' => $appliedTotal,
            'ending_vacation' => $endingVacation,
            'ending_sick' => $endingSick,
            'ending_total' => $endingTotal,
            'days_with_pay' => round((float) ($attrs['days_with_pay'] ?? 0), 1),
            'days_without_pay' => round((float) ($attrs['days_without_pay'] ?? 0), 1),
            'commutation' => $attrs['commutation'] ?? null,
            'status' => 'Pending',
        ]);

        return response()->json([
            'message' => 'Leave application saved.',
            'id' => $record->id,
        ]);
    }
}
