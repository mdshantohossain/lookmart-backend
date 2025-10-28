<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function updateOrCreate(array $data, ?Permission $permission = null): ?Permission
    {
        DB::beginTransaction();
        try {
            $inputs = collect($data)->toArray();
            return $permission ? tap($permission)->update($inputs) : Permission::create($inputs);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception->getMessage());
            return null;
        }
    }
}
