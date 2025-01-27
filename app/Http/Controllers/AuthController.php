<?php

namespace App\Http\Controllers;

use App\Mail\TwoFactorCode;
use App\Mail\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $messages = [
            'name.unique' => 'User or Email already exists.',
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 6 characters.',
            'name.max' => 'Name must be at most 30 characters.',
            'name.string' => 'Name must be a string.',

            'email.unique' => 'User or Email already exists.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must be at most 16 characters.',
            'password.string' => 'Password must be a string.',
            'password.regex' => 'Password must be between 8 and 16 characters and include at least one uppercase letter, one number, and one special character.',
        ];

        $request->validate([
            'name' => 'required|string|max:30|min:6|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|max:16|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
        ], $messages);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Mail::to($user->email)->send(new EmailVerification($user));

        return redirect()->route('register')->with('success', 'Account successfully created, an email has been sent to verify your email address.');
    }

    public function verifyEmail($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'User not found.']);
        }

        if (!URL::hasValidSignature(request())) {
            return redirect()->route('login')->withErrors(['error' => 'Invalid or expired verification link.']);
        }

        $user->active = 1;
        $user->save();

        return redirect()->route('login')->with('success', 'Your email has been verified. You can now log in.');
    }

  
    public function login(Request $request)
    {
        $messages = [
            'name.required' => 'Username is required.',
            'name.min' => 'Username must be at least 6 characters.',
            'name.max' => 'Username must be at most 30 characters.',
            'name.string' => 'Username must be a string.',

            'password.required' => 'Password is required.',
        ];

        $request->validate([
            'name' => 'required|string|min:6|max:30',
            'password' => 'required',
        ], $messages);

        $user = User::where('name', $request->name)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if (!$user->active) {
                return back()->withErrors(['error' => 'Your account is not active. Please verify your email or contact support.']);
            }

            $code = rand(10000, 99999);

            $user->update([
                'two_factor_code' => Hash::make($code),
                'two_factor_expires_at' => now()->addMinutes(5),
            ]);

            Mail::to($user->email)->send(new TwoFactorCode($code, $user));

            session(['auth_user_id' => $user->id]);

            return redirect()->route('authentication');
        }

        return back()->withErrors(['error' => 'Invalid username or password.']);
    }

    public function verifyTwoFactor(Request $request)
    {
        $userId = session('auth_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'No user session found.']);
        }

        $request->validate([
            'auth1' => 'required|numeric',
            'auth2' => 'required|numeric',
            'auth3' => 'required|numeric',
            'auth4' => 'required|numeric',
            'auth5' => 'required|numeric',
        ]);

        $code = implode('', [
            $request->auth1,
            $request->auth2,
            $request->auth3,
            $request->auth4,
            $request->auth5,
        ]);

        if (Hash::check($code, $user->two_factor_code) && $user->two_factor_expires_at->isFuture()) {
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

            Auth::login($user);

            session()->forget('auth_user_id');

            return redirect()->route('home');
        }

        return back()->withErrors(['two_factor_code' => 'The authentication code is incorrect or has expired.']);
    }
}
