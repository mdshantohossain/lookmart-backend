<?php

namespace App\Http\Controllers;

use App\Models\EmailVerification;
use App\Services\AuthService;
use App\Services\MailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function index(Request $request, AuthService $authService): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'name'  => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:16',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => [
                    'email' => $validated->errors()->get('email')[0] ?? null,
                ]
            ], 422);
        }

        return $authService->register($validated->validated());
    }

    public function verifyEmail(AuthService $authService): JsonResponse
    {
        $token = request()->input('token');

        if(! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found.',
            ], 404);
        }

        return $authService->verifyEmail($token);
    }

    public function resendVerifyEmail(AuthService $authService): JsonResponse
    {
        $token = request()->input('token');

        if(! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found.',
            ], 404);
        }

        return $authService->resendVerifyEmail($token);
    }
}
