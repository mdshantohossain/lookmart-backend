<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Services\PermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(): View
    {
        return view('admin.permissions.index', [
            'permissions' => Permission::all(),
        ]);
    }

    public function create(): View
    {
        // check permission of current user
        isAuthorized('permission create');

        return view('admin.permissions.create');
    }

    public function store(PermissionRequest $permissionRequest, PermissionService $permissionService): RedirectResponse
    {
        // check permission of current user
        isAuthorized('permission store');

        $permission = $permissionService->updateOrCreate($permissionRequest->validated());

        if(!$permission)  return redirect()->route('permissions.index')->with('error', 'Permission not created');

        return back()->with('success', 'Permission Created Successfully');
    }

    public function edit(Permission $permission): View
    {
        // check permission of current user
        isAuthorized('permission edit');

        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(PermissionRequest $permissionRequest, Permission $permission, PermissionService $permissionService): RedirectResponse
    {
        // check permission of current user
        isAuthorized('permission update');

        $updatedPermission = $permissionService->updateOrCreate($permissionRequest->validated(), $permission);

        if(!$updatedPermission) return redirect()->route('permissions.index')->with('error', 'Permission was not updated');

        return redirect()->route('permissions.index')->with('success', 'Permission updated Successfully');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        // check permission of current user
        isAuthorized('permission destroy');

        $permission->delete();

        return back()->with('success', 'Permission Deleted Successfully');
    }
}
