<?php

namespace Database\Factories;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class LocationFactory
 *
 * This class represents a factory for generating test data for the Location model.
 * It extends the Illuminate\Database\Eloquent\Factories\Factory class and defines
 * the default state for the Location model.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class LocationFactory extends Factory
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
            'site_id' => Site::factory(),
        ];
    }
}
