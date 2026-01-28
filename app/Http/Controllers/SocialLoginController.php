<?php

namespace App\Http\Controllers;

use App\Models\RefreshAccessToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function socialLogin(Request $request): JsonResponse
    {
        try {
            $provider = $request->provider;
            $token = $request->token;

            $socialUser = Socialite::driver($provider)->userFromToken($token);

            $user = User::updateOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random()),
                    'role' => 'user'
                ]
            );

            // auth access token
            $accessToken  = $user->createToken('auth_token');

            // refresh token access token to refresh old validate old token
            $refreshToken = bin2hex(random_bytes(40));

            RefreshAccessToken::create([
                'user_id' => $user->id,
                'refresh_token' => hash('sha256', $refreshToken),
                'access_token_id' => $accessToken->accessToken->id,
                'expires_in' => now()->addDays(7),
            ]);

            // 🔥 RELOAD USER PROPERLY
            $user = User::with('addresses')
                ->withCount('orders')
                ->find($user->id);


            return response()->json([
                'success' => true,
                'message' => 'Login successfully.',
                'data' => [
                    'token' => $accessToken->plainTextToken,
                    'user' => $user,
                ]
            ])->cookie('refresh_token',
                $refreshToken,
                60 * 24 * 7,
                '/',
                null,
                app()->environment('production'), // secure in prod
                true,                              // httpOnly
                false,
                'lax');
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
