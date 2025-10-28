<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && password_verify($request->password, $user->password)) {
            Auth::login($user);

            return response()->json($user);
        }

        return back()->with('credentialError', 'Credentials do not match in our records');
    }
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && password_verify($request->password, $user->password)) {
            Auth::login($user);

            if($user->role === 'user') {

                $token = $user->createToken('login')->plainTextToken;

                return response()->json([
                    'status' =>  'success',
                    'message' => 'Login Successful',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                    ]
                ]);
            }

            return redirect('/')->with('success', 'Login is successful');
        }

        return back()->with('credentialError', 'Credentials do not match in our records');
    }
}
