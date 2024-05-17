<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Class EquipmentSeeder
 *
 * This class represents a seeder for populating the equipment table with initial data.
 * It seeds the equipment table with two sample equipment: Equipment 1 and Equipment 2.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group1 = EquipmentGroup::where('name', 'Group 1')->first();
        $group2 = EquipmentGroup::where('name', 'Group 2')->first();

        Equipment::create([
            'name' => 'Equipment 1',
            'operational' => true,
            'equipment_group_id' => $group1->id,
        ]);

        Equipment::create([
            'name' => 'Equipment 2',
            'operational' => false,
            'equipment_group_id' => $group2->id,
        ]);
    }
}
