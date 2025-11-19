<?php

namespace App\Services;

use App\Models\EmailVerification;
use App\Models\RefreshAccessToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    protected MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * @param array{email: string, password: string} $data
     * @return JsonResponse
     */
    public function login(array $data): JsonResponse
    {
        try {
            $user = User::firstWhere('email', $data['email']);

            if (! $user) {
                return response()->json([
                    'status' => false,
                    'message' => "Credential didn't match in our records",
                ], 400);
            }

            if($user && Hash::check($data['password'], $user->password)) {
                $token = $user->createToken('auth_token')->plainTextToken; // auth token for every request

                $refreshToken = bin2hex(random_bytes(40)); // refresh token to valid old auth token

                RefreshAccessToken::create([
                    'user_id' => $user->id,
                    'refresh_token' => $refreshToken,
                    'expires_in' => now()->addDays(7),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Login successfully.',
                    'data' => [
                        'token' => $token,
                        'user' => $user,
                    ]
                ])->cookie('refresh_token', $refreshToken, 60*24, null, null, false, true);
            }

            return response()->json([
                'success' => false,
                'message' => "Credential didn't match in our records",
            ], 400);

        } catch (\Throwable $e) {
            logger()->error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @param array{name: string, email: string, password: string} $data
     * @return JsonResponse
     */
    public function register(array $data): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role' => 'user'
            ]);

            if (! $user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An unexpected error occurred. Please try again later.',
                ], 500);
            }

            $this->createOrSendTokenToEmail($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful.'
            ], 201);
        } catch (\Throwable $e) {
            logger($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => app()->environment('local')
                    ? $e->getMessage()
                    : 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * @param string $token
     * @return JsonResponse
     */
    public function verifyEmail(string $token): JsonResponse
    {
        try {
            $record = EmailVerification::where('token', $token)
                ->where('expires_at', '>', now())
                ->first();

            if(! $record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your verification link has expired. Please request a new one.',
                ], 400);
            }

            // If already verified
            if ($record->user->email_verified_at) {
                $record->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Your email is already verified.',
                ]);
            }

            // update verify time
            $record->user->update(['email_verified_at' => now()]);

            $record->delete();

            // send welcome email
            $this->mailService->welcomeMail($record->user);

            return response()->json([
                'success' => true,
                'message' => 'Your email has been verified successfully. Login to continue.',
            ]);

        } catch (\Throwable $exception) {
            logger($exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * @param string $token
     * @return JsonResponse
     */
    public function resendVerifyEmail(string $token): JsonResponse
    {
        try {
            $record = EmailVerification::firstWhere('token', $token);

            if (! $record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired token. Please check to login or register again.',
                ], 400);
            }

            $user = $record->user;

            // Delete old token and generate new one
            $record->delete();

            // if already verified
            if ($user->email_verified_at) {

                return response()->json([
                    'success' => true,
                    'message' => 'Your email is already verified.',
                ]);
            }

            $this->createOrSendTokenToEmail($user);

            return response()->json([
                'success' => true,
                'message' => "We’ve sent a password reset link to your email address. Please check
                              your inbox and follow the instructions to reset your password.",
            ], 201);
        } catch (\Throwable $exception) {
            logger($exception->getMessage());
            return response()->json([
                'success' => false,
                'message'  => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * @param User $user
     * @return void
     */
    protected function createOrSendTokenToEmail(User $user): void
    {
        $token = Str::uuid();

        EmailVerification::create([
            'user_id'    => $user->id,
            'token'      => $token,
            'expires_at' => now()->addMinutes(30),
        ]);

        $verificationUrl = config('app.frontend_url') . "/verify-email?token=" . $token;

        // Send verification email again
        $this->mailService->verificationMail($user, $verificationUrl);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function createNewPassword(array $data): JsonResponse
    {
        try {
            $record = DB::table('password_reset_tokens')
                        ->where('token', $data['token'])
                        ->first();

            if(! $record) {
                return response()->json([
                    'success' => false,
                    'message' => "Invalid or expired token."
                ], 400);
            }

            $user = User::firstWhere('email', $record->email);

            if(! $user) {
                return response()->json([
                  'success' => false,
                  'message' => "User not requested for forgot password."
                ], 400);
            }

            $user->password = Hash::make($data['password']);
            $user->save();

            // delete token after verification
            DB::table('password_reset_tokens')->where('token', $data['token'])->delete();

            return response()->json([
                'success' => true,
                'message' => "Password reset successful."
            ], 201);
        } catch (\Throwable $e) {
            logger()->error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function logout(): JsonResponse
    {
        $user = request()->user();
        $user->currentAccessToken()->revoke();

        RefreshAccessToken::where('user_id', $user->id)->delete();

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logged out'
        ])->cookie('access_token', '', -1); // delete cookie
    }
}
