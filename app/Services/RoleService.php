<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function updateOrCreate(array $data, ?Role $role = null): ?Role
    {
        DB::beginTransaction();
        try {
            $inputs = collect($data)->toArray();

            $assignedRole = $role ? tap($role)->update($inputs) : Role::create($inputs);
            $assignedRole->syncPermissions($inputs['permissions']);

            DB::commit();
            return $assignedRole;
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception->getMessage());
            return null;
        }
    }
}
