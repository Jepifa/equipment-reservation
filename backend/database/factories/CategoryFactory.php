<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class CategoryFactory
 *
 * This class represents a factory for generating test data for the Category model.
 * It extends the Illuminate\Database\Eloquent\Factories\Factory class and defines
 * the default state for the Category model.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class CategoryFactory extends Factory
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
        ];
    }
}
