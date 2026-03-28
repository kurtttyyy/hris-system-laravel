<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesTabSession;
use App\Models\Applicant;
use App\Models\Resignation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterLoginController extends Controller
{
    use ResolvesTabSession;

    public function register_store(Request $request){
        Log::info($request);
        $attrs = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255',
            'confirmation_password' => 'required|string|max:255',
        ]);

        if($attrs['password'] != $attrs['confirmation_password']){
            return redirect()->back()
                ->withErrors([
                    'confirmation_password' => 'Password confirmation does not match.',
                ])
                ->withInput($request->except(['password', 'confirmation_password']));
        }

        $hire = 'hired';
        $normalizedEmail = strtolower(trim((string) $attrs['email']));

        $matchingApplicant = Applicant::query()
            ->whereRaw('LOWER(TRIM(email)) = ?', [$normalizedEmail])
            ->latest('id')
            ->first();

        if (!$matchingApplicant) {
            return redirect()->back()
                ->withErrors([
                    'email' => 'We could not find an application linked to this email address.',
                ])
                ->withInput($request->except(['password', 'confirmation_password']));
        }

        if (strtolower(trim((string) ($matchingApplicant->application_status ?? ''))) !== $hire) {
            return redirect()->back()
                ->withErrors([
                    'email' => 'Your account is not eligible for registration yet. You can create an account once your application status is marked as Hired by HR.',
                ])
                ->withInput($request->except(['password', 'confirmation_password']));
        }

        $existingUser = User::query()
            ->whereRaw('LOWER(TRIM(email)) = ?', [$normalizedEmail])
            ->first();

        $rehireUser = $this->resolveRehireUser($matchingApplicant, $existingUser);

        if ($existingUser && !$rehireUser) {
            return redirect()->back()
                ->withErrors([
                    'email' => 'An account with this email already exists. Please log in instead.',
                ])
                ->withInput($request->except(['password', 'confirmation_password']));
        }

        if ($rehireUser) {
            $rehireUser->update([
                'first_name' => $attrs['first_name'],
                'last_name' => $attrs['last_name'],
                'middle_name' => $attrs['middle_name'],
                'role' => 'Employee',
                'status' => 'Approved',
                'account_status' => 'Active',
                'email' => $attrs['email'],
                'password' => Hash::make($attrs['password']),
            ]);

            $user = $rehireUser->fresh();
        } else {
            $user = User::create([
                'first_name' => $attrs['first_name'],
                'last_name' => $attrs['last_name'],
                'middle_name' => $attrs['middle_name'],
                'role' => 'Employee',
                'status' => 'Pending',
                'account_status' => 'Active',
                'email' => $attrs['email'],
                'password' => Hash::make($attrs['password']),
            ]);
        }

        Applicant::whereRaw('LOWER(TRIM(email)) = ?', [$normalizedEmail])
                    ->whereRaw('LOWER(application_status) = ?', [$hire])
                    ->update([
                        'user_id' => $user->id,
                    ]);

        return redirect()->route('login');
    }

    public function login_store(Request $request)
    {
        $attrs = $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string',
            'tab_session' => 'nullable|string|max:120',
        ]);

        $user = User::query()
            ->where('email', $attrs['email'])
            ->first();

        if ($user && strtolower(trim((string) ($user->account_status ?? ''))) === 'inactive') {
            return back()
                ->withErrors([
                    'email' => 'Your account is inactive. Please contact HR.',
                ])
                ->withInput();
        }

        if (Auth::attempt([
            'email'    => $attrs['email'],
            'password' => $attrs['password'],
            'status'   => 'Approved',
        ])) {
            $user = Auth::user();
            $tabSession = $this->resolveTabSessionKey($request);
            $tabAuthUsers = $request->session()->get('tab_auth_users', []);

            if ($tabSession !== '' && $user) {
                $tabAuthUsers[$tabSession] = (int) $user->id;
                $request->session()->put('tab_auth_users', $tabAuthUsers);
            }

            Auth::logout();

            return match ($user->role) {
                'Employee' => redirect()->route('employee.employeeHome', $tabSession !== '' ? ['tab_session' => $tabSession] : []),
                'Admin'    => redirect()->route('admin.adminHome', $tabSession !== '' ? ['tab_session' => $tabSession] : []),
                default    => redirect()->route('login_display')->with('error', 'Unauthorized role'),
            };
        }

        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
            ->withInput();
    }

    public function logout(Request $request)
    {
        $tabSession = $this->resolveTabSessionKey($request);
        $tabAuthUsers = $request->session()->get('tab_auth_users', []);
        if ($tabSession !== '') {
            unset($tabAuthUsers[$tabSession]);
            $request->session()->put('tab_auth_users', $tabAuthUsers);
        } else {
            $request->session()->forget('tab_auth_users');
        }

        Auth::logout();
        $request->session()->regenerateToken();

        return redirect()->route('login_display', $tabSession !== '' ? ['tab_session' => $tabSession] : []);
    }

    private function resolveRehireUser(?Applicant $matchingApplicant, ?User $existingUser): ?User
    {
        if (!$matchingApplicant) {
            return null;
        }

        if ($existingUser && $this->userHasApprovedResignation((int) $existingUser->id)) {
            return $existingUser;
        }

        $linkedUserId = (int) ($matchingApplicant->user_id ?? 0);
        if ($linkedUserId <= 0) {
            return null;
        }

        $linkedUser = User::query()->find($linkedUserId);
        if ($linkedUser && $this->userHasApprovedResignation((int) $linkedUser->id)) {
            return $linkedUser;
        }

        return null;
    }

    private function userHasApprovedResignation(int $userId): bool
    {
        if ($userId <= 0) {
            return false;
        }

        return Resignation::query()
            ->where('user_id', $userId)
            ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) IN (?, ?)", ['approved', 'completed'])
            ->exists();
    }
}
