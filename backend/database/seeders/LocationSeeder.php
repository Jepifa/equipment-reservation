<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Site;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Class LocationSeeder
 *
 * This class represents a seeder for populating the locations table with initial data.
 * It seeds the locations table with two sample locations: Location 1 and Location 2.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $site1 = Site::where('name', 'Site 1')->first();
        $site2 = Site::where('name', 'Site 2')->first();

        Location::create([
            'name' => 'Location 1',
            'site_id' => $site1->id,
        ]);

        Location::create([
            'name' => 'Location 2',
            'site_id' => $site2->id,
        ]);
    }
}
