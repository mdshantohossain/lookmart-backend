<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignInOrSignUpRequest;
use App\Models\RefreshAccessToken;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // admin login
    public function index(LoginRequest $request): RedirectResponse
    {
        $user = User::firstWhere('email', $request->email);

        if ($user && password_verify($request->password, $user->password)) {
            Auth::login($user);

            return redirect()->intended()->with('success', 'Login Successful');
        }

        return back()->with('credentialError', 'Credentials do not match in our records');
    }

    // user login
    /**
     * @throws \Throwable
     */
    public function login(LoginRequest $request, AuthService $authService): JsonResponse
    {
        $response = $authService->login($request->validated());

        if(empty($response['payload'])) {
            return response()->json([
                'status' => false,
                'message' => "Credential didn't match in our records",
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successfully.',
            'data' => $response['payload']
        ])->cookie('refresh_token', $response['refresh_token'], 60 * 24 * 7, '/', null, app()->environment('production'), true, false, 'lax');
    }

    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $cookieToken = $request->cookie('refresh_token');

            if (! $cookieToken) {
                return response()->json(['message' => 'No refresh token found'], 401);
            }

            // retrieve current user for refresh old token
            $refreshToken = RefreshAccessToken::firstWhere('refresh_token', hash('sha256', $cookieToken));

            if (! $refreshToken) {
                return response()->json(['message' => 'Refresh token invalid or expired'], 401);
            }

            $user = User::find($refreshToken->user_id);

            if (! $user) {
                return response()->json(['message' => 'User not found'], 401);
            }

            $newRefreshToken = bin2hex(random_bytes(40));

            $refreshToken->update([
                'refresh_token' => hash('sha256', $newRefreshToken),
                'expires_in' => now()->addDays(7),
            ]);

            // Return new access token + set new refresh cookie (rotating)
            return response()->json([
                'success' => true,
                'token' => $user->createToken('auth_token')->plainTextToken
            ])->cookie('refresh_token', $newRefreshToken, 60*24*7, null, null, false, false, true);

        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(AuthService $authService): JsonResponse
    {
        return $authService->logout();
    }

    public function signinOrSignup(SignInOrSignUpRequest $request, AuthService $authService): JsonResponse
    {
        $user = User::firstWhere('email', $request->email);

        if (! $user) {
            return $authService->register($request->validated());
        }

        return $authService->login($user);

    }
}
