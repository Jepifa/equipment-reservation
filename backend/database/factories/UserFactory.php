<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class UserFactory
 *
 * This class represents a factory for generating test data for the User model.
 * It extends the Illuminate\Database\Eloquent\Factories\Factory class and defines
 * the default state for the User model.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'validated' => true,
            'color' => fake()->hexColor(),
        ];
    }
}
