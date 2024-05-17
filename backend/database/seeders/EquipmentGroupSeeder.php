<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\EquipmentGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Class EquipmentGroupSeeder
 *
 * This class represents a seeder for populating the equipment-groups table with initial data.
 * It seeds the equipment-groups table with two sample equipment groups: Group 1 and Group 2.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class EquipmentGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category1 = Category::where('name', 'Category 1')->first();
        $category2 = Category::where('name', 'Category 2')->first();

        EquipmentGroup::create([
            'name' => 'Group 1',
            'category_id' => $category1->id,
        ]);

        EquipmentGroup::create([
            'name' => 'Group 2',
            'category_id' => $category2->id,
        ]);
    }
}
