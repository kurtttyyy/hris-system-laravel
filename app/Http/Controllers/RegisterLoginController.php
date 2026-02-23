<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterLoginController extends Controller
{
    public function register_store(Request $request){
        Log::info($request);
        $attrs = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|max:255',
            'confirmation_password' => 'required|string|max:255',
        ]);

        if($attrs['password'] != $attrs['confirmation_password']){
            return redirect()->back()->with('error', 'Password does not match.');
        }

        $status = 'Pending';
        $role = 'Employee';
        $account_status = 'Active';

        $hire = 'hired';

        $find = Applicant::where('email', $attrs['email'])
                            ->whereRaw('LOWER(application_status) = ?', [$hire])
                            ->exists();

        if(!$find){
            return redirect()->back()->with('error', 'Registration not valid.');
        }

        $user = User::create([
            'first_name' => $attrs['first_name'],
            'last_name' => $attrs['last_name'],
            'middle_name' => $attrs['middle_name'],
            'role' => $role,
            'status' => $status,
            'account_status' => $account_status,
            'email' => $attrs['email'],
            'password' => Hash::make($attrs['password']),
        ]);

        Applicant::where('email', $user->email)
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
        ]);

        if (Auth::attempt([
            'email'    => $attrs['email'],
            'password' => $attrs['password'],
            'status'   => 'Approved',
        ])) {

            $request->session()->regenerate();

            $user = Auth::user();

            return match ($user->role) {
                'Employee' => redirect()->route('employee.employeeHome'),
                'Admin'    => redirect()->route('admin.adminHome'),
                default    => redirect()->route('login')->with('error', 'Unauthorized role'),
            };
        }

        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
            ->withInput();
    }

}
