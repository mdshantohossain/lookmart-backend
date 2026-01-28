<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\UserAddress;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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

    public function updateProfile(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);

        try {
            if($validated->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validated->errors()
                ], 422);
            }

            $user = $request->user();

            $user->update($validated->validated());

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'name' => $user->name,
                    'phone' => $user->phone,
                ]
            ]);

        } catch (\Throwable $e) {
            report($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }

    public function saveAddress(UserAddressRequest $request): JsonResponse
    {
        try {
           $address = UserAddress::create($request->all());

           if(!$address) {
               return response()->json([
                   'success' => false,
                   'message' => 'Server error occurred'
               ], 500);
           }

            return response()->json([
                'success' => true,
                'message' => 'Address saved successfully',
                'data' => $address
            ], 201);

        } catch (\Throwable $e) {
            report($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }

    public function updateAddress(UserAddressRequest $request): JsonResponse
    {
        try {
            if(!$request->id) {
                return response()->json([
                    'success' => false,
                    'message' =>'Invalid address'
                ], 400);
            }

            $address = UserAddress::find($request->id);

            if(!$address) {
                return response()->json([
                    'success' => false,
                    'message' =>'address not found'
                ], 404);
            }

            $address->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'data' => $address
            ]);

        } catch (\Throwable $e) {
            report($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }

    public function deleteAddress(int $id): JsonResponse
    {
        try {
            $address = UserAddress::find($id);

            if(!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address not found'
                ], 404);
            }

            $address->delete();

            return response()->json([
                'success' => true,
                'message' => 'Address deleted successfully',
            ]);
        } catch (\Throwable $e) {
            report($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'current_password' => 'required',
            'password' => 'required|string|min:8',
            'confirmation_password' => 'required|string|min:8|same:password',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors()
            ], 422);
        }

        try {

            $user = $request->user();

            if(!$user) {
                return response()->json([
                    'success' => false,
                    'message' => "Session expired. Try again"
                ], 401);
            }

            $validated = $validator->validated();

            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 422);
            }

            $user->update([
                'password' => bcrypt($validated['password'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }
}
