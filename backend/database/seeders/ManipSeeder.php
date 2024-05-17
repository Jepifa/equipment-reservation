<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Location;
use App\Models\Manip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Class ManipSeeder
 *
 * This class represents a seeder for populating the manips table with initial data.
 * It seeds the manips table with one sample manipulation: Manip 1.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class ManipSeeder extends Seeder
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

        $manip1 = Manip::create([
            'name' => 'Manip 1',
            'user_id' => $user->id,
            'begin_date' => Carbon::parse('2024-03-01 08:00:00'),
            'end_date' => Carbon::parse('2024-03-01 12:00:00'),
            'location_id' => $location->id,
        ]);

        $manip1->equipment()->attach([$equipment1->id, $equipment2->id]);
        $manip1->team()->attach([$team->id]); 
    }
}
