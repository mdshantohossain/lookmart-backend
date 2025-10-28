<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function updateOrCreate(array $data, ?User $user = null): ?User
    {
        DB::beginTransaction();
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

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e->getMessage());
            return null;
        }
    }
}
