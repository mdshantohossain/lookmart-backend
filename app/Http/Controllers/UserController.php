<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Env;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.user.index', [
            'users' => User::with('roles', 'roles.permissions')->whereNotIn('id', [1])->get(),
        ]);
    }

    public function create(): View
    {
        // check permission of current user
        isAuthorized('user create');

        return view('admin.user.create', [
            'roles' => Role::whereNotIn('id', [1])->get()
        ]);
    }

    public function store(UserRequest $userRequest, UserService $userService): RedirectResponse
    {
        // check permission of current user
        isAuthorized('user create');

        $user = $userService->updateOrCreate($userRequest->validated());

        if(!$user)  return redirect()->route('users.index')->with('error', 'User not created');

        return redirect()->route('users.index')->with('success', 'User Created Successfully');
    }

    public function edit(User $user): View
    {
        // check permission of current user
        isAuthorized('user edit');

        return view('admin.user.edit', compact('user'));
    }

    public function update(UserRequest $userRequest, User $user, UserService $userService): RedirectResponse
    {
        // check permission of current user
        isAuthorized('user edit');

        $updatedUser = $userService->updateOrCreate($userRequest->validated(), $user);

        logger()->info($updatedUser);

        if(!$updatedUser) return redirect()->route('users.index')->with('error', 'User was not updated');

        return redirect()->route('users.index')->with('success', 'User updated Successfully');
    }

    public function destroy(User $user): RedirectResponse
    {
        // check permission of current user
        isAuthorized('user destroy');

        $user->delete();

        return back()->with('success', 'User Deleted Successfully');
    }
}
