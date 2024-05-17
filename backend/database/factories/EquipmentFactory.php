<?php

namespace Database\Factories;

use App\Models\EquipmentGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class EquipmentFactory
 *
 * This class represents a factory for generating test data for the Equipment model.
 * It extends the Illuminate\Database\Eloquent\Factories\Factory class and defines
 * the default state for the Equipment model.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(10),
            'equipment_group_id' => EquipmentGroup::factory(),
            'operational' => true
        ];
    }
}
