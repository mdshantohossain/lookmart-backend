<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('admin.roles.index', [
            'roles' => Role::whereNotIn('id', [1])->with('permissions')->get(),
        ]);
    }

    public function create(): View
    {
        // check permission of current user
        isAuthorized('role create');

        return view('admin.roles.create', [
            'permissions' => Permission::all(),
        ]);
    }

    public function store(RoleRequest $roleRequest, RoleService $roleService): RedirectResponse
    {
        // check permission of current user
        isAuthorized('role create');

        $role = $roleService->updateOrCreate($roleRequest->validated());

        if(!$role)  return redirect()->route('roles.index')->with('error', 'Role not created');

        return back()->with('success', 'Role Created Successfully');
    }

    public function edit(Role $role): View
    {
        // check permission of current user
        isAuthorized('role edit');

        return view('admin.roles.edit', [
            'role' => $role,
            'rolePermissions' => $role->permissions->pluck('name')->toArray(),
            'permissions' => Permission::all(),
        ]);
    }

    public function update(RoleRequest $roleRequest, Role $role, RoleService $roleService): RedirectResponse
    {
        // check permission of current user
        isAuthorized('user edit');

        $updatedRole = $roleService->updateOrCreate($roleRequest->validated(), $role);

        if(!$updatedRole)  return redirect()->route('roles.index')->with('error', 'Role was not updated');

        return redirect()->route('roles.index')->with('success', 'Role updated Successfully');
    }

    public function destroy(Role $role): RedirectResponse
    {
        // check permission of current user
        isAuthorized('user destroy');

        $role->delete();

        return back()->with('success', 'Role Deleted Successfully');
    }
}
