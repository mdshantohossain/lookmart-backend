<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function updateOrCreate(array $data, ?Permission $permission = null): ?Permission
    {
        try {
            $inputs = collect($data)->toArray();
            return $permission ? tap($permission)->update($inputs) : Permission::create($inputs);
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());
            return null;
        }
    }
}
