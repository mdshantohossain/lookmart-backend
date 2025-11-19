<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\MailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function index(MailService $mailService): JsonResponse
    {
        try {
            $user = User::firstWhere('email', request('email'));

            if(!$user) {
                return response()->json([
                    'success' => false,
                    'message' => "Credential didn't match in our records"
                ], 404);
            }

            // Check if a reset token already exists for this email
            $existingToken = DB::table('password_reset_tokens')
                ->where('email', $user->email)
                ->first();

            if ($existingToken) {
                return response()->json([
                    'success' => false,
                    'message' => "A password reset link has already been sent to your email. Please check your inbox."
                ], 409); // 409 Conflict
            }

            // If not found, create a new token
            $token = Str::random(60);

            // generate token for reset password
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => now(),
            ]);

            $url = config('app.frontend_url') . '/reset-password?token=' . $token;

            // send resend email
            $mailService->resetPasswordMail($user, $url);

            return response()->json([
                'success' => true,
                'message' => "Password resend link successfully sent to your email"
            ], 201);
        } catch (\Throwable $e) {
            logger()->error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Credential didn't match in our records"
            ], 500);
        }
    }

    public function store(Request $request, AuthService $authService): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|min:8|max:16',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validated->errors()
            ], 422);
        }

       return $authService->createNewPassword($validated->validated());
    }
}
