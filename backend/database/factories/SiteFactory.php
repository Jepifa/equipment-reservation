<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class SiteFactory
 *
 * This class represents a factory for generating test data for the Site model.
 * It extends the Illuminate\Database\Eloquent\Factories\Factory class and defines
 * the default state for the Site model.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class SiteFactory extends Factory
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
