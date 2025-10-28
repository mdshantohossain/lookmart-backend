<?php

namespace App\Services;

use App\Http\Controllers\RegistrationController;
use App\Http\Requests\RegistrationRequest;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthService
{
    public function login()
    {

    }

    public function register(RegistrationRequest $registrationRequest): void
    {
        try {
            $inputs = collect($registrationRequest)->toArray();
            $user = User::create([
                'name' => $inputs['name'],
                'email' => $inputs['email'],
                'password' => bcrypt($inputs['password']),
                'phone' => $inputs['phone'] ?? null,
            ]);

            // Generate unique verification token
            $token = Str::random(64);

            // Save token in a new table (see next step)
            DB::table('email_verification_tokens')->insert([
                'user_id' => $user->id,
                'token' => $token,
                'created_at' => now(),
            ]);

            // Send verification email
            Mail::to($user->email)->send(new VerificationMail($user, $token));

//            return response()->json([
//                'message' => 'Registration successful! Please verify your email address.',
//            ]);
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

    }
}
