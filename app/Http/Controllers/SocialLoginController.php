<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function socialLogin(Request $request): JsonResponse
    {
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

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successfully.',
            'data' => [
                'token' => $token,
                'user' => $user,
            ]
        ]);
    }
}
