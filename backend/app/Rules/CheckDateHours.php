<?php

namespace App\Rules;

use Closure;
use DateTime;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Class CheckDateHours
 *
 * This class defines a custom validation rule for checking if a date's hour falls within a specific range.
 * It validates whether the hour of the provided date is between 7 and 19, inclusive.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class CheckDateHours implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Convert the date string to a DateTime object
        $date = new \DateTime($value);

        // Get the hour and minute parts separately
        $hour = (int) $date->format('H');
        $minute = (int) $date->format('i');

        // Check if the hour is between 7 and 19
        // If the hour is 19, the minute must be 0 or less to be valid
        if (!(($hour >= 7 && $hour < 19) || ($hour === 19 && $minute === 0))) {
            $fail("The $attribute hour must be between 7 and 19.");
        }
    }
}
