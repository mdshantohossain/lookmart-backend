<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\RefreshAccessToken;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(LoginRequest $request): RedirectResponse
    {
        $user = User::firstWhere('email', $request->email);

        if ($user && password_verify($request->password, $user->password)) {
            Auth::login($user);

            return redirect()->intended()->with('success', 'Login Successful');
        }

        return back()->with('credentialError', 'Credentials do not match in our records');
    }

    public function login(LoginRequest $request, AuthService $authService): JsonResponse
    {
        return $authService->login($request->validated());
    }

    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $refreshToken = $request->cookie('refresh_token');

            if (! $refreshToken) {
                return response()->json(['message' => 'No refresh token provided'], 401);
            }

            $refreshToken = RefreshAccessToken::firstWhere('refresh_token', $refreshToken); // retrieve current user for refresh old token

            if (! $refreshToken) {
                return response()->json(['message' => 'Refresh token invalid or expired'], 401);
            }

            $user = User::find($refreshToken->user_id);

            if (! $user) {
                return response()->json(['message' => 'User not found'], 401);
            }

            $newRefreshToken = bin2hex(random_bytes(40));

            $refreshToken->update([
                'refresh_token' => $newRefreshToken
            ]);

            $newAccessToken = $user->createToken('auth_token')->plainTextToken;

            // Return new access token + set new refresh cookie (rotating)
            return response()->json([
                'success' => true,
                'token' => $newAccessToken
            ])->cookie('refresh_token', $newRefreshToken, 60*24*7, null, null, false, false, true);

        } catch (\Throwable $th) {
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
}
