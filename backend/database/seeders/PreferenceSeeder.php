<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Location;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Class PreferenceSeeder
 *
 * This class represents a seeder for populating the preferences table with initial data.
 * It seeds the preferences table with one sample preference: Preferred manip.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class PreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipment1 = Equipment::where('name', 'Equipment 1')->first();
        $equipment2 = Equipment::where('name', 'Equipment 2')->first();

        $team = User::where('name', 'User')->first();

        $user = User::where('name', 'Admin')->first();

        $location = Location::where('name', 'Location 1')->first();

        $preference = Preference::create([
            'name' => 'Preferred manip',
            'manip_name' => 'Manip 1',
            'user_id' => $user->id,
            'location_id' => $location->id,
        ]);

        $preference->equipment()->attach([$equipment1->id, $equipment2->id]);
        $preference->team()->attach([$team->id]);
    }
}
