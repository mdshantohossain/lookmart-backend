<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function index(RegistrationRequest $request)
    {
       $user = User::create([
           'name' => $request['name'],
           'email' => $request['email'],
           'password' => bcrypt($request['password']),
           'role' => 'user'
       ]);

       Auth::login($user);

       return response()->json([
           'status' => 'success',
           'user' => $user,
           'message' => 'Registration and login successful'
       ]);
    }
}
