<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class EquipmentGroupFactory
 *
 * This class represents a factory for generating test data for the EquipmentGroup model.
 * It extends the Illuminate\Database\Eloquent\Factories\Factory class and defines
 * the default state for the EquipmentGroup model.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class EquipmentGroupFactory extends Factory
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
            'category_id' => Category::factory(),
        ];
    }
}
