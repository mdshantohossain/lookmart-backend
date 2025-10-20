<?php

namespace App\Services;

use Spatie\Permission\Models\Role;

class RoleService
{
    public function updateOrCreate(array $data, ?Role $role = null): ?Role
    {
        try {
            $inputs = collect($data)->toArray();

            $assignedRole = $role ? tap($role)->update($inputs) : Role::create($inputs);
            $assignedRole->syncPermissions($inputs['permissions']);

            return $assignedRole;
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());
            return null;
        }
    }
}
