<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
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
}
