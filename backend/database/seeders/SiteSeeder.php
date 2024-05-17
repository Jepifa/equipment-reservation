<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Class SiteSeeder
 *
 * This class represents a seeder for populating the sites table with initial data.
 * It seeds the sites table with two sample sites: Site 1 and Site 2.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Site::create([
            'name' => 'Site 1',
        ]);

        Site::create([
            'name' => 'Site 2',
        ]);
    }
}
