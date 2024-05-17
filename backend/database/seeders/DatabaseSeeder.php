<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 *
 * This class represents the main seeder for populating the application's database with initial data.
 * It seeds the users table with sample user data (User and Admin), and then calls other seeder classes
 * to populate additional tables such as permissions, categories, equipment groups, equipment, sites,
 * locations, manipulations, and preferences.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);
        $this->call([
            PermissionsSeeder::class,
            CategorySeeder::class,
            EquipmentGroupSeeder::class,
            EquipmentSeeder::class,
            SiteSeeder::class,
            LocationSeeder::class,
            ManipSeeder::class,
            PreferenceSeeder::class,
        ]);
    }
}
