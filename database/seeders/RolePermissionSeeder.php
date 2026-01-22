<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Content permissions
            'view news',
            'create news',
            'edit news',
            'delete news',
            'view shows',
            'create shows',
            'edit shows',
            'delete shows',
            'view events',
            'create events',
            'edit events',
            'delete events',
            
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Admin permissions
            'access admin panel',
            'manage settings',
            'view analytics',
            'manage advertising',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles (admin, user only)
        // Note: "guest" is not a role - guests are unauthenticated users handled by 'guest' middleware
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Assign all permissions to admin
        $adminRole->givePermissionTo(Permission::all());

        // Assign basic read permissions to user role
        $userRole->givePermissionTo([
            'view news',
            'view shows',
            'view events',
        ]);

        // Assign roles to existing users based on their role field
        User::where('role', 'admin')->get()->each(function ($user) use ($adminRole) {
            $user->assignRole($adminRole);
        });

        // All other users (including dj/editor from old enum) get user role
        User::where('role', '!=', 'admin')
            ->orWhereNull('role')
            ->get()
            ->each(function ($user) use ($userRole) {
                $user->assignRole($userRole);
            });
    }
}
