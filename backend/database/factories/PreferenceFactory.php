<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class PreferenceFactory
 *
 * This class represents a factory for generating test data for the Preference model.
 * It extends the Illuminate\Database\Eloquent\Factories\Factory class and defines
 * the default state for the Preference model.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class PreferenceFactory extends Factory
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
            'manip_name' => fake()->text(10),
            'user_id' => User::factory(),
            'location_id' => Location::factory(),
        ];
    }
}
