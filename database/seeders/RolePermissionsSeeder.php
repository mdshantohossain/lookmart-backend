<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'dashboard show',

            'category create',
            'category edit',
            'category delete',

            'sub-category create',
            'sub-category edit',
            'sub-category destroy',

            'catalog module',

            'product create',
            'product edit',
            'product show',
            'product destroy',

            'product policy create',
            'product policy edit',
            'product policy destroy',

            'order module',
            'order update',
            'order send',
            'order invoice download',

            'transaction module',
            'transaction detail',

            'acs module',
            'role create',
            'role edit',
            'role destroy',

            'permission create',
            'permission edit',
            'permission destroy',

            'user create',
            'user edit',
            'user destroy',

            'email module',
            'single email module',
            'multiple email module',
            'send single email',
            'send multiple email',

            'review show',
            'review create',
            'review destroy',

            'app-management create',
            'app-management update',
            'app-management destroy',

            'app-credential module',
            'app-credential store',
            'app-credential update',

            'shipping management module',

            'app cache clear',
        ];

        $users = [
            0 => [
                'name' => 'Super Admin',
                'email' => 'hr.hridoykhan2020.mh@gmail.com',
                'password' => Hash::make('password'),
                'status' => 1,
            ],
            1 => [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'phone' => '01799630233',
                'status' => 1,
            ],
            2 => [
                'name' => 'guest',
                'email' => 'guest@lookmartbd.com',
                'password' => Hash::make('password'),
                'status' => 1,
            ]
        ];

        $roles = ['super-admin', 'admin', 'guest'];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        foreach ($roles as $role) {
           $assignedRole = Role::create(['name' => $role]);

           if($assignedRole->name !== 'guest'){
            $assignedRole->syncPermissions($permissions);
           }
        }

        foreach ($users as $user) {
            $user = User::create($user);
            if($user->id !== 3) {
                $user->assignRole($roles);
            }
        }
    }
}
