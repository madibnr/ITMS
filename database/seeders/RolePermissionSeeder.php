<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $admin = Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'Full system access']);
        $manager = Role::create(['name' => 'Manager', 'slug' => 'manager', 'description' => 'Department manager with reporting access']);
        $itStaff = Role::create(['name' => 'IT Staff', 'slug' => 'it-staff', 'description' => 'IT technician / support staff']);
        $user = Role::create(['name' => 'User', 'slug' => 'user', 'description' => 'Regular end user']);

        // Create Permissions
        $modules = ['tickets', 'assets', 'incidents', 'changes', 'maintenance', 'reports', 'users'];
        $actions = ['view', 'create', 'edit', 'delete'];
        $permissions = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissions[] = Permission::create([
                    'name' => ucfirst($action) . ' ' . ucfirst($module),
                    'slug' => "{$module}.{$action}",
                    'module' => $module,
                ]);
            }
        }

        // Assign all permissions to Admin
        $admin->permissions()->attach(Permission::all());

        // Manager: view/create/edit on most, no delete, no user management
        $managerPermissions = Permission::whereIn('slug', [
            'tickets.view', 'tickets.create', 'tickets.edit',
            'assets.view',
            'incidents.view', 'incidents.create', 'incidents.edit',
            'changes.view', 'changes.create', 'changes.edit',
            'maintenance.view',
            'reports.view',
        ])->get();
        $manager->permissions()->attach($managerPermissions);

        // IT Staff: ticket/asset/incident/maintenance management
        $itPermissions = Permission::whereIn('slug', [
            'tickets.view', 'tickets.create', 'tickets.edit',
            'assets.view', 'assets.create', 'assets.edit',
            'incidents.view', 'incidents.create', 'incidents.edit',
            'changes.view', 'changes.create',
            'maintenance.view', 'maintenance.create', 'maintenance.edit',
        ])->get();
        $itStaff->permissions()->attach($itPermissions);

        // Regular user: ticket view & create only
        $userPermissions = Permission::whereIn('slug', [
            'tickets.view', 'tickets.create',
        ])->get();
        $user->permissions()->attach($userPermissions);
    }
}
