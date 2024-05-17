<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Class PermissionsSeeder
 *
 * This class represents a seeder for populating the roles table with initial data.
 * It initializes the roles table with the two roles: admin and user.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[
            \Spatie\Permission\PermissionRegistrar::class
        ]->forgetCachedPermissions();

        // Create role
        Role::create(["name" => "admin"]);
        Role::create(["name" => "user"]);

        // Assign roles to users
        User::find(2)->assignRole('admin');
        User::find(1)->assignRole('user');
    }
}
