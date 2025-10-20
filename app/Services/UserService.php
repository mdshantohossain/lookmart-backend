<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function updateOrCreate(array $data, ?User $user = null): ?User
    {
        try {
            $inputs = collect($data)->toArray();

            if($user) {
               $user = tap($user)->update($inputs);
            } else {
               $user = User::create([
                   'name' => $inputs['name'],
                   'email' => $inputs['email'],
                   'phone' => $inputs['phone'],
                   'password' => Hash::make($inputs['password']),
               ]);
            }

            $user->syncRoles([$inputs['role']]);

            return $user;
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
            return null;
        }
    }
}
