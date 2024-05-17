<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ManipFactory
 *
 * This class represents a factory for generating test data for the Manip model.
 * It extends the Illuminate\Database\Eloquent\Factories\Factory class and defines
 * the default state for the Manip model.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class ManipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $beginDate = fake()->dateTimeThisMonth();

        $hour = fake()->numberBetween(7, 17);
        $minute = ($hour == 17) ? 0 : fake()->randomElement([0, 30]);
        $second = 0;

        $beginDate->setTime($hour, $minute, $second);

        $endDate = clone $beginDate;
        $endDate->modify('+2 hour');

        $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
        $formattedEndDate = $endDate->format('Y-m-d H:i:s');

        return [
            'name' => fake()->text(10),
            'user_id' => User::factory(),
            'begin_date' => $formattedBeginDate,
            'end_date' => $formattedEndDate,
            'location_id' => Location::factory(),
        ];
    }
}
